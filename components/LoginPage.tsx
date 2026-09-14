import React, { useState, useEffect } from 'react';
import { registerParticipant, checkGenerationLimit, UserInfo } from '../services/apiService';

interface LoginPageProps {
  onLogin: (info: UserInfo) => void;
}

export const LoginPage: React.FC<LoginPageProps> = ({ onLogin }) => {
  const [name, setName] = useState('');
  const [error, setError] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isLimitReached, setIsLimitReached] = useState(false);
  const baseUrl = import.meta.env.BASE_URL || '/';

  // Check generation limit for this device/IP as soon as the start screen loads
  useEffect(() => {
    const verifyDeviceLimit = async () => {
      try {
        const status = await checkGenerationLimit();
        if (status.maintenance) {
          setError(status.message || 'Platform is currently under maintenance.');
          setIsLimitReached(true);
        } else if (!status.can_generate) {
          setError(status.message || 'Too many generations, please try again.');
          setIsLimitReached(true);
        }
      } catch (err) {
        console.warn('Pre-check device limit error:', err);
      }
    };
    verifyDeviceLimit();
  }, []);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    // If device is already at limit, block immediately
    if (isLimitReached) {
      setError(error || 'Too many generations, please try again.');
      return;
    }

    if (!name.trim()) {
      setError('Enter your name ✨');
      return;
    }

    setError('');
    setIsSubmitting(true);

    try {
      // Proactively re-verify device IP limit before advancing to photo screen
      const limitStatus = await checkGenerationLimit();
      if (limitStatus.maintenance) {
        setError(limitStatus.message || 'Platform is currently under maintenance.');
        setIsLimitReached(true);
        setIsSubmitting(false);
        return;
      }

      if (!limitStatus.can_generate) {
        setError(limitStatus.message || 'Too many generations, please try again.');
        setIsLimitReached(true);
        setIsSubmitting(false);
        return;
      }

      // Register participant on backend
      const user = await registerParticipant(name.trim());
      onLogin(user);
    } catch (err: any) {
      if (err.isLimitReached || err.status === 429) {
        setError(err.message || 'Too many generations, please try again.');
        setIsLimitReached(true);
      } else if (err.isMaintenance || err.status === 503) {
        setError(err.message || 'Platform is currently under maintenance.');
        setIsLimitReached(true);
      } else {
        console.warn('Registration network error, continuing in local mode:', err);
        onLogin({ name: name.trim() });
      }
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="min-h-[100dvh] w-full bg-[#faf9fd] flex flex-col items-center justify-center sm:p-4 select-none overflow-x-hidden overflow-y-auto">
      {/* Mobile-first Container (Fills phone screen on mobile, phone-sized frame on desktop) */}
      <div 
        className="relative w-full max-w-md min-h-[100dvh] sm:min-h-[844px] sm:max-h-[900px] sm:rounded-[2.5rem] sm:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.3)] sm:border-4 sm:border-white/80 bg-cover bg-center bg-no-repeat flex flex-col justify-end p-5 sm:p-6 overflow-hidden animate-fade-in"
        style={{ backgroundImage: `url(${baseUrl}Login.jpeg)` }}
      >
        {/* Input Card at Bottom of Mobile View */}
        <div className="relative z-10 w-full max-w-[320px] mx-auto bg-white rounded-2xl p-3.5 sm:p-4 shadow-[0_15px_35px_rgba(0,0,0,0.25)] border border-pink-100 text-center mb-3 sm:mb-2">
          <form onSubmit={handleSubmit} className="space-y-2.5 text-left">
            <div>
              <input
                type="text"
                value={name}
                onChange={(e) => {
                  setName(e.target.value);
                  if (!isLimitReached && error) setError('');
                }}
                disabled={isLimitReached || isSubmitting}
                placeholder="First Name"
                className="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 text-slate-900 font-bold text-xs placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#8c1d6b] transition-all border border-slate-200 shadow-sm disabled:opacity-60"
              />
            </div>

            {/* Error / Limit Warning Banner */}
            {error && (
              <div className="p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-xs font-bold text-center leading-relaxed animate-fade-in shadow-xs">
                ⚠️ {error.startsWith('{') || /error|leaked|api[_\s]?key|denied|status|403|500|exception/i.test(error) ? 'Too many generations, please try again.' : error}
              </div>
            )}

            {/* Action Button */}
            <button
              type="submit"
              disabled={isSubmitting || isLimitReached}
              className={`
                w-full py-2.5 rounded-xl font-black text-xs uppercase tracking-wider shadow-md transition-all flex items-center justify-center gap-1.5 mt-0.5
                ${
                  isLimitReached
                    ? 'bg-slate-200 text-slate-400 cursor-not-allowed'
                    : 'bg-gradient-to-r from-[#8c1d6b] via-[#a3227d] to-[#78165b] hover:brightness-105 active:scale-[0.98] text-white cursor-pointer disabled:opacity-70'
                }
              `}
            >
              <span>
                {isLimitReached
                  ? 'LIMIT REACHED'
                  : isSubmitting
                  ? 'CONNECTING...'
                  : 'GET STARTED'}
              </span>
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};
