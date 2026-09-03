import React, { useState } from 'react';
import { AnimeStyleId } from '../types';
import { ANIME_STYLES } from '../services/geminiService';

interface StyleSelectorProps {
  selectedStyleId: AnimeStyleId;
  onSelectStyle: (styleId: AnimeStyleId) => void;
  customPrompt: string;
  onChangeCustomPrompt: (prompt: string) => void;
}

const FUNKY_PROMPT_SUGGESTIONS = [
  '💬 Speech Bubble: "Yum!"',
  '💬 Speech Bubble: "Wonder Time!"',
  '🧥 Hot Pink & Orange Jacket',
  '✨ Golden Star Sparkles',
  '🎧 Y2K Retro Headphones',
  '⚡ Electric Action Aura',
  '🕶️ Cool Tinted Shades',
  '🌸 Floating Cherry Blossoms',
];

export const StyleSelector: React.FC<StyleSelectorProps> = ({
  selectedStyleId,
  onSelectStyle,
  customPrompt,
  onChangeCustomPrompt,
}) => {
  const [showCustomDetails, setShowCustomDetails] = useState(false);

  const handleSuggestionClick = (suggestion: string) => {
    const cleanText = suggestion.replace(/^[^\w\s"]+\s*/, '');
    if (!customPrompt) {
      onChangeCustomPrompt(cleanText);
    } else if (!customPrompt.includes(cleanText)) {
      onChangeCustomPrompt(`${customPrompt}, ${cleanText}`);
    }
  };

  return (
    <div className="space-y-3">
      <div className="flex items-center justify-between px-1">
        <label className="text-xs font-black uppercase tracking-wider text-slate-300 flex items-center gap-1.5">
          <span>🎨 PICK YOUR ANIME COMIC STYLE:</span>
        </label>
        <span className="text-[10px] font-mono text-[#ff2e93] font-bold">
          CARD STYLES ✨
        </span>
      </div>

      {/* 2x2 Responsive Grid for the 4 Styles */}
      <div className="grid grid-cols-2 gap-2.5">
        {ANIME_STYLES.map((style) => {
          const isSelected = selectedStyleId === style.id;
          return (
            <button
              key={style.id}
              type="button"
              onClick={() => onSelectStyle(style.id)}
              className={`
                relative p-3 rounded-2xl text-left transition-all duration-150 flex flex-col justify-between
                ${
                  isSelected
                    ? 'bg-[#181a28] border-2 ' +
                      style.accentBorder +
                      ' brutal-shadow scale-[1.02] ring-1 ring-white/30'
                    : 'bg-[#12131d] border-2 border-slate-800 hover:border-slate-700 opacity-90 hover:opacity-100'
                }
              `}
            >
              {/* Header: Emoji & Vibe Sticker */}
              <div className="flex items-start justify-between gap-1 mb-2">
                <span className="text-2xl">{style.emoji}</span>
                <span
                  className={`text-[8px] font-black uppercase px-2 py-0.5 rounded-md tracking-wider ${
                    isSelected ? style.badgeColor : 'bg-slate-800 text-slate-400'
                  }`}
                >
                  {style.vibeTag}
                </span>
              </div>

              <div>
                <div className="flex items-center gap-1.5">
                  <h4 className="text-white font-black text-xs tracking-tight">
                    {style.name}
                  </h4>
                </div>
                <p className="text-[10px] text-slate-400 mt-1 line-clamp-2 leading-tight">
                  {style.description}
                </p>
              </div>

              {isSelected && (
                <div className="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-[#ccff00] text-black text-[9px] font-black flex items-center justify-center border border-black shadow-sm">
                  ✓
                </div>
              )}
            </button>
          );
        })}
      </div>

      {/* Extra Funky Additions Drawer */}
      <div className="pt-1">
        <button
          type="button"
          onClick={() => setShowCustomDetails(!showCustomDetails)}
          className="w-full py-2 px-3 rounded-xl bg-[#151722] hover:bg-[#1b1e2c] border border-slate-800 text-xs font-bold text-slate-300 hover:text-[#ccff00] transition-all flex items-center justify-between"
        >
          <span className="flex items-center gap-2">
            <span>💬 CUSTOM SPEECH BUBBLE & ACCESSORIES</span>
          </span>
          <span className="text-[10px] font-mono">{showCustomDetails ? '▲ HIDE' : '▼ CUSTOMIZE'}</span>
        </button>

        {showCustomDetails && (
          <div className="mt-2.5 p-3.5 rounded-2xl bg-[#151724] border-2 border-slate-800 space-y-3 animate-fade-in">
            <div className="space-y-1">
              <div className="flex items-center justify-between text-[11px] font-bold text-slate-300">
                <span>Custom speech bubble text or outfit:</span>
                {customPrompt && (
                  <button
                    onClick={() => onChangeCustomPrompt('')}
                    className="text-[10px] text-[#ff2e93] font-bold hover:underline"
                  >
                    CLEAR
                  </button>
                )}
              </div>
              <input
                type="text"
                value={customPrompt}
                onChange={(e) => onChangeCustomPrompt(e.target.value)}
                placeholder='e.g. Speech bubble: "Yum!", wearing sporty orange jacket...'
                className="w-full px-3.5 py-2.5 rounded-xl bg-[#0d0e15] border-2 border-slate-700 text-white text-xs font-medium focus:outline-none focus:border-[#ccff00] transition-colors placeholder:text-slate-600"
              />
            </div>

            {/* Quick Suggestion Chips */}
            <div className="space-y-1.5">
              <span className="text-[10px] font-black uppercase tracking-wider text-slate-500">
                ⚡ QUICK ADDITIONS:
              </span>
              <div className="flex flex-wrap gap-1.5">
                {FUNKY_PROMPT_SUGGESTIONS.map((item, i) => (
                  <button
                    key={i}
                    type="button"
                    onClick={() => handleSuggestionClick(item)}
                    className="px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#1e2133] hover:bg-[#282c44] text-slate-200 border border-slate-700 hover:border-[#ccff00] transition-all active:scale-95"
                  >
                    {item}
                  </button>
                ))}
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};
