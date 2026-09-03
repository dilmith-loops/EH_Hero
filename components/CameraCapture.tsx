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

  // Timer counter for retro REC display
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
    <div className="fixed inset-0 z-50 bg-black flex flex-col justify-between select-none animate-fade-in">
      {/* Top Camcorder Info Header */}
      <div className="p-4 pt-6 flex items-center justify-between z-30 bg-gradient-to-b from-black/80 to-transparent">
        <div className="flex items-center gap-2">
          <span className="w-3 h-3 rounded-full bg-red-500 animate-pulse" />
          <span className="font-mono text-xs font-bold text-red-400 tracking-wider">
            REC ● {formatRecTime(recSeconds)}
          </span>
          <span className="text-[10px] font-bold px-2 py-0.5 rounded bg-yellow-400 text-black font-mono ml-2">
            HD 60FPS
          </span>
        </div>

        <div className="flex items-center gap-3">
          <button
            onClick={onClose}
            className="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-md text-white flex items-center justify-center text-lg active:scale-95 transition-transform"
          >
            ✕
          </button>
        </div>
      </div>

      {/* Viewfinder Main View */}
      <div className="relative flex-1 bg-black overflow-hidden flex items-center justify-center">
        {isFlashActive && (
          <div className="absolute inset-0 bg-white z-40 animate-out fade-out duration-200" />
        )}

        {countdown !== null && (
          <div className="absolute inset-0 z-30 bg-black/50 flex flex-col items-center justify-center">
            <span className="text-8xl font-black text-[#ccff00] animate-bounce drop-shadow-[0_0_20px_#ccff00]">
              {countdown}
            </span>
            <span className="text-sm font-bold text-white uppercase tracking-widest mt-2">
              STRIKE A POSE! 📸
            </span>
          </div>
        )}

        {cameraError ? (
          <div className="p-6 text-center max-w-xs space-y-4">
            <div className="text-4xl">⚠️</div>
            <p className="text-red-400 font-bold text-sm">{cameraError}</p>
            <button
              onClick={startCamera}
              className="px-5 py-2.5 rounded-full bg-[#ccff00] text-black font-extrabold text-xs brutal-shadow-sm active:translate-y-0.5"
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
            {/* Gen Z Sticker overlay */}
            <div className="absolute top-6 left-6 rotate-[-6deg] bg-[#ff2e93] text-white font-black text-xs px-3 py-1.5 rounded-lg brutal-shadow border border-black">
              MAIN CHARACTER ENERGY 🔥
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

            {/* Target Face Brackets & Focus Reticle */}
            <div className="absolute inset-0 pointer-events-none flex flex-col items-center justify-center">
              <div className="w-64 h-80 border-2 border-dashed border-[#ccff00]/60 rounded-[40px] flex flex-col items-center justify-between p-4 shadow-[0_0_25px_rgba(204,255,0,0.2)]">
                <div className="flex justify-between w-full text-[10px] font-mono text-[#ccff00] font-bold">
                  <span>[AF-LOCK]</span>
                  <span>FACE_DETECT: 99%</span>
                </div>
                <div className="text-center bg-black/60 px-3 py-1 rounded-full border border-white/20 backdrop-blur-md">
                  <span className="text-[11px] font-bold text-white tracking-wide">
                    CENTER YOUR VIBE ✨
                  </span>
                </div>
                <div className="flex justify-between w-full text-[10px] font-mono text-[#ccff00] font-bold">
                  <span>ISO 400</span>
                  <span>f/1.8</span>
                </div>
              </div>
            </div>
          </>
        )}

        <canvas ref={canvasRef} className="hidden" />
      </div>

      {/* Bottom Shutter Controls */}
      <div className="p-6 pb-10 bg-gradient-to-t from-black via-black/90 to-transparent z-30 flex flex-col gap-4">
        {capturedPreview ? (
          <div className="flex gap-3">
            <button
              onClick={handleRetake}
              className="flex-1 py-4 rounded-2xl bg-[#1e2030] text-white font-black text-sm border-2 border-white/20 brutal-shadow active:translate-y-0.5 flex items-center justify-center gap-2"
            >
              <span>🔄 RETAKE</span>
            </button>
            <button
              onClick={handleConfirm}
              className="flex-1 py-4 rounded-2xl bg-gradient-to-r from-[#ff2e93] to-[#ccff00] text-black font-black text-sm border-2 border-black brutal-shadow active:translate-y-0.5 flex items-center justify-center gap-2 shadow-[0_0_20px_#ff2e93]"
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
                  ? 'bg-[#ccff00] text-black border-2 border-black brutal-shadow-sm'
                  : 'bg-white/10 text-white hover:bg-white/20'
              }`}
              title="3-Second Timer"
            >
              <i className="fas fa-stopwatch text-sm" />
              <span className="text-[9px] font-mono mt-0.5">3s</span>
            </button>

            {/* Giant Tactile Shutter Button */}
            <button
              type="button"
              onClick={handleCaptureClick}
              disabled={!!cameraError || countdown !== null}
              className="w-20 h-20 rounded-full border-4 border-[#ccff00] p-1.5 flex items-center justify-center hover:scale-105 active:scale-95 transition-all shadow-[0_0_25px_#ccff00] group"
            >
              <div className="w-full h-full rounded-full bg-white group-hover:bg-[#ff2e93] transition-colors flex items-center justify-center">
                <i className="fas fa-camera text-black group-hover:text-white text-xl" />
              </div>
            </button>

            {/* Flip Camera */}
            {hasMultipleCameras ? (
              <button
                type="button"
                onClick={toggleCameraFacing}
                className="w-12 h-12 rounded-2xl bg-white/10 text-white hover:bg-white/20 flex flex-col items-center justify-center active:scale-95"
                title="Flip Camera"
              >
                <i className="fas fa-camera-rotate text-sm" />
                <span className="text-[9px] font-mono mt-0.5">FLIP</span>
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
