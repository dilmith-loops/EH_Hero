import { useState, useEffect, useCallback } from 'react';
import { ClothingAsset } from '../types';
import { fetchAssets, uploadAsset, deleteAsset } from '../services/assetService';

export function useClothingLibrary(category?: ClothingAsset['category']) {
  const [assets, setAssets] = useState<ClothingAsset[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const load = useCallback(async () => {
    setLoading(true);
    setError(null);
    try {
      const data = await fetchAssets(category);
      setAssets(data);
    } catch (e: any) {
      setError(e.message ?? 'Failed to load assets');
    } finally {
      setLoading(false);
    }
  }, [category]);

  useEffect(() => { load(); }, [load]);

  const addAsset = useCallback(async (
    file: File,
    name: string,
    cat: ClothingAsset['category'],
  ) => {
    const asset = await uploadAsset(file, name, cat);
    // Only add to state if it matches the current filter (or no filter)
    if (!category || asset.category === category) {
      setAssets(prev => [asset, ...prev]);
    }
  }, [category]);

  const removeAsset = useCallback(async (id: string) => {
    await deleteAsset(id);
    setAssets(prev => prev.filter(a => a.id !== id));
  }, []);

  return { assets, loading, error, addAsset, removeAsset, refresh: load };
}
