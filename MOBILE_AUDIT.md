# Mobile Responsive Audit — fab-sourcing.fr
**Date:** 2026-05-20
**Viewports analysed:** 375px (iPhone SE/13 mini), 390px (iPhone 14/15), 768px (iPad portrait)
**Method:** Static analysis of all SCSS partials and Blade views. No browser rendering.

---

## Stack Discovery

| Item | Value |
|---|---|
| CSS framework | Custom SCSS, no Tailwind/Bootstrap |
| SCSS entry | `resources/sass/web.scss` (imports all partials) |
| Build tool | Laravel Mix (webpack) — `webpack.mix.js` |
| JS | Vanilla JS only (no jQuery) |
| Font | Inter (sans) + JetBrains Mono — loaded via layout |

### Breakpoints

| Variable | Value | Used for |
|---|---|---|
| `$bp-mobile` | 640px | Cards, timeline, callouts |
| `$bp-tablet` | 800px | Section heads, grids, footer |
| `$bp-nav` | 900px | Navigation, hero, most layouts |
| `$bp-desktop` | 1024px | Defined, unused in current CSS |

**Observation:** Three effective breakpoints collapse most layouts from desktop to tablet to mobile. One custom value (`700px` in `_forms.scss:47`) falls outside this system.

### Typography scale

All major headings use `clamp()` — responsive without breakpoints:

| Class | Formula | Min (375px result) | Max |
|---|---|---|---|
| `.h-display` | `clamp(56px, 8vw, 112px)` | 56px | 112px |
| `.h-1` | `clamp(40px, 5.2vw, 68px)` | 40px | 68px |
| `.h-2` | `clamp(30px, 3.6vw, 44px)` | 30px | 44px |
| `.h-3` | `clamp(22px, 2.2vw, 26px)` | 22px | 26px |
| `.hero-a-headline` | `clamp(56px, 6.8vw, 66px)` | **56px** | 66px |
| `.stat-value` | `clamp(40px, 4vw, 56px)` | 40px | 56px |
| `.lede` | `clamp(17px, 1.4vw, 20px)` | 17px | 20px |

Body text is fixed: `.body` 16px, `.body-sm` 14px, `.h-4` 17px (`_typography.scss:78, 90–91`).

### Container & gutter

- `$gutter: 32px` (`_variables.scss:30`) — applied as `padding: 0 $gutter` on `.container` and `.container-wide`, **no responsive override anywhere**
- At 375px: content width = 375 − 64 = **311px**
- At 320px: content width = 320 − 64 = **256px**

### overflow-x

`html, body { overflow-x: hidden }` — set in `web.scss`. Functional but a band-aid (see Nice-to-have #1).

---

## Findings by Severity

---

### 🔴 Critical (breaks layout / unreadable)

---

#### C1 — Nav: Logo too tall + CTA button not hidden on mobile

- **Affects:** All pages (global nav)
- **Files:** `_nav.scss:35–38`, `partials/nav.blade.php:6–8, 53–56`
- **Viewport:** 375px, 390px
- **Issue:** `.brand-logo` has `height: 60px; flex-shrink: 0` with no mobile size reduction. The nav padding is 18px top/bottom (`_nav.scss:18`), making the nav bar ~96px tall — nearly 26% of a 375px viewport height just for the header. Additionally, the "Devis gratuit" CTA button inside `.nav-right` has no `display: none` at any breakpoint (only `.nav-links` and `.nav-phone` are hidden at `$bp-nav`). At 375px the nav-right contains: [CTA button] + [hamburger 40px], and the logo is `flex-shrink: 0`. Depending on logo image width, this combination can cause horizontal overflow or extreme compression.
- **Why critical:** A 96px fixed nav bar on a 375px screen uses 25% of the viewport before content begins. If the logo image is wide (typical for horizontal wordmarks), the nav layout breaks at the narrowest viewports.
- **Suggested fix direction:** Reduce logo to ~40px on mobile, hide the "Devis gratuit" CTA button below `$bp-nav` (the mobile drawer already contains a contact path).

---

#### C2 — Footer grid never collapses to single column

- **Affects:** All pages (global footer)
- **Files:** `_sections.scss:632–640`, `partials/footer.blade.php:4–58`
- **Viewport:** 375px, 390px (all widths below 800px)
- **Issue:** `.footer-grid` collapses from `1.5fr 1fr 1fr 1fr` to `1fr 1fr` at `$bp-tablet` (800px) — `_sections.scss:639`. There is no further breakpoint to go to single-column. At 375px with 32px gutters, each column is `(311px − 48px gap) / 2 = 131px`. Inside the Contact column, `.footer-person-avatar` is `width: 120px; height: 120px` (`_sections.scss:652–655`) — a 120px image in a 131px column leaves 11px of margin. The footer tagline in the brand column has `max-width: 38ch` at 14px font, which at 131px wraps to ~8–9 lines, making the footer excessively tall and cramped.
- **Why critical:** The footer is unreadable at mobile widths. The 120px avatar in a 131px column is borderline broken, and the text wrapping makes the footer 3–4× taller than necessary.
- **Suggested fix direction:** Add a single-column breakpoint at `$bp-mobile` (640px) or `$bp-tablet` for the footer grid, and reduce `.footer-person-avatar` to ~64–80px on mobile.

---

### 🟡 Important (looks unpolished but functional)

---

#### I1 — Hero headline minimum 56px on all mobile

- **Affects:** Home page
- **Files:** `_hero.scss:70`
- **Viewport:** 375px, 390px
- **Issue:** `.hero-a-headline` uses `clamp(56px, 6.8vw, 66px)`. At 375px, `6.8vw = 25.5px`, so the clamp **floor of 56px applies for all phones**. A 56px bold heading on 311px content is ~4 short words per visual line. The hero section then stacks the image below (column layout at 900px) with `aspect-ratio: 4/3` — on 375px that image is 311px × 233px. Combined, the hero section can occupy 600–700px before any other content appears, forcing significant scrolling to reach the stat ribbon.
- **Why important:** The above-the-fold experience on mobile shows only the headline and part of the hero image. The CTA buttons are below the fold on iPhone SE.
- **Suggested fix direction:** Lower the clamp floor to 36–40px for the hero headline specifically on mobile, allowing more content above the fold.

---

#### I2 — Comparison table requires horizontal scroll on mobile

- **Affects:** Pourquoi l'Est (`/pourquoi-est`)
- **Files:** `_sections.scss:1093–1121`, `web/why.blade.php` (comparison table block)
- **Viewport:** 375px, 390px
- **Issue:** `.comparison-wrap` has `overflow-x: auto` (`_sections.scss:1093`). The 3-column table has `white-space: nowrap` on headers (`_sections.scss:1114`), preventing header text from wrapping. There is no media query to collapse the table structure (e.g. stack rows, hide columns, or convert to definition lists). On 375px, the user must pinch-scroll horizontally to read the Asie column. The table is the key conversion argument on this page.
- **Why important:** Horizontal scroll on tables is a known mobile UX failure pattern. The "Asie" comparison is the core argument of the page — hiding it behind a scroll gesture reduces its persuasive impact.
- **Suggested fix direction:** Convert to a vertically-stacked structure below `$bp-mobile`, or use a scrollable table with a visible scroll indicator and sticky first column.

---

#### I3 — Container gutter unchanged on mobile

- **Affects:** All pages
- **Files:** `_variables.scss:30`, `_layout.scss:5–9`
- **Viewport:** 375px, all narrow
- **Issue:** `$gutter: 32px` is applied to `.container` and `.container-wide` as `padding: 0 32px` with no responsive override. This is generous on desktop but on 375px reduces content width to 311px (83%). On 320px (older iPhones, some Android) it reduces to 256px (80%). While not broken, 32px gutters are excessive on small screens where 16px is the standard minimum.
- **Why important:** Tight content area makes text-heavy sections (about, method steps, legal pages) feel cramped and reduces readability.
- **Suggested fix direction:** Reduce gutter to 16–20px below `$bp-mobile` using a responsive override on `.container`.

---

#### I4 — Footer bottom legal links: inner flex row has no wrap

- **Affects:** All pages (global footer)
- **Files:** `partials/footer.blade.php:63–77`
- **Viewport:** 375px, 390px
- **Issue:** The `.footer-bottom` correctly stacks to `flex-direction: column` at `$bp-tablet` (`_sections.scss:714`). However, the right side of footer-bottom is an inline `<div style="display:flex; gap:20px; align-items:center">` containing three items: "Mentions légales", "Confidentialité", and "fab-sourcing.fr". At 375px, this inner row renders as a single flex line with no `flex-wrap: wrap`. Estimated width: ~280–300px. At 311px content width this barely fits or overflows depending on font rendering.
- **Why important:** Legal links overflowing or being clipped violates accessibility/legal requirements in FR.
- **Suggested fix direction:** Add `flex-wrap: wrap` and `justify-content: center` to the inner links div, or move the links to a dedicated CSS class with proper mobile treatment.

---

#### I5 — No `srcset` on any images

- **Affects:** Home (hero, category cards), Produits (category cards), Pourquoi l'Est (feature image), About (team photo)
- **Files:** `web/home.blade.php`, `components/cat-card.blade.php`, `web/why.blade.php`, `web/about.blade.php`
- **Viewport:** All mobile
- **Issue:** All `<img>` tags use a single `src` with no `srcset` or `sizes` attributes. Category images are stored at multiple resolutions (e.g. `escaliers-metalliques-1778834541.jpg`, `..._medium.jpg`, `..._thumb.jpg`) in `storage/app/public/categories/` but only the original full-size is served.
- **Why important:** Mobile devices download full-resolution images unnecessarily, increasing load time on cellular connections. The thumb variants already exist in storage.
- **Suggested fix direction:** Add `srcset` pointing to `_medium` and `_thumb` variants on category/service cards; the `ProductCategory` model already stores these paths.

---

#### I6 — Drawer close button below Apple HIG touch target minimum

- **Affects:** All pages (mobile nav)
- **Files:** `_nav.scss:182–194`
- **Viewport:** 375px, 390px
- **Issue:** `.nav-drawer-close` is `width: 36px; height: 36px` — 4px below Apple HIG's 44×44px minimum for touch targets. The hamburger `.mobile-menu-btn` is correctly 40×40px (`_nav.scss:127–128`) — still 4px below HIG but borderline acceptable.
- **Why important:** Small close button increases mis-tap rate, especially for users with larger fingers or motor impairments.
- **Suggested fix direction:** Increase `.nav-drawer-close` to 44×44px.

---

### 🟢 Nice-to-have (polish, optional)

---

#### N1 — `overflow-x: hidden` on html/body is a band-aid

- **Affects:** All pages
- **Files:** `resources/sass/web.scss` (html, body rules)
- **Viewport:** All
- **Issue:** `overflow-x: hidden` on the root elements masks any horizontal overflow rather than fixing its source. If any element genuinely overflows (e.g. due to future content changes), it will be silently clipped rather than flagged.
- **Suggested fix direction:** Identify and fix any actual overflow sources; remove `overflow-x: hidden` from the root, or apply it only to specific containers where overflow is expected.

---

#### N2 — Section padding floor 72px is tall on mobile

- **Affects:** All pages (all `.section` elements)
- **Files:** `_layout.scss:17`
- **Viewport:** 375px
- **Issue:** `.section { padding: clamp(72px, 9vw, 128px) 0 }`. At 375px, `9vw = 33.75px` so the **floor of 72px applies on all phones**. Multi-section pages like Home and Why render 144px of vertical whitespace between sections (72px bottom + 72px top). For a page with 5–6 sections, that's 720px of padding alone — more than the entire viewport height.
- **Suggested fix direction:** Lower the floor to 40–48px for mobile; `clamp(40px, 9vw, 128px)` would reduce mobile padding while preserving desktop spacing.

---

#### N3 — `form-row` collapses at custom 700px breakpoint

- **Affects:** Contact page (`/contact`)
- **Files:** `_forms.scss:47`
- **Viewport:** 701–800px (iPad landscape / small tablets)
- **Issue:** `.form-row { @media (max-width: 700px) { grid-template-columns: 1fr } }` uses a hardcoded value not from the design system's breakpoint variables. Between 640px (`$bp-mobile`) and 700px, the form shows a 2-column layout that is narrower than intended. Between 700px and 800px (`$bp-tablet`), the form is 2-column when the section-head above it is already 1-column — an inconsistency.
- **Suggested fix direction:** Replace `700px` with `$bp-tablet` (800px) for consistency with all other layout breakpoints.

---

#### N4 — Method step description fixed at 15px

- **Affects:** Méthode page (`/methode`)
- **Files:** `_sections.scss:306`
- **Viewport:** 375px
- **Issue:** `.method-step-desc { font-size: 15px }` — fixed size with no responsive scaling. On very small viewports the 7-step descriptions (which can be 1–2 sentences each) are slightly smaller than the `.body` class (16px). Minor inconsistency in the type scale.
- **Suggested fix direction:** Change to `font-size: 16px` to match `.body`, or use `clamp()` consistent with the rest of the system.

---

## Findings by Page

### Home (`/`)
| Issue | Severity |
|---|---|
| Hero headline 56px minimum on mobile | 🟡 I1 |
| No srcset on hero and category card images | 🟡 I5 |
| Section padding 72px floor between all sections | 🟢 N2 |

### Services (`/services`)
| Issue | Severity |
|---|---|
| Container 32px gutter on all viewports | 🟡 I3 |
| Section padding 72px floor | 🟢 N2 |
- **Note:** Service card grid (`_cards.scss:11`) collapses correctly to 1-column at 900px. Service cards with `col-7/col-5` spans all reset to `span 1` at mobile. No specific issues with this page beyond the global ones.

### Produits — Catalogue (`/produits`)
| Issue | Severity |
|---|---|
| No srcset on category card images | 🟡 I5 |
| Container 32px gutter | 🟡 I3 |
- **Note:** `.cat-grid` has a proper 3-col → 2-col → 1-col progression (`_cards.scss:224–225`). `.products-grid` similarly (`_cards.scss:95–96`). Both well handled.

### Pourquoi l'Est (`/pourquoi-est`)
| Issue | Severity |
|---|---|
| Comparison table horizontal scroll only — no structural collapse | 🟡 I2 |
| Container 32px gutter | 🟡 I3 |
| Section padding 72px floor | 🟢 N2 |
- **Note:** `.advantage-grid` collapses to 1-col at 800px (`_sections.scss:972`). `.why-stat-callout` stacks at 640px (`_sections.scss:1050`). Well handled.

### Méthode (`/methode`)
| Issue | Severity |
|---|---|
| Method step description fixed 15px | 🟢 N4 |
| Container 32px gutter | 🟡 I3 |
- **Note:** Timeline is the strongest mobile implementation in the project. Badge shrinks from 52px → 40px at 640px (`_sections.scss:272`). Connector line repositions correctly (`_sections.scss:251`). Timeline content padding adjusts (`_sections.scss:279`). No critical issues.

### À propos (`/a-propos`)
| Issue | Severity |
|---|---|
| Container 32px gutter | 🟡 I3 |
| No srcset on Thierry photo | 🟡 I5 |
| Section padding 72px floor | 🟢 N2 |
- **Note:** `.team-card` collapses from `320px 1fr` to 1-column at 900px (`_sections.scss:1211`). `.client-type-grid` collapses to 1-col at 800px (`_sections.scss:1163`). `.values-grid` collapses to 1-col at 800px. Well handled.

### Contact (`/contact`)
| Issue | Severity |
|---|---|
| Footer bottom inner flex row no wrap | 🟡 I4 |
| `form-row` breakpoint at custom 700px | 🟢 N3 |
- **Note:** Form inputs are 17px (`_forms.scss:26`) and textarea is 16px (`_forms.scss:37`) — both **above iOS's 16px auto-zoom threshold**. No zoom issue. `.form-row` collapses to 1-column at 700px. `.contact-grid` collapses at 900px (`_sections.scss:529`). Good overall.

### Global: Nav (all pages)
| Issue | Severity |
|---|---|
| Logo 60px height + CTA button not hidden on mobile | 🔴 C1 |
| Drawer close button 36×36px (below 44px HIG) | 🟡 I6 |
- **Note:** Mobile drawer is well implemented — `min(340px, 88vw)`, `100dvh`, overlay backdrop, escape key, body scroll lock. JS toggle works correctly. `aria-expanded` managed.

### Global: Footer (all pages)
| Issue | Severity |
|---|---|
| Grid never collapses to single column | 🔴 C2 |
| Bottom legal links inner flex row no wrap | 🟡 I4 |

---

## Summary

| Severity | Count |
|---|---|
| 🔴 Critical | 2 |
| 🟡 Important | 6 |
| 🟢 Nice-to-have | 4 |
| **Total** | **12** |

**Most affected page:** Global (nav + footer affect every page — fix those first)

**Recommended fix order:**
1. **C1** — Nav: reduce logo height, hide CTA on mobile *(affects all pages, highest visibility)*
2. **C2** — Footer: add 1-column breakpoint, reduce avatar size *(affects all pages)*
3. **I1** — Hero headline: lower clamp floor *(first impression on mobile)*
4. **I2** — Comparison table: scrollable or restructured on mobile *(key conversion page)*
5. **I3** — Container gutter: 16px on mobile *(affects all page content width)*
6. **I4** — Footer bottom links: add flex-wrap *(legal/accessibility risk)*
7. **I5** — Add srcset to images *(performance)*
8. **I6** — Drawer close button: 44×44px *(accessibility)*
9. **N1–N4** — Optional polish pass

**What's already well handled:**
- Mobile drawer navigation: complete, accessible implementation
- Typography: extensive `clamp()` usage on all major headings
- Grid responsiveness: all content grids have appropriate collapse breakpoints
- Form inputs: 17px font prevents iOS auto-zoom
- Image aspect ratios: hero and article images adjust on mobile
- Method timeline: specifically designed for mobile with sized badge and connector line
- Product/category grids: proper 3→2→1 column progression
