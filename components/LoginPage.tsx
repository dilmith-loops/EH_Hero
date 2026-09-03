import React, { useState } from 'react';

interface UserInfo {
  name: string;
  phone: string;
}

interface LoginPageProps {
  onLogin: (info: UserInfo) => void;
}

export const LoginPage: React.FC<LoginPageProps> = ({ onLogin }) => {
  const [name, setName] = useState('');
  const [phone, setPhone] = useState('');
  const [error, setError] = useState('');
  const baseUrl = import.meta.env.BASE_URL || '/';

  const handleSubmit = (e: React.FormEvent) => {
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
    onLogin({ name: name.trim(), phone: phone.trim() });
  };

  return (
    <div className="relative min-h-screen w-full flex flex-col justify-between items-center p-4 sm:p-6 overflow-hidden animate-fade-in">
      {/* Background Poster Image - Fitted so characters fit inside frame perfectly */}
      <img
        src={`${baseUrl}Login.jpeg`}
        alt="Wonder Anime Hero"
        className="absolute inset-0 w-full h-full object-contain sm:object-cover object-center pointer-events-none"
      />

      {/* Top Brand Logos Header - Scaled up on both sides */}
      <div className="relative z-10 w-full pt-3 px-3 flex flex-col items-center space-y-2">
        {/* Logos on both sides */}
        <div className="w-full flex items-center justify-between">
          <img
            src={`${baseUrl}eh-logo.png`}
            alt="Elephant House"
            className="h-14 sm:h-16 object-contain filter drop-shadow-md"
          />
          <img
            src={`${baseUrl}wonder.png`}
            alt="Wonder"
            className="h-14 sm:h-16 object-contain filter drop-shadow-[0_4px_12px_rgba(255,41,117,0.3)]"
          />
        </div>

        {/* Playful Toon Title positioned higher up */}
        <div className="text-center pt-6 sm:pt-10 animate-wobble">
          <h1 className="text-3xl sm:text-5xl font-black tracking-wider uppercase font-playful text-white drop-shadow-[0_6px_12px_rgba(180,30,133,0.9)] [text-shadow:_3px_3px_0_#b41e85,_-3px_-3px_0_#b41e85,_3px_-3px_0_#b41e85,_-3px_3px_0_#b41e85,_5px_5px_0_#000]">
            SNAP & <span className="text-[#ffea00] [text-shadow:_3px_3px_0_#000,_-3px_-3px_0_#000,_3px_-3px_0_#000,_-3px_3px_0_#000,_5px_5px_0_#b41e85]">WONDER</span> 📸
          </h1>
          <p className="text-xs sm:text-base font-playful font-black tracking-widest uppercase text-yellow-300 drop-shadow-[0_2px_6px_rgba(0,0,0,0.9)] mt-1">
            Selfie to Anime Hero 🍦
          </p>
        </div>
      </div>

      {/* Bottom Login Form Card */}
      <div className="relative z-10 w-full max-w-sm bg-white/50 backdrop-blur-xl rounded-3xl p-6 shadow-[0_20px_40px_-15px_rgba(180,30,133,0.3)] border border-white/70 space-y-4 text-center mb-4">
        <form onSubmit={handleSubmit} className="space-y-3 text-left">
          <div>
            <input
              type="text"
              value={name}
              onChange={(e) => setName(e.target.value)}
              placeholder="First Name"
              className="w-full px-4 py-3.5 rounded-2xl bg-white/80 backdrop-blur-md text-slate-900 font-bold text-sm placeholder:text-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#b41e85] transition-all border border-white/60 shadow-sm"
            />
          </div>

          <div>
            <input
              type="tel"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
              placeholder="Mobile Number"
              className="w-full px-4 py-3.5 rounded-2xl bg-white/80 backdrop-blur-md text-slate-900 font-bold text-sm placeholder:text-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#b41e85] transition-all border border-white/60 shadow-sm"
            />
          </div>

          {error && (
            <p className="text-rose-600 text-xs font-bold px-1 drop-shadow-sm">
              ⚠️ {error}
            </p>
          )}

          <button
            type="submit"
            className="w-full py-4 rounded-2xl bg-[#b41e85] hover:bg-[#9d1773] text-white font-black text-sm uppercase tracking-wider shadow-[0_10px_25px_-5px_rgba(180,30,133,0.5)] hover:shadow-[0_15px_30px_-5px_rgba(180,30,133,0.6)] active:scale-[0.99] transition-all flex items-center justify-center mt-1"
          >
            <span>GET STARTED</span>
          </button>
        </form>
      </div>
    </div>
  );
};
