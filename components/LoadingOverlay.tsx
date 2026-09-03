import React from 'react';
import { AnimeGenerationPhase } from '../types';

interface LoadingOverlayProps {
  phase: AnimeGenerationPhase;
  styleName?: string;
  treatName?: string;
}

export const LoadingOverlay: React.FC<LoadingOverlayProps> = ({ phase }) => {
  const baseUrl = import.meta.env.BASE_URL || '/';
  const [displayPercent, setDisplayPercent] = React.useState(15);

  const phasesOrder: AnimeGenerationPhase[] = ['analyzing', 'sketching', 'inking', 'coloring'];
  const currentIdx = phasesOrder.indexOf(phase);
  const targetPercent = Math.min(92, Math.max(25, Math.round(((currentIdx + 1) / phasesOrder.length) * 90)));

  // Smooth live percentage timer
  React.useEffect(() => {
    const interval = setInterval(() => {
      setDisplayPercent((prev) => {
        if (prev < targetPercent) return prev + 1;
        if (prev < 92) return prev + 1;
        return prev;
      });
    }, 180);

    return () => clearInterval(interval);
  }, [targetPercent]);

  return (
    <div className="fixed inset-0 z-50 bg-gradient-to-b from-[#fdeee9] via-[#fceae5] to-[#fcd9ce]/95 backdrop-blur-2xl flex flex-col items-center justify-center p-6 text-center select-none animate-fade-in">
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

        {/* Live Animated Progress Percentage Counter */}
        <div className="space-y-2 w-full">
          <div className="text-4xl font-black tracking-tight text-slate-900 font-playful">
            {displayPercent}%
          </div>

          {/* Smooth Progressive Progress Bar */}
          <div className="w-full h-3.5 bg-white/80 rounded-full overflow-hidden p-0.5 shadow-inner border border-orange-100">
            <div
              className="h-full rounded-full bg-gradient-to-r from-[#e65c40] via-[#ff7854] to-[#ffea00] transition-all duration-300 ease-out shadow-[0_0_12px_rgba(230,92,64,0.5)]"
              style={{ width: `${displayPercent}%` }}
            />
          </div>
        </div>
      </div>
    </div>
  );
};
