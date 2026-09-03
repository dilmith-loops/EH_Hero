import { ClothingAsset } from '../types';

// BASE_URL is '/fiton/' on Hostinger, '/' locally — set by vite.config.ts `base`
const API = `${import.meta.env.BASE_URL}api/assets.php`;

export async function fetchAssets(category?: ClothingAsset['category']): Promise<ClothingAsset[]> {
  const url = category ? `${API}?category=${category}` : API;
  const res = await fetch(url);
  if (!res.ok) throw new Error(`Failed to fetch assets (${res.status})`);
  return res.json();
}

export async function uploadAsset(
  file: File,
  name: string,
  category: ClothingAsset['category'],
): Promise<ClothingAsset> {
  const form = new FormData();
  form.append('file', file);
  form.append('name', name);
  form.append('category', category);
  const res = await fetch(API, { method: 'POST', body: form });
  if (!res.ok) {
    const body = await res.json().catch(() => ({}));
    throw new Error(body.error ?? `Upload failed (${res.status})`);
  }
  return res.json();
}

export async function deleteAsset(id: string): Promise<void> {
  const res = await fetch(`${API}?id=${encodeURIComponent(id)}`, { method: 'DELETE' });
  if (!res.ok) throw new Error(`Delete failed (${res.status})`);
}

/** Fetch a remote image URL and return it as a base64 string + mimeType for the Gemini API. */
export async function imageUrlToBase64(url: string): Promise<{ base64: string; mimeType: string }> {
  const res = await fetch(url);
  if (!res.ok) throw new Error('Failed to load image from library');
  const blob = await res.blob();
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = () => {
      const dataUrl = reader.result as string;
      const [header, base64] = dataUrl.split(',');
      const mimeType = header.match(/:(.*?);/)?.[1] ?? 'image/jpeg';
      resolve({ base64, mimeType });
    };
    reader.onerror = reject;
    reader.readAsDataURL(blob);
  });
}
