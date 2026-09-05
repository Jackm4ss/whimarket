# WhiMarket

> **Modern creator-driven commerce platform connecting audiences with authenticated pre-loved collections and exclusive merchandise from Indonesia's leading public figures.**

---

## Overview

WhiMarket is an e-commerce platform bridging top creators, artists, and streamers with their communities. The landing page is engineered with a mobile-first architecture, sub-millisecond interaction feedback, and strict adherence to brand typography, color harmonies, and responsive fluid layout grids.

---

## Key Highlights

- **Hero Experience**: Dynamic category highlights, trust metrics, verified creator endorsements, and handcrafted callouts.
- **Value Proposition**: 4-pillar trust architecture (Authenticity, Escrow Protection, Direct-from-Creator provenance, and Express Logistics).
- **Curated Discoverability**: Categorized exploration panels and trending product listings featuring authenticated creator badges.
- **Creator Social Proof**: Interactive creator directory with rating metrics and frictionless horizontal mobile swipe navigation.
- **Creator Onboarding Portal**: High-conversion acquisition section for prospective public figures and merchandisers.
- **Sequential Escrow Guide**: Visual step-by-step transaction flow featuring 3D clay-rendered assets and continuous responsive trace vectors.
- **Audience Retention**: Integrated newsletter dispatch module with fluid responsive container alignment.
- **Global Footer Navigation**: Comprehensive directory matrix, localization indicators, and official mobile platform store entries.

---

## Technical Stack

| Layer | Technology | Rationale |
| :--- | :--- | :--- |
| **Markup** | HTML5 Semantic Architecture | High accessibility compliance (WCAG) and SEO indexing. |
| **Styling** | Tailwind CSS Engine | Utility-first compilation, unified token registry, zero CSS bloat. |
| **Typography** | Plus Jakarta Sans & Caveat | Primary modern grotesque sans paired with organic handwriting accents. |
| **Runtime** | Vanilla JavaScript | Zero runtime dependency overhead; native event loops for scroll transitions and navigation drawers. |

---

## Design System Tokens

```css
/* Core Color Palette */
--brand-purple:       #4F26A6;
--brand-purple-deep:  #4F18C8;
--brand-banner:       #501EB4;
--brand-newsletter:   #5B27B5;
--brand-amber:        #F59E0B;
--brand-gold:         #FDBA2D;
--surface-bg:         #FAF9FC;
--surface-card:       #FFFFFF;
--text-primary:       #111827;
--text-muted:         #6B7280;
```

---

## Project Structure

```text
whimarket/
├── public/
│   ├── index.html              # Production single-page landing application
│   └── assets/                 # Optimized static media assets
│       ├── avatars/            # Verified creator portrait assets
│       ├── categories/         # Category showcase renders
│       ├── products/           # Featured product collection assets
│       └── steps/              # Sequential process 3D illustrations
├── .gitignore                  # Git VCS exclusions
└── README.md                   # System documentation & technical specifications
```

---

## Local Development

Deploy locally with any zero-configuration static file server:

### Python 3
```bash
python -m http.server 3000 --directory public
```

### Node.js / npx
```bash
npx serve public -p 3000
```

### Bun
```bash
bun x serve public -p 3000
```

Open `http://localhost:3000` in browser.

---

## License

All visual trademarks, brand identities, and proprietary asset illustrations belong to WhiMarket. Open-source code implementation available under the [MIT License](LICENSE).
