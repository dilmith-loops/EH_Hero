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
    <div className="space-y-3">
      {/* Main Image Container: Fits entire character inside frame without clipping */}
      <div className="relative aspect-square w-full rounded-3xl overflow-hidden select-none border border-slate-200/80 bg-slate-950 shadow-lg flex items-center justify-center">
        {/* Layer 1: Blurred background fill */}
        <img
          src={showOriginal ? originalUrl : animeUrl}
          alt=""
          className="absolute inset-0 w-full h-full object-cover filter blur-xl opacity-60 scale-110 pointer-events-none"
        />

        {/* Layer 2: Main Image - Fitted completely inside frame without any head or side cropping */}
        <img
          src={showOriginal ? originalUrl : animeUrl}
          alt={showOriginal ? 'Original photo' : 'Wonder Anime Hero'}
          className="relative z-10 w-full h-full object-contain filter drop-shadow-md"
        />

        {/* View Original Image Toggle Button */}
        <button
          type="button"
          onMouseDown={() => setShowOriginal(true)}
          onMouseUp={() => setShowOriginal(false)}
          onMouseLeave={() => setShowOriginal(false)}
          onTouchStart={() => setShowOriginal(true)}
          onTouchEnd={() => setShowOriginal(false)}
          className="absolute top-3 right-3 px-3.5 py-2 rounded-full bg-slate-900/85 hover:bg-slate-900 text-white font-extrabold text-xs backdrop-blur-md border border-white/20 shadow-md active:scale-95 transition-all flex items-center gap-1.5 z-20"
        >
          <span>{showOriginal ? '📸 ORIGINAL' : '👁️ HOLD TO SEE ORIGINAL'}</span>
        </button>
      </div>

      {/* Action Buttons Bar */}
      <div className="space-y-2 pt-1">
        {onDownload && (
          <button
            type="button"
            onClick={onDownload}
            className="w-full py-3.5 px-4 rounded-2xl bg-[#ffea00] hover:bg-[#ffe600] text-slate-900 font-black text-sm uppercase tracking-wider shadow-[0_10px_20px_-5px_rgba(255,234,0,0.4)] active:scale-[0.99] transition-all flex items-center justify-center gap-2"
          >
            <span>SAVE TO GALLERY 💾</span>
          </button>
        )}

        <div className="grid grid-cols-2 gap-2">
          {onShare && (
            <button
              type="button"
              onClick={onShare}
              className="py-3 px-3 rounded-2xl bg-[#ff2975] hover:bg-[#e62065] text-white font-extrabold text-xs shadow-sm active:scale-[0.99] transition-all flex items-center justify-center gap-1.5"
            >
              <span>SHARE 📤</span>
            </button>
          )}

          {onRetake && (
            <button
              type="button"
              onClick={onRetake}
              className="py-3 px-3 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-extrabold text-xs border border-slate-200 shadow-sm active:scale-[0.99] transition-all flex items-center justify-center gap-1.5"
            >
              <span>RETAKE PHOTO 📸</span>
            </button>
          )}
        </div>
      </div>
    </div>
  );
};
