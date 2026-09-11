import React, { useState } from 'react';

interface BeforeAfterSliderProps {
  originalUrl: string;
  animeUrl: string;
  styleName?: string;
  styleEmoji?: string;
  treatName?: string;
  treatEmoji?: string;
  vibeTag?: string;
  onExpand?: () => void;
  onDownload?: () => void;
  onReset?: () => void;
  onShare?: () => void;
  onRetake?: () => void;
}

export const BeforeAfterSlider: React.FC<BeforeAfterSliderProps> = ({
  originalUrl,
  animeUrl,
  onDownload,
  onReset,
  onShare,
  onRetake,
}) => {
  const [showOriginal, setShowOriginal] = useState(false);

  return (
    <div className="flex-1 min-h-0 h-full flex flex-col justify-between space-y-2 overflow-hidden">
      {/* Main Image View */}
      <div className="relative flex-1 min-h-0 w-full select-none flex items-center justify-center overflow-hidden py-1">
        <img
          src={animeUrl}
          alt="Wonder Anime Hero"
          className="max-h-full max-w-full object-contain rounded-2xl shadow-lg border border-pink-100/50"
        />
      </div>

      {/* Action Buttons Bar */}
      <div className="shrink-0 space-y-2 pt-1">
        {onDownload && (
          <button
            type="button"
            onClick={onDownload}
            className="w-full py-3 px-4 rounded-2xl bg-[#ffea00] hover:bg-[#ffe600] text-slate-900 font-black text-xs sm:text-sm uppercase tracking-wider shadow-md active:scale-[0.99] transition-all flex items-center justify-center gap-2 cursor-pointer"
          >
            <span>SAVE TO GALLERY</span>
          </button>
        )}

        <div className="grid grid-cols-2 gap-2">
          {onShare && (
            <button
              type="button"
              onClick={onShare}
              className="py-2.5 px-3 rounded-2xl bg-[#ff2975] hover:bg-[#e62065] text-white font-extrabold text-xs shadow-sm active:scale-[0.99] transition-all flex items-center justify-center gap-1.5 cursor-pointer"
            >
              <span>SHARE</span>
            </button>
          )}

          {onRetake && (
            <button
              type="button"
              onClick={onRetake}
              className="py-2.5 px-3 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-extrabold text-xs border border-slate-200 shadow-sm active:scale-[0.99] transition-all flex items-center justify-center gap-1.5 cursor-pointer"
            >
              <span>RETAKE PHOTO</span>
            </button>
          )}
        </div>
      </div>
    </div>
  );
};
