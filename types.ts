export type AnimeStyleId =
  | 'anime'
  | 'shonen'
  | 'comic'
  | 'cyberpunk';

export interface AnimeStylePreset {
  id: AnimeStyleId;
  name: string;
  japaneseName: string;
  emoji: string;
  vibeTag: string;
  tagline: string;
  description: string;
  icon: string;
  accentBg: string;
  accentBorder: string;
  badgeColor: string;
  promptDescription: string;
}

export type WonderTreatId =
  | 'pinkybear'
  | 'green_mango'
  | 'mango'
  | 'orange'
  | 'strawberry';

export interface WonderTreat {
  id: WonderTreatId;
  name: string;
  imageFileName: string;
  sriLankanTag: string;
  emoji: string;
  badge: string;
  flavor: string;
  accentColor: string;
  description: string;
  promptDescription: string;
}

export type AnimeGenerationPhase = 'analyzing' | 'sketching' | 'inking' | 'coloring';

export interface ImageState {
  file: File | null;
  preview: string | null;
  base64: string | null;
}

export interface AnimeResult {
  id: string;
  originalUrl: string;
  animeUrl: string;
  styleId: AnimeStyleId;
  styleName: string;
  styleEmoji: string;
  treatId: WonderTreatId;
  treatName: string;
  treatEmoji: string;
  treatImage: string;
  vibeTag: string;
  customPrompt?: string;
  timestamp: number;
}
