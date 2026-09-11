import React, { useRef, useState, useEffect, useCallback } from 'react';

interface CameraCaptureProps {
  onCapture: (file: File, previewUrl: string, base64: string) => void;
  onClose: () => void;
}

export const CameraCapture: React.FC<CameraCaptureProps> = ({ onCapture, onClose }) => {
  const videoRef = useRef<HTMLVideoElement>(null);
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const streamRef = useRef<MediaStream | null>(null);

  const [facingMode, setFacingMode] = useState<'user' | 'environment'>('user');
  const [hasMultipleCameras, setHasMultipleCameras] = useState(false);
  const [capturedPreview, setCapturedPreview] = useState<string | null>(null);
  const [capturedBlob, setCapturedBlob] = useState<Blob | null>(null);
  const [capturedBase64, setCapturedBase64] = useState<string | null>(null);
  const [countdown, setCountdown] = useState<number | null>(null);
  const [useCountdown, setUseCountdown] = useState(false);
  const [cameraError, setCameraError] = useState<string | null>(null);
  const [isFlashActive, setIsFlashActive] = useState(false);
  const [recSeconds, setRecSeconds] = useState(1);

  // Timer counter for REC display
  useEffect(() => {
    const t = setInterval(() => setRecSeconds((s) => s + 1), 1000);
    return () => clearInterval(t);
  }, []);

  const playShutterSound = () => {
    try {
      const audioCtx = new (window.AudioContext || (window as any).webkitAudioContext)();
      const osc = audioCtx.createOscillator();
      const gain = audioCtx.createGain();
      osc.type = 'sawtooth';
      osc.frequency.setValueAtTime(880, audioCtx.currentTime);
      osc.frequency.exponentialRampToValueAtTime(220, audioCtx.currentTime + 0.1);
      gain.gain.setValueAtTime(0.4, audioCtx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1);
      osc.connect(gain);
      gain.connect(audioCtx.destination);
      osc.start();
      osc.stop(audioCtx.currentTime + 0.1);
    } catch {}
  };

  useEffect(() => {
    if (navigator.mediaDevices?.enumerateDevices) {
      navigator.mediaDevices.enumerateDevices().then((devices) => {
        const videoInputs = devices.filter((d) => d.kind === 'videoinput');
        setHasMultipleCameras(videoInputs.length > 1);
      });
    }
  }, []);

  const startCamera = useCallback(async () => {
    setCameraError(null);
    if (streamRef.current) {
      streamRef.current.getTracks().forEach((track) => track.stop());
    }

    try {
      const constraints: MediaStreamConstraints = {
        video: {
          facingMode: facingMode,
          width: { ideal: 1080 },
          height: { ideal: 1440 },
        },
        audio: false,
      };

      const stream = await navigator.mediaDevices.getUserMedia(constraints);
      streamRef.current = stream;
      if (videoRef.current) {
        videoRef.current.srcObject = stream;
      }
    } catch (err: any) {
      console.error('Camera error:', err);
      if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
        setCameraError('Camera access blocked. Tap allow in your browser address bar!');
      } else {
        setCameraError('Could not start camera. Make sure no other app is using it!');
      }
    }
  }, [facingMode]);

  useEffect(() => {
    startCamera();
    return () => {
      if (streamRef.current) {
        streamRef.current.getTracks().forEach((track) => track.stop());
      }
    };
  }, [startCamera]);

  const toggleCameraFacing = () => {
    setFacingMode((prev) => (prev === 'user' ? 'environment' : 'user'));
  };

  const performSnap = () => {
    if (!videoRef.current || !canvasRef.current) return;
    const video = videoRef.current;
    const canvas = canvasRef.current;

    const width = video.videoWidth || 720;
    const height = video.videoHeight || 960;

    canvas.width = width;
    canvas.height = height;

    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    if (facingMode === 'user') {
      ctx.translate(width, 0);
      ctx.scale(-1, 1);
    }

    ctx.drawImage(video, 0, 0, width, height);

    setIsFlashActive(true);
    playShutterSound();
    setTimeout(() => setIsFlashActive(false), 200);

    const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
    const base64 = dataUrl.split(',')[1];

    canvas.toBlob((blob) => {
      if (blob) {
        setCapturedBlob(blob);
        setCapturedPreview(dataUrl);
        setCapturedBase64(base64);
      }
    }, 'image/jpeg', 0.95);
  };

  const handleCaptureClick = () => {
    if (useCountdown) {
      setCountdown(3);
      const timer = setInterval(() => {
        setCountdown((prev) => {
          if (prev === null || prev <= 1) {
            clearInterval(timer);
            performSnap();
            return null;
          }
          return prev - 1;
        });
      }, 1000);
    } else {
      performSnap();
    }
  };

  const handleRetake = () => {
    setCapturedPreview(null);
    setCapturedBlob(null);
    setCapturedBase64(null);
    setCountdown(null);
  };

  const handleConfirm = () => {
    if (capturedBlob && capturedPreview && capturedBase64) {
      const file = new File([capturedBlob], `selfie-${Date.now()}.jpg`, {
        type: 'image/jpeg',
      });
      onCapture(file, capturedPreview, capturedBase64);
    }
  };

  const formatRecTime = (s: number) => {
    const mins = Math.floor(s / 60)
      .toString()
      .padStart(2, '0');
    const secs = (s % 60).toString().padStart(2, '0');
    return `00:${mins}:${secs}`;
  };

  return (
    <div className="fixed inset-0 z-50 bg-slate-950 flex flex-col justify-between select-none animate-fade-in">
      {/* Top Header */}
      <div className="p-4 pt-6 flex items-center justify-end z-30 bg-gradient-to-b from-slate-950/90 to-transparent">
        <button
          onClick={onClose}
          className="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-md text-white flex items-center justify-center text-lg active:scale-95 transition-transform"
        >
          ✕
        </button>
      </div>

      {/* Viewfinder Main View */}
      <div className="relative flex-1 bg-slate-950 overflow-hidden flex items-center justify-center">
        {isFlashActive && (
          <div className="absolute inset-0 bg-white z-40 animate-out fade-out duration-200" />
        )}

        {countdown !== null && (
          <div className="absolute inset-0 z-30 bg-slate-950/60 backdrop-blur-sm flex flex-col items-center justify-center">
            <span className="text-8xl font-black font-playful text-[#ffea00] animate-bounce drop-shadow-[0_0_25px_rgba(255,234,0,0.8)]">
              {countdown}
            </span>
            <span className="text-sm font-black font-playful text-white uppercase tracking-widest mt-2 drop-shadow-md">
              STRIKE A POSE! 📸
            </span>
          </div>
        )}

        {cameraError ? (
          <div className="p-6 text-center max-w-xs space-y-4">
            <div className="text-4xl">⚠️</div>
            <p className="text-rose-400 font-bold text-sm">{cameraError}</p>
            <button
              onClick={startCamera}
              className="px-5 py-3 rounded-2xl bg-gradient-to-r from-[#8c1d6b] to-[#ff2975] text-white font-black text-xs uppercase tracking-wider shadow-lg active:scale-95"
            >
              RETRY CAMERA 🔄
            </button>
          </div>
        ) : capturedPreview ? (
          <div className="relative w-full h-full flex items-center justify-center">
            <img
              src={capturedPreview}
              alt="Selfie"
              className="w-full h-full object-cover"
            />
            {/* Brand Theme Badge Overlay */}
            <div className="absolute top-6 left-6 rotate-[-4deg] bg-gradient-to-r from-[#8c1d6b] to-[#ff2975] text-white font-black font-playful text-xs px-4 py-2 rounded-2xl shadow-xl border border-white/30 flex items-center gap-1.5">
              <span>HERO SELFIE</span>
              <span>✨</span>
            </div>
          </div>
        ) : (
          <>
            <video
              ref={videoRef}
              autoPlay
              playsInline
              muted
              className={`w-full h-full object-cover ${
                facingMode === 'user' ? '-scale-x-100' : ''
              }`}
            />

            {/* Wonder Face Framing Bracket */}
            <div className="absolute inset-0 pointer-events-none flex flex-col items-center justify-center">
              <div className="w-64 h-80 border-2 border-dashed border-[#ffea00]/80 rounded-[40px] flex flex-col items-center justify-between p-4 shadow-[0_0_30px_rgba(255,234,0,0.3)]">
                <div className="flex justify-between w-full text-[10px] font-mono text-[#ffea00] font-bold">
                  <span>[HERO-CAM]</span>
                  <span>READY ⚡</span>
                </div>
                <div className="text-center bg-[#8c1d6b]/80 px-4 py-1.5 rounded-full border border-white/40 backdrop-blur-md shadow-lg">
                  <span className="text-xs font-black font-playful text-white tracking-wide">
                    CENTER YOUR SELFIE ✨
                  </span>
                </div>
                <div className="flex justify-between w-full text-[10px] font-mono text-[#ffea00] font-bold">
                  <span>WONDER VIBE</span>
                  <span>100%</span>
                </div>
              </div>
            </div>
          </>
        )}

        <canvas ref={canvasRef} className="hidden" />
      </div>

      {/* Bottom Shutter Controls */}
      <div className="p-6 pb-10 bg-gradient-to-t from-slate-950 via-slate-950/90 to-transparent z-30 flex flex-col gap-4">
        {capturedPreview ? (
          <div className="flex gap-3">
            <button
              onClick={handleRetake}
              className="flex-1 py-4 rounded-2xl bg-white/15 text-white font-black text-sm border border-white/20 hover:bg-white/25 active:scale-95 transition-all flex items-center justify-center gap-2"
            >
              <span>🔄 RETAKE</span>
            </button>
            <button
              onClick={handleConfirm}
              className="flex-1 py-4 rounded-2xl bg-gradient-to-r from-[#8c1d6b] via-[#a3227d] to-[#ff2975] hover:brightness-105 text-white font-black text-sm uppercase tracking-wider shadow-[0_10px_25px_-5px_rgba(140,29,107,0.6)] active:scale-95 transition-all flex items-center justify-center gap-2"
            >
              <span>✨ USE SELFIE</span>
            </button>
          </div>
        ) : (
          <div className="flex items-center justify-around">
            {/* Timer Toggle */}
            <button
              type="button"
              onClick={() => setUseCountdown(!useCountdown)}
              className={`w-12 h-12 rounded-2xl font-bold flex flex-col items-center justify-center transition-all ${
                useCountdown
                  ? 'bg-[#ffea00] text-slate-900 shadow-[0_0_15px_rgba(255,234,0,0.5)] font-black'
                  : 'bg-white/15 text-white hover:bg-white/25 border border-white/20'
              }`}
              title="3-Second Timer"
            >
              <span className="text-xs">⏱️</span>
              <span className="text-[9px] font-mono mt-0.5 font-bold">3s</span>
            </button>

            {/* Giant Brand Shutter Button */}
            <button
              type="button"
              onClick={handleCaptureClick}
              disabled={!!cameraError || countdown !== null}
              className="w-20 h-20 rounded-full border-4 border-[#ffea00] p-1.5 flex items-center justify-center hover:scale-105 active:scale-95 transition-all shadow-[0_0_30px_rgba(255,234,0,0.5)] group"
            >
              <div className="w-full h-full rounded-full bg-gradient-to-tr from-[#8c1d6b] to-[#ff2975] group-hover:brightness-110 transition-colors flex items-center justify-center text-white shadow-inner">
                <span className="text-2xl">📸</span>
              </div>
            </button>

            {/* Flip Camera */}
            {hasMultipleCameras ? (
              <button
                type="button"
                onClick={toggleCameraFacing}
                className="w-12 h-12 rounded-2xl bg-white/15 text-white hover:bg-white/25 border border-white/20 flex flex-col items-center justify-center active:scale-95 transition-all"
                title="Flip Camera"
              >
                <span className="text-xs">🔄</span>
                <span className="text-[9px] font-mono mt-0.5 font-bold">FLIP</span>
              </button>
            ) : (
              <div className="w-12" />
            )}
          </div>
        )}
      </div>
    </div>
  );
};
