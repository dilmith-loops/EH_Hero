import React, { useState } from 'react';
import { ClothingAsset } from '../types';
import { GarmentType } from '../services/geminiService';
import { useClothingLibrary } from '../hooks/useClothingLibrary';

interface Props {
  garmentType: GarmentType;
  onSelect: (asset: ClothingAsset) => void;
  onClose: () => void;
}

const LABELS: Record<GarmentType, string> = {
  upper:   'Tops',
  lower:   'Bottoms',
  overall: 'Full Dress',
};

export const LibraryPicker: React.FC<Props> = ({ garmentType, onSelect, onClose }) => {
  const { assets, loading, error } = useClothingLibrary(garmentType);
  const [search, setSearch] = useState('');

  const filtered = assets.filter(a =>
    a.name.toLowerCase().includes(search.toLowerCase()),
  );

  return (
    <div
      className="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4"
      onClick={onClose}
    >
      <div
        className="bg-[#0f172a] border border-slate-700 rounded-2xl w-full max-w-2xl max-h-[80vh] flex flex-col shadow-2xl"
        onClick={e => e.stopPropagation()}
      >
        {/* Header */}
        <div className="flex items-center justify-between px-5 py-4 border-b border-slate-800">
          <h2 className="text-white font-semibold flex items-center gap-2">
            <i className="fas fa-layer-group text-blue-400" />
            Select from Library &mdash; {LABELS[garmentType]}
          </h2>
          <button onClick={onClose} className="text-slate-500 hover:text-white transition-colors">
            <i className="fas fa-times text-lg" />
          </button>
        </div>

        {/* Search */}
        <div className="px-5 py-3 border-b border-slate-800">
          <div className="relative">
            <i className="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm" />
            <input
              type="text"
              placeholder="Search by name..."
              value={search}
              onChange={e => setSearch(e.target.value)}
              className="w-full bg-slate-800 border border-slate-700 rounded-lg pl-9 pr-4 py-2 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors"
            />
          </div>
        </div>

        {/* Content */}
        <div className="flex-1 overflow-y-auto p-5">
          {loading && (
            <div className="flex items-center justify-center py-16 text-slate-500 gap-2">
              <i className="fas fa-spinner fa-spin" /> Loading library...
            </div>
          )}

          {error && (
            <div className="flex flex-col items-center justify-center py-16 text-red-400 gap-2">
              <i className="fas fa-triangle-exclamation text-3xl opacity-60" />
              <p className="text-sm">{error}</p>
            </div>
          )}

          {!loading && !error && filtered.length === 0 && (
            <div className="flex flex-col items-center justify-center py-16 text-slate-600">
              <i className="fas fa-box-open text-4xl mb-3 opacity-40" />
              <p className="text-sm font-medium">No items found</p>
              <p className="text-xs mt-1">Upload garments in the Clothing Dashboard first</p>
            </div>
          )}

          {!loading && !error && filtered.length > 0 && (
            <div className="grid grid-cols-3 sm:grid-cols-4 gap-3">
              {filtered.map(asset => (
                <button
                  key={asset.id}
                  onClick={() => onSelect(asset)}
                  className="group relative rounded-xl overflow-hidden bg-slate-800 border-2 border-transparent hover:border-blue-500 transition-all duration-200 text-left"
                >
                  <div className="aspect-[3/4] overflow-hidden bg-slate-900">
                    <img
                      src={asset.url}
                      alt={asset.name}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                  </div>
                  <div className="absolute inset-0 bg-blue-500/0 group-hover:bg-blue-500/10 transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                    <div className="bg-blue-600/90 text-white text-xs font-bold px-3 py-1.5 rounded-full">
                      Select
                    </div>
                  </div>
                  <p className="px-2 py-1.5 text-slate-300 text-[11px] truncate">{asset.name}</p>
                </button>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};
