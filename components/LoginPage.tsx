import React, { useState } from 'react';
import { registerParticipant, UserInfo } from '../services/apiService';

interface LoginPageProps {
  onLogin: (info: UserInfo) => void;
}

export const LoginPage: React.FC<LoginPageProps> = ({ onLogin }) => {
  const [name, setName] = useState('');
  const [phone, setPhone] = useState('');
  const [error, setError] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const baseUrl = import.meta.env.BASE_URL || '/';

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!name.trim()) {
      setError('Enter your name ✨');
      return;
    }
    if (!phone.trim() || phone.trim().length < 8) {
      setError('Enter a valid mobile number 📱');
      return;
    }
    setError('');
    setIsSubmitting(true);
    try {
      const user = await registerParticipant(name.trim(), phone.trim());
      onLogin(user);
    } catch (err) {
      console.warn('Registration failed, continuing:', err);
      onLogin({ name: name.trim(), phone: phone.trim() });
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
        {/* Input Card at Bottom of Mobile View (Solid White Card) */}
        <div className="relative z-10 w-full max-w-[300px] mx-auto bg-white rounded-2xl p-3.5 sm:p-4 shadow-[0_15px_35px_rgba(0,0,0,0.25)] border border-pink-100 text-center mb-3 sm:mb-2">
          <form onSubmit={handleSubmit} className="space-y-2.5 text-left">
            <div>
              <input
                type="text"
                value={name}
                onChange={(e) => setName(e.target.value)}
                placeholder="First Name"
                className="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 text-slate-900 font-bold text-xs placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#8c1d6b] transition-all border border-slate-200 shadow-sm"
              />
            </div>

            <div>
              <input
                type="tel"
                value={phone}
                onChange={(e) => setPhone(e.target.value)}
                placeholder="Mobile Number"
                className="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 text-slate-900 font-bold text-xs placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#8c1d6b] transition-all border border-slate-200 shadow-sm"
              />
            </div>

            {error && (
              <p className="text-rose-600 text-[11px] font-bold px-1 text-center">
                ⚠️ {error}
              </p>
            )}

            {/* Purple GET STARTED Button */}
            <button
              type="submit"
              disabled={isSubmitting}
              className="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#8c1d6b] via-[#a3227d] to-[#78165b] hover:brightness-105 disabled:opacity-70 text-white font-black text-xs uppercase tracking-wider shadow-md active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 mt-0.5 cursor-pointer"
            >
              <span>{isSubmitting ? 'CONNECTING...' : 'GET STARTED'}</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};
