import React from 'react';

interface NotFoundPageProps {
  onGoHome: () => void;
}

export const NotFoundPage: React.FC<NotFoundPageProps> = ({ onGoHome }) => {
  const baseUrl = import.meta.env.BASE_URL || '/';

  return (
    <div className="min-h-[100dvh] w-full bg-[#12081d] text-white flex flex-col items-center justify-center p-4 select-none overflow-x-hidden relative">
      {/* Background ambient glowing orbs */}
      <div className="absolute top-1/4 left-1/2 -translate-x-1/2 w-80 h-80 bg-[#ff2975]/20 rounded-full blur-3xl pointer-events-none" />
      <div className="absolute bottom-1/4 left-1/3 w-64 h-64 bg-[#8c1d6b]/30 rounded-full blur-3xl pointer-events-none" />

      {/* Main Card */}
      <div className="relative z-10 w-full max-w-md bg-white/10 backdrop-blur-2xl border border-white/15 rounded-3xl p-6 sm:p-8 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.5)] text-center animate-fade-in flex flex-col items-center">
        {/* Brand Header */}
        <div className="flex items-center justify-center gap-3 mb-6 pb-4 border-b border-white/10 w-full">
          <img src={`${baseUrl}eh-logo.png`} alt="Elephant House" className="h-8 sm:h-9 object-contain filter drop-shadow-sm brightness-110" />
          <span className="text-pink-300 font-bold text-xs">✕</span>
          <img src={`${baseUrl}wonder.png`} alt="Wonder" className="h-8 sm:h-9 object-contain filter drop-shadow-[0_4px_12px_rgba(255,41,117,0.4)]" />
        </div>

        {/* 404 Ice Cream Animation */}
        <div className="relative w-24 h-24 mb-4 flex items-center justify-center">
          <div className="absolute inset-0 bg-[#ff2975]/30 rounded-full blur-xl animate-pulse" />
          <span className="text-6xl relative select-none animate-bounce">🍦</span>
        </div>

        <span className="px-3 py-1 rounded-full bg-[#ff2975]/20 border border-[#ff2975]/40 text-[#ff75aa] font-extrabold text-[11px] uppercase tracking-widest mb-2">
          Error 404
        </span>

        <h1 className="text-4xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-pink-100 to-[#ff2975] tracking-tight">
          Lost in Wonder?
        </h1>

        <h2 className="text-sm sm:text-base font-extrabold text-white/90 mt-2">
          Flavor Not Found
        </h2>

        <p className="text-xs sm:text-sm text-slate-300 font-medium max-w-xs mx-auto mt-2 leading-relaxed">
          The page or adventure you were looking for seems to have melted away or taken a detour!
        </p>

        {/* Actions */}
        <div className="w-full mt-6 space-y-2.5">
          <button
            type="button"
            onClick={onGoHome}
            className="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-[#ff2975] via-[#a3227d] to-[#8c1d6b] hover:brightness-110 active:scale-[0.98] text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-[#ff2975]/25 transition-all flex items-center justify-center gap-2 cursor-pointer"
          >
            <span>✨ Back to Wonder Hero</span>
          </button>

          <a
            href="http://127.0.0.1:8000/admin"
            className="w-full py-3 px-6 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 active:scale-[0.98] text-white/80 font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2"
          >
            <span>Admin Portal ↗</span>
          </a>
        </div>

        <p className="text-[10px] text-slate-400 mt-6 tracking-wide">
          Elephant House © {new Date().getFullYear()} • Ceylon Cold Stores PLC
        </p>
      </div>
    </div>
  );
};
