import React from 'react';
import { WonderTreatId } from '../types';
import { WONDER_TREATS } from '../services/geminiService';

interface WonderTreatSelectorProps {
  selectedTreatId: WonderTreatId;
  onSelectTreat: (treatId: WonderTreatId) => void;
}

export const WonderTreatSelector: React.FC<WonderTreatSelectorProps> = ({
  selectedTreatId,
  onSelectTreat,
}) => {
  const baseUrl = import.meta.env.BASE_URL || '/';

  return (
    <div className="space-y-1">
      {/* 2-Column Grid with padding to prevent active outline cutoff */}
      <div className="grid grid-cols-2 sm:grid-cols-3 gap-2.5 p-1">
        {WONDER_TREATS.map((treat) => {
          const isSelected = selectedTreatId === treat.id;
          const imageSrc = `${baseUrl}${treat.imageFileName}`;

          return (
            <button
              key={treat.id}
              type="button"
              onClick={() => onSelectTreat(treat.id)}
              className={`
                group relative p-2 rounded-2xl text-center transition-all duration-200 flex items-center justify-center overflow-visible
                ${
                  isSelected
                    ? 'bg-gradient-to-b from-pink-50 via-white to-yellow-50 border-2 border-[#ff2975] shadow-[0_6px_16px_-4px_rgba(255,41,117,0.3)]'
                    : 'bg-white border border-slate-200/80 hover:border-pink-200 shadow-sm opacity-90 hover:opacity-100'
                }
              `}
            >
              {/* Product Image Only */}
              <div className="relative w-full h-16 sm:h-18 flex items-center justify-center p-0.5">
                <img
                  src={imageSrc}
                  alt={treat.name}
                  className="max-h-full max-w-full object-contain filter drop-shadow-[0_4px_8px_rgba(0,0,0,0.12)] group-hover:scale-110 transition-transform duration-300"
                />
              </div>

              {/* Active Selection Checkmark Badge */}
              {isSelected && (
                <div className="absolute top-2 right-2 w-5 h-5 rounded-full bg-[#ff2975] text-white text-[10px] font-black flex items-center justify-center shadow-md">
                  ✓
                </div>
              )}
            </button>
          );
        })}
      </div>
    </div>
  );
};
