# Handoff: Adonis (Hair Focus)

## Overview

Adonis is a premium, physician-led men's & women's longevity / performance-medicine brand. The product is a hair-focused (FDA-approved finasteride + minoxidil and related formulas) and sexual-wellness telehealth storefront built on **WordPress + WooCommerce**, with a separate Prescribery integration handling clinical intake, physician review, and pharmacy fulfillment.

This handoff covers the marketing site (homepage, product pages), the WooCommerce cart/checkout/handoff flow, and two upper-funnel landing pages (Precision Care premium concierge, Lab Intelligence educational AI).

The visual identity is "v4.5" — a dark-luxury graphite + warm-gold palette for men, with a near-white paper + deep-purple counterpart for women. Editorial serif display (Fraunces) over a humanist mono UI face (JetBrains Mono / IBM Plex Mono).

---

## About the Design Files

The HTML files in this bundle are **design references** — high-fidelity prototypes built in plain HTML/CSS/JS to communicate look, feel, copy, structure, and interaction patterns. **They are not production code to copy directly.**

The implementation task is to **rebuild these designs in the target codebase's environment**:
- **WordPress** for the marketing site (homepage, Precision Care, Lab Intelligence) — likely using a custom block theme with reusable Gutenberg blocks. Tailwind or vanilla CSS with the token system below; do not bring in a heavy JS framework on the public marketing surface.
- **WooCommerce** for product, cart, checkout, and account flows — override Woo templates rather than rebuilding the cart from scratch. The HTML files show the *intended visual* of standard Woo touchpoints.
- **Prescribery** (external, out of scope for the WP build) handles the clinical workflow after handoff. The cart/checkout file shows where Adonis ends and Prescribery begins.

If the developer is working in a different stack (Next.js, Astro, etc.) the same designs apply — recreate the visual system using whatever the host environment provides.

---

## Fidelity

**High-fidelity (hifi).** Every color, font, spacing value, border radius, and copy block in these files is intentional and should be reproduced exactly. The token table below is the source of truth; if anything in the HTML disagrees with the README, the README wins.

Mobile views are also hifi — they're shown inside an iPhone-style frame in each file (toggle via the **Tweaks** panel → View → Mobile). Every section has a mobile counterpart.

Each HTML file also contains a **WP/WooCommerce Implementation Notes** view (Tweaks → View → Notes) with section-by-section guidance on how to structure the WP/Woo build. Read those before touching the codebase.

---

## Screenshots

Reference images of every page, every view. See `screenshots/` folder.

**Homepage (`01-homepage.html`)**
- `01-homepage-men-top.png` — hero, men's theme
- `01-homepage-men-pathways.png` — Express / Precision / Lab Intelligence 3-up
- `01-homepage-men-products.png` — featured products grid
- `01-homepage-men-howitworks.png` — 5-step timeline
- `01-homepage-men-physicians.png` — physician credibility grid
- `01-homepage-men-faq.png` — FAQ section
- `01-homepage-women-top.png` — same hero, women's theme (paper bg + purple accent)

**Product page (`02-product-page.html`)** — single template, two products
- `02-product-desktop-hair.png` — desktop view
- `02-product-mobile.png` — iPhone-frame mobile
- `02-product-notes.png` — WP/Woo implementation notes

**Cart + Checkout (`03-cart-checkout.html`)**
- `03-cart-desktop.png` / `03-cart-mobile.png`
- `03-checkout-desktop.png` / `03-checkout-mobile.png`
- `03-thankyou.png` — Prescribery handoff (desktop + mobile pair)
- `03-cart-checkout-notes.png` — WC + Prescribery integration notes

**Precision Care (`04-precision-care.html`)**
- `04-precision-desktop.png` / `04-precision-mobile.png` / `04-precision-notes.png`

**Lab Intelligence (`05-lab-intelligence.html`)**
- `05-lab-desktop.png` / `05-lab-mobile.png` / `05-lab-notes.png` — read the notes for compliance/AI guardrails

---

## Files in this bundle

| File | What it is |
|---|---|
| `01-homepage.html` | Marketing homepage — hero, men/women toggle, pathways, conditions, products, before/after, physicians, FAQ, footer |
| `02-product-page.html` | Single template covering both Hair (Finasteride+Minoxidil) and ED (Tadalafil) variants — toggle via Tweaks |
| `03-cart-checkout.html` | Cart, checkout, and order-confirmation / Prescribery handoff screens |
| `04-precision-care.html` | `/precision-care` landing — $499 concierge intake (full panel + physician + plan) |
| `05-lab-intelligence.html` | `/lab-intelligence` landing — educational AI (upload free / $199 panel / upgrade to Precision) |
| `00-brand-directions-explored.html` | Earlier exploration of three brand directions; **direction A was selected**. Reference only — do not implement. |

Every page-level file has three views you can toggle in the **Tweaks** panel (top-right of the prototype):
- **Desktop** — 1440 design width
- **Mobile** — same content, iOS-frame, 390×844
- **Notes** — implementation guidance (WP block structure, Woo product setup, Prescribery integration points, compliance copy, etc.)

---

## Design Tokens

Set these as CSS custom properties on `:root` (or Tailwind theme extensions). The site has **two themes**: men (dark / default) and women (light). They are toggled by adding `body.men` or `body.women` (with `body.neutral` as a fallback that uses men's palette by default).

### Color — Men (Dark / Default)

```css
--m-bg:        #0F1116;   /* graphite with a hint of navy — primary background */
--m-bg2:       #181B22;   /* elevated surfaces, cards */
--m-fg:        #ECE6D8;   /* warm off-white — body text */
--m-mute:      #898577;   /* secondary text, idx labels */
--m-line:      rgba(236, 230, 216, 0.10);   /* hairline borders */
--m-line2:     rgba(236, 230, 216, 0.18);   /* stronger borders */
--m-acc:       #C9A35E;   /* warm desaturated gold — single accent */
--m-acc2:      #C9A35E;   /* italics + emphasis (matches --acc in v4.5) */
--m-acc-deep:  #8E7038;   /* hover/pressed accent */
--m-secondary: #5A2A2A;   /* deep oxblood — used sparingly */
--m-chart:     #B58A4D;   /* chart fills */
```

### Color — Women (Light)

```css
--w-bg:        #FBFAF7;   /* near-white paper */
--w-bg2:       #F3F1EC;   /* elevated surfaces */
--w-fg:        #1F1D1A;   /* near-black ink */
--w-mute:      #7A736A;
--w-line:      rgba(31, 29, 26, 0.12);
--w-line2:     rgba(31, 29, 26, 0.22);
--w-acc:       #533673;   /* deep purple — women's signature */
--w-acc2:      #533673;
--w-acc-deep:  #3C2655;
```

Theme switch is one CSS rule per token: `body.women { --bg: var(--w-bg); ... }` etc. The HTML files in this bundle implement the full mapping — copy the `:root` block from `01-homepage.html` for the canonical version.

### Typography

```css
--display: "Fraunces", "Cormorant Garamond", Georgia, serif;
--mono:    "JetBrains Mono", "IBM Plex Mono", "SF Mono", ui-monospace, monospace;
--sans:    "Inter Tight", system-ui, sans-serif;   /* fallback for body if mono is too dense */
```

- **Headings** → `--display`, weight 300–500. Italics in headings use `--acc2` and signal emphasis (e.g. *italic word* in "Built for *performance*").
- **Body** → `--mono` at 14–16px, line-height 1.55–1.7. The mono is set to `font-feature-settings: "tnum"` for tabular numerals.
- **Idx labels** (the small uppercase tags at the top of each section like `01 · Hero`) → `--mono`, 11px, `letter-spacing: 0.16em`, `text-transform: uppercase`, `color: var(--mute)`.

> **Mono uppercase is reserved for idx labels and chips only.** Buttons, body, headings, nav, and form labels are sans (Inter Tight) or display (Fraunces). If you find yourself reaching for `--mono` + `text-transform: uppercase` outside of idx labels and chips, stop — it's almost certainly the wrong choice.

Type scale (clamp-based, fluid):
```
H1 hero      clamp(56px, 8vw, 132px)   — Fraunces 300, line-height 0.96
H2 section   clamp(44px, 6vw, 96px)    — Fraunces 300, line-height 1.02
H3           clamp(28px, 3vw, 44px)    — Fraunces 400
H4           20–24px                    — Fraunces 500
Body         15–16px                    — mono 400, line-height 1.6
Small / meta 12–13px                    — mono 400, letter-spacing 0.04em
```

### Spacing

8px-based scale: `4 · 8 · 12 · 16 · 24 · 32 · 48 · 64 · 96 · 128 · 160`. Section vertical padding is generous — typical `padding: 120px 0` desktop, `64–80px` mobile.

Page max-width: **1400px**, gutter `40px` desktop & tablet / `20px` mobile. Hero is full-bleed; everything else is constrained.

### Border radius

```
--r-sm: 4px      /* chips, badges */
--r-md: 6px      /* cards, inputs, buttons (the v4.5 standard) */
--r-lg: 12px     /* large feature cards (used sparingly) */
```

The v4.5 system is **deliberately low on radius** — most surfaces are 6px. Resist the urge to round more.

### Shadows

Almost none. The dark theme uses **borders, not shadows**, for depth. The one exception is sticky checkout/summary cards which get `box-shadow: 0 8px 32px rgba(0,0,0,0.4)`.

### Buttons

Buttons are **pills** — Inter Tight 14px, sentence/title case, no uppercase. The mono uppercase treatment is for idx labels and chips only.

```css
.btn {
  font-family: var(--sans);    /* Inter Tight */
  font-size: 14px;
  font-weight: 500;
  letter-spacing: 0;
  text-transform: none;
  padding: 12px 24px;
  border-radius: 999px;        /* full pill */
  display: inline-flex;
  align-items: center;
  gap: 10px;
  line-height: 1;
  transition: background .25s, color .25s, border-color .25s;
  white-space: nowrap;
}

.btn.accent {            /* primary CTA */
  background: var(--fg);
  color: var(--bg);
  border: 1px solid var(--fg);
}
.btn.accent:hover { background: transparent; color: var(--fg); }

.btn.ghost {             /* secondary */
  background: transparent;
  color: var(--fg);
  border: 1px solid var(--line2);
}
.btn.ghost:hover { border-color: var(--fg); }
```

The italicized accent word inside a CTA (e.g. "Get *started*") uses `<em style="color: var(--acc)">` and is part of the brand voice — preserve it. Italics inside a pill stay sentence-case Inter Tight, italicized — not mono.

---

## Page-by-page Specs

### 01 · Homepage (`01-homepage.html`)

**Sections (top to bottom, with `data-screen-label` values)**
1. Hero — H1 with italic accent word, sub-copy, two CTAs, full-bleed treatment chart
2. Differentiation — chips comparing Adonis to generic telehealth
3. Conditions — two pillars (Hair / Sexual wellness) with biomarker SVG
4. Pathways — 3-up: Express ($89/mo) · Precision ($499) · Lab Intelligence (free upload)
5. Lab Intelligence preview — full-width biomarker chart
6. Featured Products — 4 cards with formulation, mg, monthly price
7. How It Works — 5-step timeline
8. Before / After Gallery — 4 patient pairs with N-of timeframe
9. Physicians — 4-card credibility grid
10. Trust + service area — chips of certifications + states
11. FAQ — 6 expanding rows + lede
12. Checkout Preview — sample order summary
13. Footer CTA + footer

**Men/Women toggle** is in the nav (`<button data-gender="men">`/`women`). It swaps a body class. Some copy is gender-conditional via `<span class="g-men">` / `<span class="g-women">` / `<span class="g-neutral">` — visibility is controlled by CSS, NOT JS rewrites of innerText. Preserve this pattern.

### 02 · Product Page (`02-product-page.html`)

**One template, two products.** Toggle in Tweaks → Product. The data layer (in the file's `<script>`) holds Hair (Finasteride 1mg + Minoxidil 5%) and ED (Tadalafil 5mg) entries. When ported to Woo, these are two products sharing one PHP template.

**Sections**: notice strip → nav (with breadcrumb that updates) → product hero (image carousel left, info right with formulary, frequency selector, price by cadence, primary CTA) → "what's in it" formulation breakdown → How It Works (4-step) → before/after specific to the indication → physician card (single-doctor variant) → safety + side effects accordion → FAQ → trust strip → footer.

The frequency selector (Monthly / Quarterly / Annual) updates the price live and applies a saved % badge — implement as Woo subscription variants.

### 03 · Cart + Checkout (`03-cart-checkout.html`)

**Five views via Tweaks → View**:
1. Cart — desktop
2. Cart — mobile
3. Checkout — desktop (sticky right-rail summary)
4. Checkout — mobile
5. Thank-you / Prescribery handoff — both

**Critical**: Adonis collects shipping + payment + auth. **Clinical intake happens AFTER checkout, on Prescribery.** The thank-you screen is the handoff — it tells the user "your consultation starts in minutes" and links them to Prescribery. The notes view explains the webhook architecture.

### 04 · Precision Care (`04-precision-care.html`)

`/precision-care` landing for the $499 concierge product. Full panel labs (no upload — Adonis-ordered) + 30-min physician video + 90-day plan. Hero, what's included, panel breakdown, sample report, physician bios, comparison vs Express, FAQ, CTA.

### 05 · Lab Intelligence (`05-lab-intelligence.html`)

`/lab-intelligence` educational AI landing. **Compliance is the most sensitive part of this page** — read the "AI does/doesn't" card and the banned-language list in the notes view before writing any copy. v1 is **deterministic insights only** (no LLM on raw lab values). Lab credit toward Precision Care is baked into the bridge UX.

---

## Interactions & Behavior

### Theme toggle (men/women)
- `<button data-gender="men">` and `<button data-gender="women">` swap `body.men` / `body.women` classes.
- Persist choice to `localStorage`.
- All token swaps are pure CSS — no re-render needed.
- Some text content uses `.g-men` / `.g-women` / `.g-neutral` selectors that are shown/hidden via CSS (`display: inline` / `display: none`).

### Frequency / cadence selector (product page)
- Three buttons (Monthly / Quarterly / Annual). Clicking updates the displayed price and badge. The active state uses `border-color: var(--fg)` and `color: var(--fg)`; inactive uses `border-color: var(--line2)`.

### FAQ accordion (homepage + product + landings)
- `<details>` / `<summary>` semantically. The `+` rotates 45° to `×` when open. Smooth height transition — Woo theme should use the same pattern (no JS framework needed).

### Image carousel (product page)
- Left/right thumbnail strip + main image. Click thumb to swap. No swipe on desktop — keyboard arrows ok. Mobile is horizontal scroll-snap.

### Cart quantity stepper
- −/+ buttons, no free-text input. Min 1, max 12. Updates subtotal live (debounced 200ms in production for cart updates).

### Checkout sticky summary
- Right rail position-stickys at `top: 24px` on desktop. On mobile, summary collapses to a sticky **bottom** bar showing just total + "Continue".

### Hover / focus
- Links: `text-decoration: underline` on hover, `text-underline-offset: 4px`.
- Cards: subtle border lift (`border-color: var(--line2)` → `var(--fg)` at 0.4 opacity) + 1px translateY.
- Buttons: see button rules above. Always include a focus ring — `outline: 2px solid var(--acc); outline-offset: 2px`.

### Animations
Keep them quiet. Standard: `transition: 0.25s cubic-bezier(0.2, 0, 0, 1)` on color / border / transform. No on-scroll reveals. No parallax. The brand voice is *clinical, measured* — animation should reinforce that.

### Responsive breakpoints

Two breakpoints, in this order:

```
Mobile:  ≤  720px   — single column, stacked CTAs, sticky bottom bar, 20px gutter
Tablet:    721–900  — 2-column grids reduce to 1, 40px gutter
Desktop:   901+     — full grid, 1400px max content width, 40px gutter
```

Use `@media (max-width: 900px)` for the tablet collapse and `@media (max-width: 720px)` for the mobile collapse. The earlier prototypes used `1024 / 640`; the production spec is `900 / 720`. If the HTML and the README disagree, the README wins — update the HTML to match.

---

## State Management

For the WP/Woo build, most state lives server-side or in the standard Woo session:
- **Cart** — Woo session
- **Auth** — Woo customer
- **Selected gender theme** — `localStorage` only (visual preference, not account-level)
- **Selected cadence** on product page — local component state, submitted as Woo variation on add-to-cart
- **FAQ open/closed** — DOM only, no persistence

For the Lab Intelligence flow:
- **Uploaded labs** — S3 with HIPAA-safe pipeline (see notes view in file 05)
- **Insights cache** — server-side keyed by user + lab document hash

---

## Copy Guidelines

The brand voice is in every HTML file — copy the exact strings, don't paraphrase. Some style notes:

- **Sentence case** for headlines, `Title Case` for nav links and CTAs.
- **Italics** mark the emphasized concept ("Built for *performance*", "Your consultation starts in *minutes*"). Always wrap in `<em>` and color with `--acc2`.
- **Hyphens, not em-dashes**, in body copy. (Em-dashes used only in pull quotes.)
- **Numerals over words** for dosages, prices, timeframes.
- **No emoji.** Anywhere.
- **No exclamation points.** The brand is calm.
- **Avoid "your"** in headlines unless it's a direct CTA. Prefer the noun. ("Your hair" → "The hair") — see homepage hero for cadence.

---

## Compliance / Required Copy

These strings MUST appear and must not be reworded without a legal review pass:

- "FDA-approved finasteride and minoxidil." (homepage, product, footer)
- "Educational view · not a diagnosis." (any biomarker visualization)
- "Treatment is prescribed by a licensed physician based on your individual evaluation." (homepage trust strip, every product page)
- Lab Intelligence v1: **never** the strings "AI doctor", "AI diagnosis", "AI prescription", "AI treatment plan", "guaranteed optimization". The notes view in file 05 has the full banned list.
- Footer must include the medical disclaimer block verbatim.

---

## Assets

The HTML files use **only** inline SVG (charts, biomarker rows, icons) and CSS gradients for imagery. There are **no raster product images, no photos, no logos.** Placeholders should be replaced with real assets at implementation time:

- **Product images** — 4–6 per SKU, square 1:1, dark backdrop, the bottle/blister at 65–70% of frame. The HTML uses a placeholder gradient block.
- **Before/after photos** — the homepage gallery has 4 pairs as placeholder boxes. Real photos must be model-released and labeled with N-of timeframe (e.g. "N=1 · 6 months").
- **Physician headshots** — 4 on the homepage, 1 detail on the product page. Square crop, neutral backdrop matching brand bg2.
- **Logo** — wordmark only, set in Fraunces 300 italic at hero scale. The "Adonis" string in the nav IS the logo at small size; recreate it as inline SVG for the production build so it has consistent metrics across browsers.

Fonts are loaded from Google Fonts in the prototype:
- Fraunces (300, 400, 500) with `opsz` axis if possible
- JetBrains Mono (400, 500)

For production, self-host both via `@font-face` to avoid the Google Fonts third-party fetch (also a GDPR consideration).

---

## Files to reference in this bundle

- `01-homepage.html` — homepage, all 12 sections + men/women toggle. Token block in lines 20–60 is the source of truth.
- `02-product-page.html` — product detail + Tweaks-driven Hair/ED variant.
- `03-cart-checkout.html` — full Woo flow + Prescribery handoff.
- `04-precision-care.html` — concierge landing.
- `05-lab-intelligence.html` — educational AI landing with compliance guardrails in the notes view.
- `00-brand-directions-explored.html` — historical exploration. Reference only.

For each file, **open it in a browser, then open the Tweaks panel (top-right) and cycle through the View options** to see all three states (Desktop / Mobile / Notes). The Notes view is the per-page implementation guide.

---

## Recommended build order

1. **WordPress block theme scaffold** — base styles, token CSS, type scale, button & card primitives. Get the men/women theme switch working first; it touches everything.
2. **Homepage** — most reusable blocks come from here. Build the blocks (hero, panel, pathway-card, condition-pillar, product-card, faq-accordion, before-after-pair) and use them.
3. **Product page template** — single PHP template, two Woo products. Subscription variations for cadence.
4. **Cart + Checkout** — Woo template overrides, not from-scratch. Match the visual exactly; preserve all Woo hooks.
5. **Thank-you + Prescribery handoff** — webhook + redirect. See file 03 notes.
6. **Precision Care landing** — standalone page, reuses homepage blocks.
7. **Lab Intelligence landing** — standalone page; v1 ships **without** the AI inference layer (deterministic only). Read file 05 notes carefully.

---

## Resolved decisions

The previous "open questions" list has been resolved. These are the binding decisions for v1 — implement to these specs.

### Placeholders for v1 launch

- **Physicians** — Build the 4-card grid with the existing copy and 4 dummy headshot slots. Real photography + bios swap in before public launch.
- **Before/after gallery** — Build the component but render skeleton boxes with the N-of timeframe label visible (e.g. `N=1 · 6 months · photo pending`). Real photos arrive in v1.1.
- **Logo** — Text wordmark only for v1: "Adonis" set in Fraunces 300 italic at nav scale, inline SVG with explicit `width` / `height`. Commissioned wordmark lands in v1.1.

### Typography & fonts

- **Self-host** Fraunces and JetBrains Mono. Generate `woff2` subsets and `@font-face` declarations locally. **No Google Fonts CDN**, including in dev. (The current `wp-theme/functions.php` uses Google Fonts as a temporary scaffold — replace before any environment that's not localhost.)

### Brand copy (locked — do not paraphrase)

- **Top bar:** `FL-Licensed Physicians · Rx review in 24h` — real launch copy.
- **Nav IA:** `Treatments` / `Science` / `Standards` / `Log in` / `Get started`.
- **Cart line-item helper:** `Edit dose, switch to one-time, or cancel anytime in your member portal.` Drop the "Most members choose…" line — too sales-y for the brand.
- **Non-FL checkout fallback:** `We'll be in your state soon — join the list` with email capture (not a hard error).

### Compliance — Lab Intelligence

- Assume **legal sign-off is not yet obtained**. Implement the "does / doesn't" card and banned-phrase guidance exactly as written.
- **All Lab Intelligence copy lives in ACF fields** so legal can edit without a deploy.
- **CI lint** fails the build if any of these strings appear (case-insensitive) in committed copy or in ACF values pulled at build time:
  - `AI doctor`
  - `AI diagnosis`
  - `AI prescription`
  - `AI treatment plan`
  - `guaranteed optimization`

### Payment & subscriptions

- **Adonis owns Stripe auth.** Stripe lives on the WP site — **no Stripe Connect.**
- **Auth-then-capture flow:** authorize at checkout. Capture only after the Prescribery webhook returns `approved`. On `declined`, void the auth and email the customer.
- **Subscriptions:** the official **WooCommerce Subscriptions** plugin. Cadence (Monthly / Quarterly / Annual) is modeled as subscription variations.

### Geography & tax

- **Service area at launch: Florida only.** Gate checkout on `billing_state == FL`. Non-FL traffic sees the waitlist capture (copy above) instead of a hard error.
- **Tax rates:** FL 6% state + Miami-Dade 1% county. Configure both as Woo tax rates.

### Inventory

- **Precision Care stock** is toggled by an admin user via the standard WC stock-status field. Expose a custom dashboard widget on `/wp-admin` with one switch — `Accepting new Precision visits — yes/no` — wired to that toggle. No separate booking system.

### Account & order flow

- **Mobile checkout sticky bar:** total + `Continue` only. No payment-method icons, no extra trust copy on the bar (those live above it).
- **Order ID format:** `AD-YYYY-MM-NNNNN` — generated via a post-meta override on the `woocommerce_new_order` action. No third-party plugin.
- **Member portal:** stock WC My Account, themed to v4.5. Override Woo templates rather than building a custom account system. Three custom endpoints:
  - `Subscriptions` — already provided by WC Subscriptions
  - `Lab Uploads` — custom (S3 + HIPAA-safe pipeline; see file 05 notes)
  - `Insights` — custom (deterministic insights cache)

