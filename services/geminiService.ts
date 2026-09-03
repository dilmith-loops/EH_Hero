import { GoogleGenAI, GenerateContentResponse } from "@google/genai";
import {
  AnimeStyleId,
  AnimeStylePreset,
  AnimeGenerationPhase,
  WonderTreatId,
  WonderTreat,
} from '../types';

export const WONDER_TREATS: WonderTreat[] = [
  {
    id: 'pinkybear',
    name: 'Wonder Pinky Bear',
    imageFileName: 'pinkybear.png',
    sriLankanTag: 'ICONIC 🐻',
    emoji: '🐻',
    badge: 'KAWAII BEAR',
    flavor: 'Strawberry & Cream Bear',
    accentColor: 'border-pink-400 text-pink-300',
    description: 'Iconic panda-bear shaped ice cream bar with pink ears, body, and sweet creamy white face.',
    promptDescription:
      'Wonder Pinky Bear ice cream on a wooden stick: a charming panda/bear-shaped ice cream bar with pastel pink ears, arms and body, round pink eyes, and a sweet creamy vanilla-white face and tummy.',
  },
  {
    id: 'green_mango',
    name: 'Wonder Green Mango',
    imageFileName: 'green_mango.png',
    sriLankanTag: 'TANGY HIT 🍏',
    emoji: '🍏',
    badge: 'ZAP OF LIME',
    flavor: 'Zesty Raw Green Mango',
    accentColor: 'border-lime-400 text-lime-300',
    description: 'Vibrant neon green icy popsicle with 3 vertical ridges and refreshing tangy green mango punch.',
    promptDescription:
      'Wonder Green Mango ice lolly on a wooden stick: electric neon-lime-green frozen fruit popsicle with 3 prominent vertical grooves, glistening with translucent icy texture.',
  },
  {
    id: 'mango',
    name: 'Wonder Mango',
    imageFileName: 'mango.png',
    sriLankanTag: 'REAL SHAPE 🥭',
    emoji: '🥭',
    badge: 'CEYLON MANGO',
    flavor: 'Ripe Sweet Island Mango',
    accentColor: 'border-amber-400 text-amber-300',
    description: 'Real mango fruit-shaped bright golden-orange ice cream bar with sweet tropical mango goodness.',
    promptDescription:
      'Wonder Mango ice cream bar on a wooden stick: sculpted in the exact shape of a ripe mango fruit slice in radiant golden-orange, with real mango fruit gloss.',
  },
  {
    id: 'orange',
    name: 'Wonder Orange Wheel',
    imageFileName: 'orange.png',
    sriLankanTag: 'CITRUS POP 🍊',
    emoji: '🍊',
    badge: 'ORANGE SLICE',
    flavor: 'Juicy Orange & Cream',
    accentColor: 'border-orange-400 text-orange-300',
    description: 'Round citrus orange slice lollipop popsicle with bright orange rind and creamy white segments.',
    promptDescription:
      'Wonder Orange Wheel ice cream lollipop on a wooden stick: circular round orange slice shaped popsicle featuring a bright orange fruit rind and 6 distinct creamy white triangular wedge segments radiating from the center on a wooden stick.',
  },
  {
    id: 'strawberry',
    name: 'Wonder Strawberry',
    imageFileName: 'strawberry.png',
    sriLankanTag: 'SWEET BERRY 🍓',
    emoji: '🍓',
    badge: 'FRUIT SHAPE',
    flavor: 'Fragrant Red Strawberry',
    accentColor: 'border-rose-400 text-rose-300',
    description: 'Sculpted strawberry fruit-shaped pink berry ice cream bar with scalloped crown edges.',
    promptDescription:
      'Wonder Strawberry ice cream bar on a wooden stick: sculpted in the shape of a fresh strawberry fruit with scalloped crown edges, candy pink berry color on a wooden stick.',
  },
];

export const ANIME_STYLES: AnimeStylePreset[] = [
  {
    id: 'anime',
    name: 'Wonder Comic Anime',
    japaneseName: 'ワンダーコミック',
    emoji: '✨',
    vibeTag: 'SIGNATURE 🌟',
    tagline: 'Wonder Cartoon Style',
    description: 'Clean cartoon animation style, original background and dress preserved with ice cream treat.',
    icon: 'fa-wand-magic-sparkles',
    accentBg: 'bg-pink-500/15',
    accentBorder: 'border-[#ff2e93]',
    badgeColor: 'bg-[#ff2e93] text-white',
    promptDescription:
      'Clean 2D cartoon animation artwork preserving facial features, natural proportions, original background, and original dress.',
  },
];

export async function transformImageToAnime(
  imageBase64: string,
  mimeType = 'image/jpeg',
  styleId: AnimeStyleId = 'anime',
  treatId: WonderTreatId = 'pinkybear',
  treatBase64?: string,
  customPrompt?: string,
  onProgress?: (phase: AnimeGenerationPhase) => void,
): Promise<string> {
  const apiKey = process.env.API_KEY || process.env.GEMINI_API_KEY;
  if (!apiKey) {
    throw new Error('Gemini API key is missing. Set GEMINI_API_KEY in .env.local');
  }

  const ai = new GoogleGenAI({ apiKey });
  const selectedStyle = ANIME_STYLES.find((s) => s.id === styleId) || ANIME_STYLES[0];
  const selectedTreat = WONDER_TREATS.find((t) => t.id === treatId) || WONDER_TREATS[0];

  onProgress?.('analyzing');

  await new Promise((r) => setTimeout(r, 600));
  onProgress?.('sketching');

  const customInstruction = customPrompt?.trim()
    ? `\nUSER CUSTOM DETAILS / PROMPT: ${customPrompt.trim()}`
    : '';

  const treatImageReferenceNotice = treatBase64
    ? `\nEXACT WONDER PRODUCT REFERENCE (Image 2): The ice cream in Image 2 is "${selectedTreat.name}". Draw this exact shape, colors, and wooden popsicle stick in the illustration!`
    : '';

  // MASTER PROMPT: GUARANTEED 2D ANIME ILLUSTRATION TRANSFORMATION WITH PERFECT LIKENESS
  const generationPrompt = `Transform the person in Image 1 into a STUNNING HIGH-QUALITY 2D ANIME / CARTOON CHARACTER ILLUSTRATION:

1. GUARANTEED 2D ANIME / CARTOON REDRAW (MUST NOT LOOK LIKE A REAL PHOTO):
- COMPLETE ARTISTIC REDRAW: You MUST completely redraw the person's face, skin, eyes, hair, and clothing into a stylized 2D anime/manga digital illustration with clean drawn line art, smooth vibrant anime cel-shading, and bright anime highlights!
- NO REALISTIC SKIN TEXTURES: The face and skin must NOT be left as a realistic photo. Render every inch of skin, hair, eyes, and clothing in 2D animated cartoon art style.

2. FACIAL LIKENESS & ANATOMY CONTROL (KEEP CHARACTER EXACTLY THE SAME):
- 100% RECOGNIZABLE INDIVIDUAL: Keep the exact facial structure, eye shape, nose shape, smile, hairstyle, and facial proportions of the person in Image 1 so they are immediately recognizable as an anime character version of themselves.
- EXACTLY TWO ARMS & TWO HANDS TOTAL: The character has ONLY TWO arms and TWO hands. Modify ONE hand from Image 1 to hold the "${selectedTreat.name}" ice cream bar naturally on its wooden stick near their chest/shoulder. Ensure hand and fingers are drawn in clean 2D anime art style.
- NO EXTRA HANDS / NO DUPLICATE ARMS under any circumstances.

3. CLEAN VIBRANT COLORFUL ANIME GRADIENT BACKGROUND:
- Replace the background behind the character with a clean, smooth, colorful 2D anime backdrop color-matched to "${selectedTreat.name}" (${selectedTreat.flavor}):
  * Use radiant, vibrant anime color gradients matching the signature colors of "${selectedTreat.name}"!
  * Add soft glowing anime bokeh lights and smooth color aura gradients in matching treat colors.
  * DO NOT ADD floating food items, candies, toffee, or ice crystals.

4. PRODUCT & 1:1 SQUARE ASPECT RATIO CANVAS:
- Single "${selectedTreat.name}" ice cream bar on a wooden stick drawn in vivid 2D artwork style matching Image 2 reference.
- EXACT 1:1 SQUARE ASPECT RATIO: The output image MUST be generated in a 1:1 square format (equal width and height).
- 100% FULL BLEED: Complete edge-to-edge 2D animation artwork with ZERO white margins, zero pillarboxing, and zero letterboxing. Fill all 4 corners with background color and artwork!

${customInstruction}${treatImageReferenceNotice}`;

  onProgress?.('coloring');

  const candidateModels = ['gemini-2.5-flash-image', 'gemini-3.1-flash-image'];
  let lastError: any = null;

  // Build API contents: user photo + exact ice cream product reference photo
  const contentParts: any[] = [
    { text: generationPrompt },
    { inlineData: { mimeType, data: imageBase64 } },
  ];

  if (treatBase64) {
    contentParts.push({
      inlineData: { mimeType: 'image/png', data: treatBase64 },
    });
  }

  for (const modelName of candidateModels) {
    try {
      const response: GenerateContentResponse = await ai.models.generateContent({
        model: modelName,
        contents: {
          parts: contentParts,
        },
        config: {
          responseModalities: ['TEXT', 'IMAGE'],
        },
      });

      const parts = response.candidates?.[0]?.content?.parts;
      if (parts && parts.length > 0) {
        for (const part of parts) {
          if (part.inlineData?.data) {
            const outMime = part.inlineData.mimeType || 'image/png';
            const rawBase64 = `data:${outMime};base64,${part.inlineData.data}`;

            // Overlay wonder.png brand logo on bottom center of generated image
            try {
              return await overlayWonderLogo(rawBase64);
            } catch (logoErr) {
              console.warn('Could not overlay wonder logo:', logoErr);
              return rawBase64;
            }
          }
        }
      }

      const textOutput = response.text;
      if (textOutput) {
        throw new Error(`AI generated text response: "${textOutput.substring(0, 180)}..."`);
      }
    } catch (err: any) {
      lastError = err;
      console.warn(`Model ${modelName} call failed:`, err?.message || err);
    }
  }

  throw new Error(
    lastError?.message ||
      'Transformation failed. Please try again with a clear photo!'
  );
}

/** Overlay public/wonder.png logo seamlessly onto bottom center of generated image. */
async function overlayWonderLogo(imageBase64DataUrl: string): Promise<string> {
  const baseUrl = import.meta.env.BASE_URL || '/';
  const logoUrl = `${baseUrl}wonder.png`;

  return new Promise((resolve) => {
    const mainImg = new Image();
    mainImg.crossOrigin = 'anonymous';
    mainImg.src = imageBase64DataUrl;

    mainImg.onload = () => {
      const logoImg = new Image();
      logoImg.crossOrigin = 'anonymous';
      logoImg.src = logoUrl;

      logoImg.onload = () => {
        // Use exact original generated image dimensions to prevent any clipping/cutting of the character
        const canvas = document.createElement('canvas');
        canvas.width = mainImg.width;
        canvas.height = mainImg.height;
        const ctx = canvas.getContext('2d');

        if (!ctx) {
          return resolve(imageBase64DataUrl);
        }

        // Draw main anime image full frame without cropping
        ctx.drawImage(mainImg, 0, 0);

        // Calculate logo size: 50% of canvas width for bold prominent branding
        const logoWidth = canvas.width * 0.50;
        const logoHeight = logoWidth * (logoImg.height / logoImg.width);
        const x = (canvas.width - logoWidth) / 2;
        const y = canvas.height - logoHeight - canvas.height * 0.04; // 4% margin from bottom

        // Add subtle dark backdrop glow behind logo for readability
        ctx.save();
        ctx.shadowColor = 'rgba(0, 0, 0, 0.6)';
        ctx.shadowBlur = 12;
        ctx.shadowOffsetY = 4;

        ctx.drawImage(logoImg, x, y, logoWidth, logoHeight);
        ctx.restore();

        resolve(canvas.toDataURL('image/png'));
      };

      logoImg.onerror = () => resolve(imageBase64DataUrl);
    };

    mainImg.onerror = () => resolve(imageBase64DataUrl);
  });
}

