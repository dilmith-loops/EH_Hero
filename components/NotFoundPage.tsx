import React from 'react';

interface NotFoundPageProps {
  onGoHome: () => void;
}

export const NotFoundPage: React.FC<NotFoundPageProps> = ({ onGoHome }) => {
  const baseUrl = import.meta.env.BASE_URL || '/';

  return (
    <div className="min-h-[100dvh] w-full bg-gradient-to-b from-[#1a0524] via-[#0e0214] to-[#08010b] text-white flex flex-col items-center justify-center p-4 sm:p-6 select-none overflow-x-hidden relative">
      {/* Background ambient glowing orbs */}
      <div className="absolute top-1/6 left-1/2 -translate-x-1/2 w-96 h-96 bg-[#ff2975]/20 rounded-full blur-[100px] pointer-events-none" />
      <div className="absolute bottom-1/6 left-1/3 w-80 h-80 bg-[#8c1d6b]/25 rounded-full blur-[100px] pointer-events-none" />

      {/* Decorative floating sparkles */}
      <span className="absolute top-12 left-10 text-xl opacity-30 select-none animate-pulse">✨</span>
      <span className="absolute top-24 right-12 text-lg opacity-40 select-none animate-bounce" style={{ animationDuration: '4s' }}>🍦</span>
      <span className="absolute bottom-20 left-16 text-lg opacity-25 select-none animate-pulse" style={{ animationDuration: '3s' }}>✨</span>

      {/* Main Card */}
      <div className="relative z-10 w-full max-w-md bg-white/[0.08] backdrop-blur-3xl border border-white/20 rounded-[2.5rem] p-7 sm:p-8 shadow-[0_25px_70px_-15px_rgba(0,0,0,0.7)] text-center animate-fade-in flex flex-col items-center overflow-hidden">
        {/* Top subtle rim highlight */}
        <div className="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-[#ff2975]/60 to-transparent" />

        {/* Brand Header */}
        <div className="flex items-center justify-center gap-3.5 mb-5 pb-4 border-b border-white/10 w-full">
          <img
            src={`${baseUrl}eh-logo.png`}
            alt="Elephant House"
            className="h-8 sm:h-9 object-contain filter drop-shadow-[0_2px_8px_rgba(255,255,255,0.2)] brightness-110"
          />
          <span className="text-pink-300/80 font-bold text-xs">✕</span>
          <img
            src={`${baseUrl}wonder.png`}
            alt="Wonder"
            className="h-8 sm:h-9 object-contain filter drop-shadow-[0_4px_16px_rgba(255,41,117,0.5)]"
          />
        </div>

        {/* 404 Visual Icon */}
        <div className="relative w-32 h-32 mb-4 flex items-center justify-center">
          <div className="absolute inset-0 bg-gradient-to-tr from-[#ff2975]/35 via-purple-500/20 to-[#ffea00]/25 rounded-full blur-2xl animate-pulse" />
          <div className="absolute inset-2 bg-white/5 border border-white/20 rounded-full backdrop-blur-md" />

          {/* Strawberry Wonder Treat */}
          <img
            src={`${baseUrl}strawberry.png`}
            alt="Elephant House Wonder Treat"
            className="w-22 h-22 object-contain relative z-10 filter drop-shadow-[0_10px_20px_rgba(255,41,117,0.45)] select-none animate-bounce"
            style={{ animationDuration: '3.5s' }}
          />

          <div className="absolute -top-1 -right-1 z-20 bg-pink-500/30 border border-pink-300/40 rounded-full w-7 h-7 flex items-center justify-center text-xs shadow-md">
            ❓
          </div>
        </div>

        <span className="px-4 py-1.5 rounded-full bg-[#ff2975]/20 border border-[#ff2975]/40 text-[#ff75aa] font-extrabold text-[10px] sm:text-[11px] uppercase tracking-widest mb-3">
          Error 404 • Page Not Found
        </span>

        <h1 className="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-pink-100 to-[#ff75aa] tracking-tight">
          Flavor Not Found!
        </h1>

        <h2 className="text-xs sm:text-sm font-extrabold text-[#ffea00] uppercase tracking-wider mt-1.5">
          Lost in the Wonder Realm
        </h2>

        <p className="text-xs sm:text-sm text-slate-300 font-medium max-w-xs mx-auto mt-3 leading-relaxed">
          The page or adventure you were looking for seems to have melted away or taken a detour!
        </p>

        {/* Actions - Only public button */}
        <div className="w-full mt-6 space-y-3">
          <button
            type="button"
            onClick={onGoHome}
            className="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-[#ff2975] via-[#a3227d] to-[#78165b] hover:brightness-110 active:scale-[0.98] text-white font-black text-xs uppercase tracking-widest shadow-[0_10px_25px_-5px_rgba(255,41,117,0.45)] transition-all flex items-center justify-center gap-2 cursor-pointer"
          >
            <span>✨ Back to Wonder Hero</span>
          </button>
        </div>

        <p className="text-[10px] text-slate-400 mt-6 tracking-wide">
          Elephant House © {new Date().getFullYear()} • Ceylon Cold Stores PLC
        </p>
      </div>
    </div>
  );
};
