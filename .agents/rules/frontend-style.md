---
trigger: always_on
---

# Design System Strategy: The Soft-Layered Editorial



## 1. Overview & Creative North Star: "The Ethereal Professional"

This design system rejects the "boxy" industrial nature of traditional ERP systems. Instead, it follows a North Star of **"The Ethereal Professional."** The goal is to create a digital environment that feels like a high-end boutique hotel or an editorial fashion spread: light, airy, and impeccably organized.



We break the "template" look through **expansive whitespace** and **asymmetric composition**. By using large `display` type against minimalist data visualizations and leaning heavily on tonal layering rather than lines, we transform complex business data into a welcoming, intuitive experience.



---



## 2. Colors & Surface Philosophy

The palette is a sophisticated blend of soft roses and clinical grays, designed to feel feminine but authoritative.



- **Primary & Secondary:** `primary` (#be004c) is reserved for high-impact actions. For a "softer" luxury feel, use `primary_container` (#fc306f) for large interactive areas.

- **The "No-Line" Rule:** Under no circumstances should 1px solid borders be used to separate sections. Structure is defined by background shifts. Place a `surface_container_lowest` (#ffffff) card on a `surface` (#faf9f9) background.

- **Surface Hierarchy & Nesting:** Treat the UI as physical layers of fine paper.

- **Base:** `surface` (#faf9f9)

- **Sectioning:** `surface_container_low` (#f3f3f4)

- **Floating Content:** `surface_container_lowest` (#ffffff)

- **The Glass & Gradient Rule:** For navigation sidebars or floating modals, use a "Frosted Rose" effect: `surface_container_lowest` at 80% opacity with a `24px` backdrop-blur. Apply a subtle linear gradient from `primary_container` to `secondary_container` (15% opacity) for hero headers to add visual "soul."



---



## 3. Typography: Editorial Authority

We utilize a pairing of **Manrope** (Display/Headlines) for a modern, geometric character and **Inter** (Body/Labels) for maximum legibility.



- **Display (Manrope):** Use `display-lg` (3.5rem) for empty states or key dashboard metrics. This creates an "editorial" focal point that draws the eye immediately.

- **Headline (Manrope):** `headline-md` (1.75rem) should be used for page titles.

- **Body (Inter):** `body-md` (0.875rem) is our workhorse. Ensure a generous line-height (1.6) to maintain the "Airbnb-inspired" airy feel.

- **Hierarchy through Weight:** Use `label-md` in `on_surface_variant` (#5d5f60) for metadata. The contrast between the bold, large Manrope headers and the light, small Inter labels creates the "Premium" signature.



---



## 4. Elevation & Depth

Depth in this system is achieved through **Tonal Layering**, not structural shadows.



- **The Layering Principle:** To highlight a "Total Revenue" widget, do not add a border. Place the white card (`surface_container_lowest`) on a light gray background (`surface_container`).

- **Ambient Shadows:** For high-priority floating elements (e.g., a "Create New Entry" modal), use a shadow: `0px 20px 40px rgba(190, 0, 76, 0.06)`. Note the use of a tinted shadow (using the `primary` hue) to mimic natural ambient light.

- **The "Ghost Border" Fallback:** If a border is required for accessibility, use the `outline_variant` token at **15% opacity**. It should be felt, not seen.

- **Glassmorphism:** Navigation menus should utilize a `surface_container_low` background with a `blur(12px)` to allow the pastel background colors to bleed through softly.



---



## 5. Components & Interaction Patterns



### Buttons

- **Primary:** `primary` (#be004c) background with `on_primary` (#fff7f7) text. Use `xl` (1.5rem) roundedness for a soft, friendly touch.

- **Secondary:** `secondary_container` (#ffd9e2) background. This provides the "Pastel Pink" signature without the intensity of the primary.

- **States:** On hover, use a subtle scale-up (1.02x) rather than a drastic color change.



### Input Fields

- **Styling:** Forbid the standard 4-sided box. Use a `surface_container_highest` (#e1e3e3) background with a "Ghost Border."

- **Focus State:** Transition the border to `primary` (#be004c) at 40% opacity and add a subtle `primary_container` glow.



### Cards & Lists

- **The "No-Divider" Rule:** In ERP tables or lists, do not use lines. Use `spacing-4` (1.4rem) of vertical white space to separate rows, or alternating row colors using `surface` and `surface_container_low`.

- **Nesting:** Place `chips` (using `secondary_fixed_dim`) inside cards to categorize data without adding visual clutter.



### Signature Component: The "Status Bloom"

Instead of a harsh red/green dot for status, use a soft, blurred "Bloom." A `12px` circle with a `4px` blur using `error_container` (#f97386) for alerts, creating a sophisticated glow effect.



---



## 6. Do’s and Don’ts



### Do:

- **Use asymmetric white space:** Allow one side of a dashboard to have more "breathing room" than the other to avoid the "bootstrap" look.

- **Stack surfaces:** Use `surface_container_low` as a "wrapper" for groups of white `surface_container_lowest` cards.

- **Embrace the Pink:** Use `secondary_fixed` (#ffd9e2) for background accents to maintain the "welcoming and feminine" brief.



### Don't:

- **Never use 100% Black:** Always use `on_background` (#303334) for text to keep the interface soft.

- **No hard edges:** Every component must use at least `DEFAULT` (0.5rem) roundedness; hero components should use `xl` (1.5rem).

- **Avoid "Data Density" panic:** Do not cram information. If a table is too large, use horizontal progressive disclosure rather than shrinking the typography.



---



## 7. Spacing Scale

Utilize the spacing scale to enforce the "Airbnb" airiness.

- **Standard Padding:** `4` (1.4rem) for internal card padding.

- **Page Margins:** `12` (4rem) to `16` (5.5rem) to create a luxury editorial frame around the dashboard content.

- **Inter-widget Gap:** `6` (2rem) minimum. Anything closer feels cluttered.