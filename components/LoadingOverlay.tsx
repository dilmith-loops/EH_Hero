import React from 'react';
import { AnimeGenerationPhase } from '../types';

interface LoadingOverlayProps {
  phase: AnimeGenerationPhase;
  styleName?: string;
  treatName?: string;
}

export const LoadingOverlay: React.FC<LoadingOverlayProps> = ({ phase }) => {
  const baseUrl = import.meta.env.BASE_URL || '/';

  const phasesOrder: AnimeGenerationPhase[] = ['analyzing', 'sketching', 'inking', 'coloring'];
  const currentIdx = phasesOrder.indexOf(phase);
  const progressPercent = Math.min(100, Math.max(25, ((currentIdx + 1) / phasesOrder.length) * 100));

  return (
    <div className="fixed inset-0 z-50 bg-[#faf9fd]/95 backdrop-blur-2xl flex flex-col items-center justify-center p-6 text-center select-none animate-fade-in">
      <div className="w-full max-w-sm flex flex-col items-center space-y-6">
        {/* Brand Logos */}
        <div className="relative p-3 rounded-3xl bg-white shadow-xl border border-pink-100 flex items-center justify-center gap-3">
          <img
            src={`${baseUrl}eh-logo.png`}
            alt="Elephant House"
            className="h-12 object-contain filter drop-shadow-sm"
          />
          <span className="text-slate-300 font-bold text-base">✕</span>
          <img
            src={`${baseUrl}wonder.png`}
            alt="Wonder"
            className="h-12 object-contain filter drop-shadow-[0_6px_16px_rgba(255,41,117,0.3)] animate-wobble"
          />
        </div>

        {/* Minimal Smooth Progressive Progress Bar */}
        <div className="w-full space-y-2">
          <div className="w-full h-3 bg-slate-200/80 rounded-full overflow-hidden p-0.5 shadow-inner">
            <div
              className="h-full rounded-full bg-gradient-to-r from-[#ff2975] via-[#ff0055] to-[#ffea00] transition-all duration-500 ease-out shadow-[0_0_12px_rgba(255,41,117,0.5)]"
              style={{ width: `${progressPercent}%` }}
            />
          </div>
        </div>
      </div>
    </div>
  );
};
