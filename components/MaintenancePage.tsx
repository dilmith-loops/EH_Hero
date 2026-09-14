import React, { useState } from 'react';

interface MaintenancePageProps {
  onRefresh?: () => void;
}

export const MaintenancePage: React.FC<MaintenancePageProps> = ({ onRefresh }) => {
  const [checking, setChecking] = useState(false);
  const baseUrl = import.meta.env.BASE_URL || '/';

  const handleRefresh = async () => {
    setChecking(true);
    if (onRefresh) {
      await onRefresh();
    } else {
      setTimeout(() => {
        window.location.reload();
      }, 600);
    }
    setTimeout(() => setChecking(false), 1000);
  };

  return (
    <div className="min-h-[100dvh] w-full bg-[#11091d] text-white flex flex-col items-center justify-center p-4 select-none overflow-x-hidden relative">
      {/* Ambient background glows */}
      <div className="absolute top-1/4 left-1/2 -translate-x-1/2 w-80 h-80 bg-amber-500/15 rounded-full blur-3xl pointer-events-none" />
      <div className="absolute bottom-1/4 left-1/3 w-64 h-64 bg-[#8c1d6b]/25 rounded-full blur-3xl pointer-events-none" />

      {/* Main Card */}
      <div className="relative z-10 w-full max-w-md bg-white/10 backdrop-blur-2xl border border-white/15 rounded-3xl p-6 sm:p-8 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.5)] text-center animate-fade-in flex flex-col items-center">
        {/* Brand Header */}
        <div className="flex items-center justify-center gap-3 mb-5 pb-4 border-b border-white/10 w-full">
          <img src={`${baseUrl}eh-logo.png`} alt="Elephant House" className="h-8 sm:h-9 object-contain filter drop-shadow-sm brightness-110" />
          <span className="text-pink-300 font-bold text-xs">✕</span>
          <img src={`${baseUrl}wonder.png`} alt="Wonder" className="h-8 sm:h-9 object-contain filter drop-shadow-[0_4px_12px_rgba(255,41,117,0.4)]" />
        </div>

        {/* Maintenance Badge */}
        <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-300 font-extrabold text-[11px] uppercase tracking-wider mb-4">
          <span className="w-2 h-2 rounded-full bg-amber-400 animate-ping" />
          <span>Under Scheduled Maintenance</span>
        </div>

        {/* Ice Cream / Tool Visual */}
        <div className="relative w-24 h-24 mb-4 flex items-center justify-center">
          <div className="absolute inset-0 bg-amber-500/20 rounded-full blur-xl animate-pulse" />
          <span className="text-6xl relative select-none animate-bounce">🍨</span>
        </div>

        <h1 className="text-3xl sm:text-4xl font-black text-white tracking-tight">
          We're Chilling!
        </h1>

        <h2 className="text-xs sm:text-sm font-extrabold text-[#ff75aa] uppercase tracking-widest mt-1">
          Upgrading Wonder Hero
        </h2>

        <p className="text-xs sm:text-sm text-slate-300 font-medium max-w-xs mx-auto mt-3 leading-relaxed">
          Our AI engines and servers are getting a quick tune-up to serve up even cooler anime transformations. We will be back online shortly!
        </p>

        {/* Actions */}
        <div className="w-full mt-6 space-y-2.5">
          <button
            type="button"
            onClick={handleRefresh}
            disabled={checking}
            className="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-amber-500 via-orange-500 to-[#ff2975] hover:brightness-110 active:scale-[0.98] text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-70"
          >
            <span>{checking ? 'Checking Status... ⏳' : '🔄 Refresh & Try Again'}</span>
          </button>

          <a
            href="http://127.0.0.1:8000/admin"
            className="w-full py-3 px-6 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 active:scale-[0.98] text-white/80 font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2"
          >
            <span>Admin Sign In ↗</span>
          </a>
        </div>

        <p className="text-[10px] text-slate-400 mt-6 tracking-wide">
          Elephant House © {new Date().getFullYear()} • Ceylon Cold Stores PLC
        </p>
      </div>
    </div>
  );
};
