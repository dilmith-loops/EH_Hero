import React, { useRef, useState, useEffect } from 'react';

interface ImageUploaderProps {
  preview: string | null;
  onImageSelect: (file: File) => void;
  onOpenCamera: () => void;
  onClear: () => void;
  onSelectSample?: (url: string) => void;
}

const SAMPLE_AVATARS = [
  {
    name: 'Chloe 🌸',
    tag: 'SLAY',
    url: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=500&q=80',
    ring: 'from-[#ff2e93] to-[#ffe600]',
  },
  {
    name: 'Kai ⚡',
    tag: 'DRIP',
    url: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=500&q=80',
    ring: 'from-[#00f0ff] to-[#ccff00]',
  },
  {
    name: 'Zara 📼',
    tag: 'Y2K',
    url: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=500&q=80',
    ring: 'from-[#9d4edd] to-[#ff2e93]',
  },
];

export const ImageUploader: React.FC<ImageUploaderProps> = ({
  preview,
  onImageSelect,
  onOpenCamera,
  onClear,
  onSelectSample,
}) => {
  const inputRef = useRef<HTMLInputElement>(null);
  const [isDragging, setIsDragging] = useState(false);

  useEffect(() => {
    const handlePaste = (e: ClipboardEvent) => {
      const items = e.clipboardData?.items;
      if (!items) return;
      for (let i = 0; i < items.length; i++) {
        if (items[i].type.startsWith('image/')) {
          const file = items[i].getAsFile();
          if (file) {
            onImageSelect(file);
            break;
          }
        }
      }
    };
    window.addEventListener('paste', handlePaste);
    return () => window.removeEventListener('paste', handlePaste);
  }, [onImageSelect]);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) onImageSelect(file);
  };

  const handleDragOver = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(true);
  };

  const handleDragLeave = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(false);
  };

  const handleDrop = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(false);
    const file = e.dataTransfer.files?.[0];
    if (file && file.type.startsWith('image/')) {
      onImageSelect(file);
    }
  };

  return (
    <div className="space-y-3">
      {/* Main Upload / Camera Viewport */}
      <div className="relative">
        <div
          onDragOver={handleDragOver}
          onDragLeave={handleDragLeave}
          onDrop={handleDrop}
          className={`
            relative w-full h-44 sm:h-52 rounded-2xl transition-all duration-200 overflow-hidden flex flex-col items-center justify-center
            ${
              preview
                ? 'bg-slate-950 shadow-md'
                : isDragging
                ? 'border-2 border-[#ff2975] bg-pink-50/50 scale-[1.01]'
                : 'border-2 border-dashed border-slate-200/80 hover:border-pink-300 bg-slate-50/60 hover:bg-white shadow-sm'
            }
          `}
        >
          {preview ? (
            <>
              <img
                src={preview}
                alt="Your photo"
                className="w-full h-full object-contain p-1"
              />

              {/* Trash Button */}
              <button
                type="button"
                onClick={(e) => {
                  e.stopPropagation();
                  onClear();
                }}
                className="absolute top-3 right-3 w-9 h-9 rounded-full bg-slate-900/80 hover:bg-[#ff2975] text-white flex items-center justify-center backdrop-blur-md active:scale-90 transition-all z-10"
                title="Remove photo"
              >
                ✕
              </button>
            </>
          ) : (
            <div className="p-4 text-center flex flex-col items-center justify-center space-y-3 max-w-xs">
              <div className="w-14 h-14 rounded-2xl bg-gradient-to-tr from-pink-100 to-yellow-100 border border-pink-200 flex items-center justify-center text-2xl shadow-sm">
                📸
              </div>

              <div>
                <h3 className="text-slate-800 font-extrabold text-sm">
                  Add Your Selfie
                </h3>
                <p className="text-slate-400 text-xs font-semibold">
                  Snap or upload a photo
                </p>
              </div>

              {/* Action Buttons */}
              <div className="flex w-full gap-2 pt-1">
                <button
                  type="button"
                  onClick={onOpenCamera}
                  className="flex-1 py-3 px-3 rounded-2xl bg-[#ffea00] hover:bg-[#ffe600] text-slate-900 font-black text-xs shadow-md active:scale-95 transition-all flex items-center justify-center gap-1.5"
                >
                  <i className="fas fa-camera text-xs" />
                  <span>CAMERA</span>
                </button>

                <button
                  type="button"
                  onClick={() => inputRef.current?.click()}
                  className="flex-1 py-3 px-3 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-black text-xs shadow-md active:scale-95 transition-all flex items-center justify-center gap-1.5"
                >
                  <i className="fas fa-images text-xs text-pink-400" />
                  <span>UPLOAD</span>
                </button>
              </div>
            </div>
          )}
        </div>

        <input
          ref={inputRef}
          type="file"
          accept="image/*"
          className="hidden"
          onChange={handleFileChange}
        />
      </div>
    </div>
  );
};
