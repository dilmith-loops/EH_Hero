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
    setTimeout(() => setChecking(false), 1200);
  };

  return (
    <div className="min-h-[100dvh] w-full bg-gradient-to-b from-[#1a0524] via-[#0e0214] to-[#08010b] text-white flex flex-col items-center justify-center p-4 sm:p-6 select-none overflow-x-hidden relative">
      {/* Background ambient glowing radial effects */}
      <div className="absolute top-1/6 left-1/2 -translate-x-1/2 w-96 h-96 bg-[#ff2975]/20 rounded-full blur-[100px] pointer-events-none" />
      <div className="absolute bottom-1/6 left-1/4 w-80 h-80 bg-[#38bdf8]/15 rounded-full blur-[100px] pointer-events-none" />
      <div className="absolute top-1/2 right-1/4 w-72 h-72 bg-[#a3227d]/20 rounded-full blur-[90px] pointer-events-none" />

      {/* Decorative floating ice crystals & lightning */}
      <span className="absolute top-12 left-10 text-xl opacity-30 select-none animate-pulse">❄️</span>
      <span className="absolute bottom-20 left-16 text-lg opacity-25 select-none animate-pulse" style={{ animationDuration: '3s' }}>⚡</span>
      <span className="absolute bottom-28 right-14 text-xl opacity-35 select-none animate-bounce" style={{ animationDuration: '5s' }}>❄️</span>

      {/* Main Redesigned Card */}
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

        {/* Status Badge */}
        <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/15 border border-sky-400/30 text-sky-300 font-extrabold text-[10px] sm:text-[11px] uppercase tracking-widest mb-5 shadow-sm">
          <span className="relative flex h-2 w-2">
            <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75" />
            <span className="relative inline-flex rounded-full h-2 w-2 bg-sky-400" />
          </span>
          <span>System Cool-Down & Tuning</span>
        </div>

        {/* Hero Visual: Authentic Elephant House Wonder Treat */}
        <div className="relative w-32 h-32 mb-4 flex items-center justify-center">
          {/* Frosty halo glow */}
          <div className="absolute inset-0 bg-gradient-to-tr from-[#ff2975]/35 via-sky-400/25 to-[#ffea00]/25 rounded-full blur-2xl animate-pulse" />
          <div className="absolute inset-2 bg-white/5 border border-white/20 rounded-full backdrop-blur-md" />

          {/* Floating Pinky Bear Treat */}
          <img
            src={`${baseUrl}pinkybear.png`}
            alt="Elephant House Wonder Treat"
            className="w-24 h-24 object-contain relative z-10 filter drop-shadow-[0_10px_20px_rgba(255,41,117,0.45)] select-none animate-bounce"
            style={{ animationDuration: '3.5s' }}
          />
        </div>

        {/* Main Headline */}
        <h1 className="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-pink-100 to-[#ff75aa] tracking-tight">
          We're Chilling!
        </h1>

        <h2 className="text-xs sm:text-sm font-extrabold text-[#ffea00] uppercase tracking-wider mt-1.5 flex items-center gap-1.5">
          <span>⚡</span>
          <span>Upgrading Wonder Hero AI</span>
          <span>⚡</span>
        </h2>

        <p className="text-xs sm:text-sm text-slate-300 font-medium max-w-xs mx-auto mt-3 leading-relaxed">
          Our servers are taking a frosty breather to serve up faster, sharper, and even more magical anime transformations. We'll be back online in just a few minutes!
        </p>

        {/* Live System Gauge Shimmer */}
        <div className="w-full bg-white/[0.06] border border-white/10 rounded-2xl p-3 sm:p-3.5 my-5 text-left shadow-inner">
          <div className="flex items-center justify-between text-[11px] font-bold text-slate-200 mb-2">
            <span className="flex items-center gap-1.5">
              <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" />
              <span>AI Engine Optimization</span>
            </span>
            <span className="text-[#ffea00] font-black tracking-wider">IN PROGRESS</span>
          </div>
          <div className="w-full h-2 bg-white/10 rounded-full overflow-hidden p-0.5">
            <div
              className="h-full bg-gradient-to-r from-sky-400 via-[#ff2975] to-[#ffea00] rounded-full animate-pulse"
              style={{ width: '92%' }}
            />
          </div>
          <div className="flex items-center justify-between text-[10px] text-slate-400 font-medium mt-2">
            <span>Server Health: Optimal</span>
            <span>All Data Saved</span>
          </div>
        </div>

        {/* Action Button - Single, prominent, with no admin button */}
        <div className="w-full space-y-3">
          <button
            type="button"
            onClick={handleRefresh}
            disabled={checking}
            className="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-[#ff2975] via-[#a3227d] to-[#78165b] hover:brightness-110 active:scale-[0.98] text-white font-black text-xs uppercase tracking-widest shadow-[0_10px_25px_-5px_rgba(255,41,117,0.45)] transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75"
          >
            {checking ? (
              <>
                <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                <span>Checking System Status...</span>
              </>
            ) : (
              <>
                <span className="text-base">🔄</span>
                <span>Refresh & Try Again</span>
              </>
            )}
          </button>

          <p className="text-[11px] text-pink-200/60 font-medium text-center">
            Automatically reconnecting when servers are ready
          </p>
        </div>

        {/* Footer */}
        <p className="text-[10px] text-slate-400 mt-6 tracking-wide">
          Elephant House © {new Date().getFullYear()} • Ceylon Cold Stores PLC
        </p>
      </div>
    </div>
  );
};
