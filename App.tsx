import React, { useState, useEffect } from 'react';
import { LoginPage } from './components/LoginPage';
import { ImageUploader } from './components/ImageUploader';
import { CameraCapture } from './components/CameraCapture';
import { WonderTreatSelector } from './components/WonderTreatSelector';
import { BeforeAfterSlider } from './components/BeforeAfterSlider';
import { LoadingOverlay } from './components/LoadingOverlay';
import {
  transformImageToAnime,
  ANIME_STYLES,
  WONDER_TREATS,
} from './services/geminiService';
import { imageUrlToBase64 } from './services/assetService';
import {
  AnimeStyleId,
  WonderTreatId,
  AnimeGenerationPhase,
  ImageState,
  AnimeResult,
} from './types';

interface UserInfo {
  name: string;
  phone: string;
}

type AppStep = 'login' | 'upload' | 'result';

const App: React.FC = () => {
  const [userInfo, setUserInfo] = useState<UserInfo | null>(null);
  const [currentStep, setCurrentStep] = useState<AppStep>('login');

  const [photo, setPhoto] = useState<ImageState>({
    file: null,
    preview: null,
    base64: null,
  });

  const [treatId, setTreatId] = useState<WonderTreatId>('pinkybear');
  const [styleId] = useState<AnimeStyleId>('anime');
  const [customPrompt] = useState<string>('');

  const [loading, setLoading] = useState(false);
  const [loadingPhase, setLoadingPhase] = useState<AnimeGenerationPhase>('analyzing');
  const [error, setError] = useState<string | null>(null);

  const [currentResult, setCurrentResult] = useState<AnimeResult | null>(null);
  const [history, setHistory] = useState<AnimeResult[]>([]);
  const [isCameraOpen, setIsCameraOpen] = useState(false);
  const [isLightboxOpen, setIsLightboxOpen] = useState(false);

  const baseUrl = import.meta.env.BASE_URL || '/';

  useEffect(() => {
    document.body.style.overflow = 'hidden';
    return () => {
      document.body.style.overflow = 'unset';
    };
  }, []);

  const fileToBase64 = (file: File): Promise<string> =>
    new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        const result = reader.result as string;
        resolve(result.split(',')[1]);
      };
      reader.onerror = reject;
    });

  const handleLogin = (info: UserInfo) => {
    setUserInfo(info);
    setCurrentStep('upload');
  };

  const handleImageSelect = async (file: File) => {
    try {
      const preview = URL.createObjectURL(file);
      const base64 = await fileToBase64(file);
      setPhoto({ file, preview, base64 });
      setError(null);
    } catch {
      setError('Could not load image');
    }
  };

  const handleCameraCapture = (file: File, previewUrl: string, base64: string) => {
    setPhoto({ file, preview: previewUrl, base64 });
    setIsCameraOpen(false);
    setError(null);
  };

  const handleClearPhoto = () => {
    setPhoto({ file: null, preview: null, base64: null });
    setError(null);
  };

  const currentTreatObj = WONDER_TREATS.find((t) => t.id === treatId) || WONDER_TREATS[0];
  const currentStyleObj = ANIME_STYLES.find((s) => s.id === styleId) || ANIME_STYLES[0];

  const handleGenerateAnime = async () => {
    if (!photo.base64) {
      setIsCameraOpen(true);
      return;
    }
    setLoading(true);
    setLoadingPhase('analyzing');
    setError(null);

    try {
      const mimeType = photo.file?.type || 'image/jpeg';

      let treatBase64: string | undefined = undefined;
      try {
        const treatImageUrl = `${baseUrl}${currentTreatObj.imageFileName}`;
        const treatData = await imageUrlToBase64(treatImageUrl);
        treatBase64 = treatData.base64;
      } catch (err) {
        console.warn('Could not load treat image base64, proceeding with text prompt:', err);
      }

      const animeDataUrl = await transformImageToAnime(
        photo.base64,
        mimeType,
        styleId,
        treatId,
        treatBase64,
        customPrompt,
        (phase) => setLoadingPhase(phase)
      );

      const newResult: AnimeResult = {
        id: `wonder-toon-${Date.now()}`,
        originalUrl: photo.preview || `data:${mimeType};base64,${photo.base64}`,
        animeUrl: animeDataUrl,
        styleId,
        styleName: currentStyleObj.name,
        styleEmoji: currentStyleObj.emoji,
        treatId,
        treatName: currentTreatObj.name,
        treatEmoji: currentTreatObj.emoji,
        treatImage: `${baseUrl}${currentTreatObj.imageFileName}`,
        vibeTag: currentStyleObj.vibeTag,
        customPrompt: customPrompt.trim() || undefined,
        timestamp: Date.now(),
      };

      setCurrentResult(newResult);
      setHistory((prev) => [newResult, ...prev]);
      setCurrentStep('result');
    } catch (err: any) {
      console.error('Generation error:', err);
      setError(err.message || 'Generation failed. Try again!');
    } finally {
      setLoading(false);
    }
  };

  const handleShare = async (resultToShare: AnimeResult) => {
    try {
      if (navigator.share) {
        // Convert base64 data url to blob file for native web share
        const res = await fetch(resultToShare.animeUrl);
        const blob = await res.blob();
        const file = new File([blob], `wonder-anime-${resultToShare.treatId}.png`, { type: 'image/png' });
        
        await navigator.share({
          title: 'My Wonder Anime Look',
          text: 'Check out my Wonder Anime look! 🍦✨',
          files: [file],
        });
      } else {
        handleDownload(resultToShare);
      }
    } catch (err) {
      console.warn('Share error or cancelled:', err);
    }
  };

  const handleRetake = () => {
    handleClearPhoto();
    setCurrentStep('upload');
  };

  return (
    <div className="min-h-screen w-screen bg-[#faf9fd] text-[#0f172a] flex flex-col justify-center items-center p-3 sm:p-4 select-none overflow-y-auto relative">
      {/* Background Soft Glow */}
      <div className="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-lg h-96 bg-gradient-to-b from-pink-200/40 via-purple-100/30 to-transparent blur-3xl pointer-events-none -z-10" />

      {/* Loading Overlay */}
      {loading && (
        <LoadingOverlay
          phase={loadingPhase}
          styleName={currentStyleObj.name}
          treatName={currentTreatObj.name}
        />
      )}

      {/* Camera Viewfinder */}
      {isCameraOpen && (
        <CameraCapture
          onCapture={handleCameraCapture}
          onClose={() => setIsCameraOpen(false)}
        />
      )}

      {/* Lightbox Fullscreen */}
      {isLightboxOpen && currentResult && (
        <div
          className="fixed inset-0 z-50 bg-slate-950/95 backdrop-blur-md flex flex-col items-center justify-between p-4 animate-fade-in"
          onClick={() => setIsLightboxOpen(false)}
        >
          <div className="w-full flex justify-between items-center p-2">
            <span className="bg-[#ff2975] text-white font-black text-xs px-3.5 py-1.5 rounded-full flex items-center gap-1.5 shadow-md">
              <img src={currentResult.treatImage} alt="" className="w-4 h-4 object-contain" />
              <span>{currentResult.treatName}</span>
            </span>
            <button
              onClick={() => setIsLightboxOpen(false)}
              className="w-9 h-9 rounded-full bg-white/20 text-white font-bold flex items-center justify-center text-lg active:scale-90"
            >
              ✕
            </button>
          </div>

          <div
            className="relative flex-1 w-full max-h-[75vh] flex items-center justify-center p-2"
            onClick={(e) => e.stopPropagation()}
          >
            <img
              src={currentResult.animeUrl}
              alt="Wonder"
              className="max-h-full max-w-full object-contain rounded-3xl shadow-2xl"
            />
          </div>

          <div className="w-full max-w-sm pb-4" onClick={(e) => e.stopPropagation()}>
            <button
              onClick={() => handleDownload(currentResult)}
              className="w-full py-4 rounded-2xl bg-[#ffea00] text-slate-900 font-black text-sm shadow-xl hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2"
            >
              <span>SAVE TO GALLERY 💾</span>
            </button>
          </div>
        </div>
      )}

      {/* Main Single Card Container */}
      <div className="w-full max-w-md my-auto">
        {/* STEP 1: LOGIN */}
        {currentStep === 'login' && (
          <LoginPage onLogin={handleLogin} />
        )}

        {/* STEP 2: PERFECTLY FITTED CARD WITH ZERO EMPTY WHITESPACE */}
        {currentStep === 'upload' && (
          <div className="wonder-glass-card p-4 rounded-3xl flex flex-col space-y-3.5 shadow-xl animate-fade-in">
            {/* Header inside Card with EH Logo on Left & Wonder Logo on Right */}
            <div className="flex items-center justify-between shrink-0 pb-2 border-b border-pink-100/60">
              <img src={`${baseUrl}eh-logo.png`} alt="Elephant House" className="h-6 object-contain" />
              <img src={`${baseUrl}wonder.png`} alt="Wonder" className="h-6 object-contain" />
            </div>

            {/* Top: Expanded Image Uploader Box */}
            <div>
              <ImageUploader
                preview={photo.preview}
                onImageSelect={handleImageSelect}
                onOpenCamera={() => setIsCameraOpen(true)}
                onClear={handleClearPhoto}
              />
            </div>

            {/* Middle: Flavor Grid */}
            <div>
              <WonderTreatSelector
                selectedTreatId={treatId}
                onSelectTreat={setTreatId}
              />
            </div>

            {/* Error Message */}
            {error && (
              <div className="p-2 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-xs font-bold text-center">
                ⚠️ {error}
              </div>
            )}

            {/* Bottom Action Button */}
            <div className="pt-1">
              <button
                type="button"
                onClick={handleGenerateAnime}
                disabled={loading}
                className={`
                  w-full py-3.5 px-5 rounded-2xl font-black text-sm uppercase tracking-wider transition-all flex items-center justify-center gap-2
                  ${
                    loading
                      ? 'bg-slate-200 text-slate-400 cursor-not-allowed'
                      : photo.base64
                      ? 'bg-gradient-to-r from-[#ff2975] via-[#ff0055] to-[#ffab00] text-white shadow-[0_10px_25px_-5px_rgba(255,41,117,0.4)] hover:shadow-[0_14px_28px_-5px_rgba(255,41,117,0.5)] active:scale-[0.99]'
                      : 'bg-[#ff2975] text-white shadow-md'
                  }
                `}
              >
                {loading ? (
                  <span>CREATING ANIME HERO...</span>
                ) : photo.base64 ? (
                  <span>CREATE {currentTreatObj.name.toUpperCase()} ⚡</span>
                ) : (
                  <span>TAKE SELFIE TO START 📸</span>
                )}
              </button>
            </div>
          </div>
        )}

        {/* STEP 3: RESULT IN SINGLE CARD WITH EMBEDDED LOGO HEADER */}
        {currentStep === 'result' && currentResult && (
          <div className="wonder-glass-card p-4 rounded-3xl flex flex-col space-y-3.5 shadow-xl animate-fade-in">
            <div className="flex items-center justify-between shrink-0 pb-2 border-b border-pink-100/60">
              <img src={`${baseUrl}eh-logo.png`} alt="Elephant House" className="h-6 object-contain" />
              <img src={`${baseUrl}wonder.png`} alt="Wonder" className="h-6 object-contain" />
            </div>

            <div className="flex-1 min-h-0 flex flex-col justify-center">
              <BeforeAfterSlider
                originalUrl={currentResult.originalUrl}
                animeUrl={currentResult.animeUrl}
                styleName={currentResult.styleName}
                styleEmoji={currentResult.styleEmoji}
                treatName={currentResult.treatName}
                treatEmoji={currentResult.treatEmoji}
                vibeTag={currentResult.vibeTag}
                onExpand={() => setIsLightboxOpen(true)}
                onDownload={() => handleDownload(currentResult)}
                onReset={() => setCurrentStep('upload')}
                onShare={() => handleShare(currentResult)}
                onRetake={handleRetake}
              />
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default App;
