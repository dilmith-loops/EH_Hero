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

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

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

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    return {
      id: data.user.id,
      name: data.user.name,
      phone: data.user.phone || undefined,
    };
  } catch (error) {
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
