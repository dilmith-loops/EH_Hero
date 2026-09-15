import React, { useState, useEffect } from 'react';
import { LoginPage } from './components/LoginPage';
import { MaintenancePage } from './components/MaintenancePage';
import { NotFoundPage } from './components/NotFoundPage';
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
import { saveGeneratedPortrait, checkGenerationLimit, checkSystemStatus, UserInfo } from './services/apiService';

type AppStep = 'login' | 'upload' | 'result' | 'maintenance' | 'not-found';

const getDisplayError = (raw: string | null): string => {
  if (!raw) return 'Too many generations, please try again.';
  const str = raw.trim();
  if (
    str.startsWith('{') ||
    str.startsWith('[') ||
    /error|leaked|api[_\s]?key|denied|permission|status|403|500|404|429|undefined|null|failed|exception|fetch/i.test(str)
  ) {
    return 'Too many generations, please try again.';
  }
  return str;
};

const App: React.FC = () => {
  const [userInfo, setUserInfo] = useState<UserInfo | null>(null);
  const [currentStep, setCurrentStep] = useState<AppStep>('login');
  const [maintenanceMessage, setMaintenanceMessage] = useState<string | null>(null);

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

  useEffect(() => {
    const handleRoute = () => {
      const hash = window.location.hash.toLowerCase();
      const params = new URLSearchParams(window.location.search);
      const path = window.location.pathname;

      if (
        (!path.includes('EH-PORTAL-IT-ADMIN')) &&
        (hash === '#eh-portal-it-admin' ||
         params.get('page')?.toUpperCase() === 'EH-PORTAL-IT-ADMIN' ||
         params.get('admin') !== null)
      ) {
        window.location.href = '/EH-Hero/EH-PORTAL-IT-ADMIN';
        return;
      }

      if (hash === '#maintenance' || params.get('page') === 'maintenance') {
        setCurrentStep('maintenance');
        return;
      }

      if (hash === '#404' || hash === '#notfound' || hash === '#not-found' || params.get('page') === '404') {
        setCurrentStep('not-found');
        return;
      }

      // Check if pathname is an unknown/not found route
      const cleanPath = path.replace(/\/+$/, '');
      const cleanBase = baseUrl.replace(/\/+$/, '');
      const isHome = cleanPath === cleanBase || cleanPath === `${cleanBase}/index.html`;

      if (!isHome && !path.includes('EH-PORTAL-IT-ADMIN')) {
        setCurrentStep('not-found');
        return;
      }
    };

    handleRoute();
    window.addEventListener('hashchange', handleRoute);
    return () => window.removeEventListener('hashchange', handleRoute);
  }, [baseUrl]);

  useEffect(() => {
    const verifySystemStatus = async () => {
      try {
        const res = await checkSystemStatus();
        if (res.maintenance) {
          setMaintenanceMessage(res.message);
          setCurrentStep('maintenance');
        }
      } catch (err) {
        console.warn('System status check error:', err);
      }
    };
    verifySystemStatus();
  }, []);

  const handleGoHome = () => {
    window.location.hash = '';
    const cleanPath = window.location.pathname.replace(/\/+$/, '');
    const cleanBase = baseUrl.replace(/\/+$/, '');
    if (cleanPath !== cleanBase && cleanPath !== `${cleanBase}/index.html`) {
      window.location.href = baseUrl;
    } else {
      setCurrentStep('login');
    }
  };

  const handleMaintenanceRefresh = async () => {
    try {
      const res = await checkSystemStatus();
      if (!res.maintenance) {
        setMaintenanceMessage(null);
        window.location.hash = '';
        setCurrentStep('login');
      } else {
        setMaintenanceMessage(res.message);
      }
    } catch {
      window.location.reload();
    }
  };

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
      // Pre-check generation limit & maintenance status for this device/IP
      const limitCheck = await checkGenerationLimit();
      if (limitCheck.maintenance) {
        setMaintenanceMessage(limitCheck.message || null);
        setCurrentStep('maintenance');
        setLoading(false);
        return;
      }
      if (!limitCheck.can_generate) {
        setError('Too many generations, please try again.');
        setLoading(false);
        return;
      }

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

      // Auto-save to Laravel MySQL database & storage in background
      if (userInfo?.id) {
        saveGeneratedPortrait({
          app_user_id: userInfo.id,
          treat_id: treatId,
          treat_name: currentTreatObj.name,
          style_id: styleId,
          custom_prompt: customPrompt.trim() || undefined,
          original_image: photo.preview || (photo.base64 ? `data:${mimeType};base64,${photo.base64}` : undefined),
          generated_image: animeDataUrl,
        }).then((saved) => {
          if (saved) {
            console.log('Saved generation to Elephant House backend #', saved.id);
          }
        }).catch((err) => {
          console.warn('Background save generation failed:', err);
        });
      }
    } catch (err: any) {
      const rawMsg = err?.message || 'Transformation failed';
      const cleanMsg = rawMsg
        .replace(/AIzaSy[a-zA-Z0-9_\-]{20,}/g, 'AIzaSy***[REDACTED]***')
        .replace(/api_key:['"]?[a-zA-Z0-9_\-\.]+['"]?/gi, 'api_key:***[REDACTED]***')
        .replace(/AQ\.[a-zA-Z0-9_\-\.]{15,}/gi, 'AQ.***[REDACTED]***')
        .replace(/([?&]key=)[a-zA-Z0-9_\-]+/gi, '$1***[REDACTED]***');

      console.error('❌ [Generation Error]:', cleanMsg, err?.status ? `(Status: ${err.status})` : '');
      setError(cleanMsg);
    } finally {
      setLoading(false);
    }
  };

  const handleDownload = (resultToDownload: AnimeResult) => {
    try {
      const link = document.createElement('a');
      link.href = resultToDownload.animeUrl;
      link.download = `wonder-anime-hero-${resultToDownload.treatId}-${Date.now()}.png`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    } catch (err) {
      console.error('Download error:', err);
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
    <div className="h-[100dvh] max-h-[100dvh] w-full bg-[#faf9fd] text-[#0f172a] flex flex-col justify-center items-center p-0 sm:p-4 select-none overflow-hidden relative">

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
              <span>SAVE TO GALLERY</span>
            </button>
          </div>
        </div>
      )}

      {/* Main Single Card Container */}
      <div className="w-full h-full max-h-full sm:h-auto sm:max-h-[100dvh] sm:max-w-md my-auto flex flex-col justify-between overflow-hidden">
        {/* STEP 1: LOGIN */}
        {currentStep === 'login' && (
          <LoginPage onLogin={handleLogin} />
        )}

        {/* STEP 2: PERFECTLY FITTED CARD WITH ZERO EMPTY WHITESPACE */}
        {currentStep === 'upload' && (
          <div className="wonder-colorful-card w-full h-full sm:h-auto max-h-full sm:max-h-[100dvh] p-3.5 sm:p-4 sm:rounded-3xl rounded-none flex flex-col justify-between space-y-3 shadow-2xl animate-fade-in overflow-hidden">
            {/* Header inside Card with Scaled Up & Centered Logos */}
            <div className="flex items-center justify-center gap-3 sm:gap-4 shrink-0 pb-2.5 border-b border-pink-100/60">
              <img src={`${baseUrl}eh-logo.png`} alt="Elephant House" className="h-10 sm:h-12 object-contain filter drop-shadow-sm" />
              <span className="text-pink-300 font-bold text-base">✕</span>
              <img src={`${baseUrl}wonder.png`} alt="Wonder" className="h-10 sm:h-12 object-contain filter drop-shadow-[0_4px_12px_rgba(255,41,117,0.3)]" />
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
              <div className="p-2.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 text-xs font-bold text-center animate-fade-in shadow-xs">
                ⚠️ {getDisplayError(error)}
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

        {/* STEP 3: RESULT SCREEN */}
        {currentStep === 'result' && currentResult && (
          <div className="wonder-colorful-card w-full h-full sm:h-auto max-h-full sm:max-h-[100dvh] p-3 sm:p-4 sm:rounded-3xl rounded-none flex flex-col justify-between space-y-2.5 shadow-2xl animate-fade-in overflow-hidden">
            {/* Header inside Card with Scaled Up & Centered Logos */}
            <div className="flex items-center justify-center gap-3 sm:gap-4 shrink-0 pb-2 border-b border-pink-100/60">
              <img src={`${baseUrl}eh-logo.png`} alt="Elephant House" className="h-9 sm:h-11 object-contain filter drop-shadow-sm" />
              <span className="text-pink-300 font-bold text-base">✕</span>
              <img src={`${baseUrl}wonder.png`} alt="Wonder" className="h-9 sm:h-11 object-contain filter drop-shadow-[0_4px_12px_rgba(255,41,117,0.3)]" />
            </div>

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
        )}

        {/* STEP 4: MAINTENANCE PAGE */}
        {currentStep === 'maintenance' && (
          <MaintenancePage onRefresh={handleMaintenanceRefresh} message={maintenanceMessage} />
        )}

        {/* STEP 5: 404 NOT FOUND PAGE */}
        {currentStep === 'not-found' && (
          <NotFoundPage onGoHome={handleGoHome} />
        )}
      </div>
    </div>
  );
};

export default App;
