import React from 'react';
import { AnimeGenerationPhase } from '../types';

interface LoadingOverlayProps {
  phase: AnimeGenerationPhase;
  styleName?: string;
  treatName?: string;
}

export const LoadingOverlay: React.FC<LoadingOverlayProps> = () => {
  const baseUrl = import.meta.env.BASE_URL || '/';

  return (
    <div className="fixed inset-0 z-50 bg-white/95 backdrop-blur-2xl flex flex-col items-center justify-center p-6 text-center select-none animate-fade-in">
      <div className="w-full max-w-sm flex flex-col items-center space-y-7">
        {/* Scaled Up & Centered Brand Logos */}
        <div className="relative p-4 px-6 rounded-3xl bg-white/90 shadow-2xl border border-orange-100/80 flex items-center justify-center gap-4">
          <img
            src={`${baseUrl}eh-logo.png`}
            alt="Elephant House"
            className="h-14 sm:h-16 object-contain filter drop-shadow-md"
          />
          <span className="text-orange-300 font-black text-xl">✕</span>
          <img
            src={`${baseUrl}wonder.png`}
            alt="Wonder"
            className="h-14 sm:h-16 object-contain filter drop-shadow-[0_6px_16px_rgba(230,92,64,0.35)] animate-wobble"
          />
        </div>

        {/* Animated Progress Circle */}
        <div className="relative w-20 h-20 flex items-center justify-center">
          <svg className="w-full h-full animate-spin" viewBox="0 0 50 50">
            <defs>
              <linearGradient id="spinner-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stopColor="#e65c40" />
                <stop offset="50%" stopColor="#ff7854" />
                <stop offset="100%" stopColor="#ffea00" />
              </linearGradient>
            </defs>
            <circle
              cx="25"
              cy="25"
              r="20"
              fill="none"
              stroke="#fceae5"
              strokeWidth="4.5"
            />
            <circle
              cx="25"
              cy="25"
              r="20"
              fill="none"
              stroke="url(#spinner-gradient)"
              strokeWidth="4.5"
              strokeDasharray="85 120"
              strokeLinecap="round"
            />
          </svg>
        </div>

        {/* Status Text */}
        <p className="text-lg font-bold text-slate-800 tracking-tight font-playful animate-pulse">
          Your image is generating, please wait...
        </p>
      </div>
    </div>
  );
};
