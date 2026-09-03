import React, { useRef, useState } from 'react';
import { ClothingAsset } from '../types';
import { useClothingLibrary } from '../hooks/useClothingLibrary';

type Category = ClothingAsset['category'];

const CATEGORIES: { value: Category; label: string; icon: string; desc: string }[] = [
  { value: 'upper',   label: 'Tops',       icon: 'fas fa-tshirt',       desc: 'Shirts, blouses, jackets, kurtis' },
  { value: 'lower',   label: 'Bottoms',    icon: 'fas fa-socks',        desc: 'Pants, skirts, shorts, trousers' },
  { value: 'overall', label: 'Full Dress', icon: 'fas fa-person-dress', desc: 'Dresses, jumpsuits, sarees, suits' },
];

interface UploadZoneProps {
  category: Category;
  onUpload: (file: File, name: string, category: Category) => Promise<void>;
}

const UploadZone: React.FC<UploadZoneProps> = ({ category, onUpload }) => {
  const inputRef = useRef<HTMLInputElement>(null);
  const [dragging, setDragging] = useState(false);
  const [uploading, setUploading] = useState(false);
  const [uploadError, setUploadError] = useState<string | null>(null);

  const handleFiles = async (files: FileList | null) => {
    if (!files?.length) return;
    setUploading(true);
    setUploadError(null);
    try {
      for (const file of Array.from(files)) {
        if (!file.type.startsWith('image/')) continue;
        const name = file.name.replace(/\.[^.]+$/, '');
        await onUpload(file, name, category);
      }
    } catch (e: any) {
      setUploadError(e.message ?? 'Upload failed');
    } finally {
      setUploading(false);
      if (inputRef.current) inputRef.current.value = '';
    }
  };

  return (
    <div>
      <div
        className={`border-2 border-dashed rounded-xl p-5 text-center cursor-pointer transition-all duration-200
          ${dragging ? 'border-blue-400 bg-blue-500/10' : 'border-slate-700 hover:border-slate-500 bg-slate-800/40'}`}
        onClick={() => inputRef.current?.click()}
        onDragOver={e => { e.preventDefault(); setDragging(true); }}
        onDragLeave={() => setDragging(false)}
        onDrop={e => { e.preventDefault(); setDragging(false); handleFiles(e.dataTransfer.files); }}
      >
        <input
          ref={inputRef}
          type="file"
          accept="image/*"
          multiple
          className="hidden"
          onChange={e => handleFiles(e.target.files)}
        />
        {uploading ? (
          <div className="flex items-center justify-center gap-2 text-blue-400 text-sm py-1">
            <i className="fas fa-spinner fa-spin" /> Uploading...
          </div>
        ) : (
          <>
            <i className="fas fa-cloud-arrow-up text-2xl text-slate-500 mb-2 block" />
            <p className="text-slate-400 text-sm font-medium">Drop images here or click to browse</p>
            <p className="text-slate-600 text-xs mt-1">PNG, JPG, WEBP — multiple files supported</p>
          </>
        )}
      </div>
      {uploadError && (
        <p className="text-red-400 text-xs mt-2 flex items-center gap-1">
          <i className="fas fa-circle-exclamation" /> {uploadError}
        </p>
      )}
    </div>
  );
};

interface AssetCardProps {
  asset: ClothingAsset;
  onDelete: (id: string) => Promise<void>;
}

const AssetCard: React.FC<AssetCardProps> = ({ asset, onDelete }) => {
  const [confirm, setConfirm] = useState(false);
  const [deleting, setDeleting] = useState(false);

  const handleDelete = async () => {
    setDeleting(true);
    try {
      await onDelete(asset.id);
    } finally {
      setDeleting(false);
      setConfirm(false);
    }
  };

  return (
    <div className="group relative rounded-xl overflow-hidden bg-slate-800 border border-slate-700 hover:border-slate-500 transition-all">
      <div className="aspect-[3/4] overflow-hidden bg-slate-900">
        <img
          src={asset.url}
          alt={asset.name}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        />
      </div>
      <div className="p-2">
        <p className="text-slate-300 text-xs font-medium truncate" title={asset.name}>{asset.name}</p>
        <p className="text-slate-600 text-[10px] mt-0.5">{new Date(asset.uploadedAt).toLocaleDateString()}</p>
      </div>
      <div className="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
        {confirm ? (
          <div className="flex gap-1">
            <button
              onClick={handleDelete}
              disabled={deleting}
              className="bg-red-600 hover:bg-red-500 text-white text-[10px] px-2 py-1 rounded-md font-bold disabled:opacity-60"
            >
              {deleting ? '...' : 'Delete'}
            </button>
            <button
              onClick={() => setConfirm(false)}
              className="bg-slate-700 hover:bg-slate-600 text-white text-[10px] px-2 py-1 rounded-md"
            >
              Cancel
            </button>
          </div>
        ) : (
          <button
            onClick={() => setConfirm(true)}
            className="bg-black/60 hover:bg-red-600/80 backdrop-blur-sm text-white w-7 h-7 rounded-full flex items-center justify-center transition-colors"
          >
            <i className="fas fa-trash text-[11px]" />
          </button>
        )}
      </div>
    </div>
  );
};

interface SectionProps {
  category: Category;
  label: string;
  icon: string;
  desc: string;
}

const Section: React.FC<SectionProps> = ({ category, label, icon, desc }) => {
  const { assets, loading, error, addAsset, removeAsset } = useClothingLibrary(category);

  return (
    <section>
      <div className="flex items-center justify-between mb-5">
        <div className="flex items-center gap-3">
          <div className="w-9 h-9 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
            <i className={`${icon} text-blue-400`} />
          </div>
          <div>
            <h2 className="text-white font-semibold text-lg">{label}</h2>
            <p className="text-slate-500 text-xs">{desc}</p>
          </div>
        </div>
        {!loading && (
          <span className="bg-slate-800 text-slate-400 text-xs px-3 py-1 rounded-full border border-slate-700">
            {assets.length} item{assets.length !== 1 ? 's' : ''}
          </span>
        )}
      </div>

      <UploadZone category={category} onUpload={addAsset} />

      {error && (
        <p className="text-red-400 text-sm mt-3 flex items-center gap-2">
          <i className="fas fa-triangle-exclamation" /> {error}
        </p>
      )}

      {loading ? (
        <div className="flex items-center gap-2 text-slate-500 text-sm mt-4 py-4">
          <i className="fas fa-spinner fa-spin" /> Loading...
        </div>
      ) : assets.length > 0 ? (
        <div className="mt-5 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
          {assets.map(asset => (
            <AssetCard key={asset.id} asset={asset} onDelete={removeAsset} />
          ))}
        </div>
      ) : (
        <p className="text-slate-600 text-sm text-center mt-4 py-4">
          No {label.toLowerCase()} uploaded yet.
        </p>
      )}
    </section>
  );
};

interface Props {
  onBack: () => void;
}

export const Dashboard: React.FC<Props> = ({ onBack }) => (
  <div className="min-h-screen bg-[#0f172a] text-slate-100">
    <header className="sticky top-0 z-40 bg-slate-900/80 backdrop-blur-md border-b border-slate-800 px-6 py-3 flex items-center justify-between">
      <div className="flex items-center gap-3">
        <button
          onClick={onBack}
          className="text-slate-400 hover:text-white transition-colors flex items-center gap-2 text-sm"
        >
          <i className="fas fa-arrow-left" /> Back to Try-On
        </button>
        <span className="text-slate-700">|</span>
        <h1 className="text-lg font-semibold text-white flex items-center gap-2">
          <i className="fas fa-layer-group text-blue-400" /> Clothing Dashboard
        </h1>
      </div>
      <p className="text-slate-500 text-xs hidden sm:block">Images are saved to your server database</p>
    </header>

    <main className="max-w-7xl mx-auto px-6 py-10 space-y-14">
      {CATEGORIES.map(cat => (
        <Section key={cat.value} category={cat.value} label={cat.label} icon={cat.icon} desc={cat.desc} />
      ))}
    </main>
  </div>
);
