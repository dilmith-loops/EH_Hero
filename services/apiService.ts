export interface UserInfo {
  id?: number;
  name: string;
  phone?: string;
}

export interface SaveGenerationPayload {
  app_user_id: number;
  treat_id: string;
  treat_name: string;
  style_id?: string;
  custom_prompt?: string;
  original_image?: string;
  generated_image: string;
}

export interface SavedGenerationResponse {
  id: number;
  app_user_id: number;
  treat_id: string;
  treat_name: string;
  style_id: string;
  custom_prompt?: string;
  original_image_url?: string;
  generated_image_url: string;
  created_at: string;
}

export interface LimitStatusResponse {
  limit_enabled: boolean;
  can_generate: boolean;
  max_limit: number;
  current_count: number;
  remaining: number | null;
  period: string;
  message: string | null;
  maintenance?: boolean;
}

export interface SystemStatusResponse {
  maintenance: boolean;
  message: string | null;
}

const API_BASE_URL = import.meta.env.VITE_API_URL || (import.meta.env.PROD ? '/EH-Hero/api' : 'http://localhost:8000/api');

/**
 * Check if the backend platform maintenance mode is enabled.
 */
export async function checkSystemStatus(): Promise<SystemStatusResponse> {
  try {
    const response = await fetch(`${API_BASE_URL}/system-status`);
    if (response.status === 503) {
      const data = await response.json().catch(() => ({}));
      return {
        maintenance: true,
        message: data.message || 'System under maintenance',
      };
    }
    if (!response.ok) throw new Error('Status check failed');
    const data = await response.json();
    return {
      maintenance: Boolean(data.maintenance),
      message: data.message || null,
    };
  } catch (error) {
    return {
      maintenance: false,
      message: null,
    };
  }
}

/**
 * Check if the current device/IP has reached the generation limit.
 */
export async function checkGenerationLimit(): Promise<LimitStatusResponse> {
  try {
    const response = await fetch(`${API_BASE_URL}/generations/limit-status`);
    if (response.status === 503) {
      const data = await response.json().catch(() => ({}));
      return {
        maintenance: true,
        limit_enabled: true,
        can_generate: false,
        max_limit: 0,
        current_count: 0,
        remaining: 0,
        period: 'maintenance',
        message: data.message || 'System under maintenance',
      };
    }
    if (!response.ok) throw new Error('Limit status fetch failed');
    return await response.json();
  } catch (error) {
    // If backend check fails, fail-open so the participant is not blocked
    return {
      limit_enabled: false,
      can_generate: true,
      max_limit: 999,
      current_count: 0,
      remaining: null,
      period: 'lifetime',
      message: null,
    };
  }
}

/**
 * Register or look up the participant on the Laravel MySQL backend.
 */
export async function registerParticipant(name: string, phone?: string): Promise<UserInfo> {
  try {
    const payload: { name: string; phone?: string | null } = { name: name.trim() };
    if (phone && phone.trim()) {
      payload.phone = phone.trim();
    }

    const response = await fetch(`${API_BASE_URL}/users`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    if (response.status === 429) {
      const errData = await response.json().catch(() => ({}));
      const err: any = new Error(errData.message || 'Too many generations, please try again.');
      err.isLimitReached = true;
      err.status = 429;
      throw err;
    }

    if (response.status === 503) {
      const errData = await response.json().catch(() => ({}));
      const err: any = new Error(errData.message || 'Platform is currently under maintenance.');
      err.isMaintenance = true;
      err.status = 503;
      throw err;
    }

    if (!response.ok) {
      throw new Error('Too many generations, please try again.');
    }

    const data = await response.json();
    return {
      id: data.user.id,
      name: data.user.name,
      phone: data.user.phone || undefined,
    };
  } catch (error: any) {
    if (error?.isLimitReached || error?.isMaintenance || error?.status === 429 || error?.status === 503) {
      throw error;
    }
    console.warn('Backend user registration error (continuing in offline mode):', error);
    // Fallback if backend is unreachable so user flow is not interrupted
    return {
      id: 1,
      name: name.trim(),
      phone: phone?.trim(),
    };
  }
}

/**
 * Save generated anime image and original photo to Laravel MySQL backend & storage.
 */
export async function saveGeneratedPortrait(payload: SaveGenerationPayload): Promise<SavedGenerationResponse | null> {
  try {
    const response = await fetch(`${API_BASE_URL}/generations`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    if (!response.ok) {
      const errText = await response.text();
      console.warn(`Failed to save generation: ${response.status} ${errText}`);
      return null;
    }

    const data = await response.json();
    return data.generation;
  } catch (error) {
    console.warn('Could not save generation to backend:', error);
    return null;
  }
}

/**
 * Fetch the Gemini API key dynamically from the backend .env at runtime.
 */
export async function fetchGeminiKeyFromBackend(): Promise<string | null> {
  try {
    const response = await fetch(`${API_BASE_URL}/gemini-key`, {
      headers: {
        'Accept': 'application/json',
      },
    });

    if (!response.ok) {
      console.warn(`[Backend API] /gemini-key responded with HTTP status ${response.status} (${response.statusText})`);
      return null;
    }

    const data = await response.json();
    if (data?.api_key && typeof data.api_key === 'string' && data.api_key.trim()) {
      return data.api_key.trim();
    } else {
      console.warn('[Backend API] /gemini-key returned empty api_key. Check backend/.env');
    }
  } catch (err) {
    console.warn('[Backend API] Could not connect to backend to retrieve Gemini key:', err);
  }
  return null;
}

