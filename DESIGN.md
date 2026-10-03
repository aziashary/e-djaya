# e-Djaya UI Direction

## Design Read

Point-of-sale and store operations interface for cashiers and administrators. Warm retail visual language. Dial: ENERGY 2 / RHYTHM 2 / MOTION 1.

## Identity

- Product: e-Djaya, supporting daily sales, catalog, staff, and reporting work.
- Personality: warm, direct, dependable, and easy to scan during busy service.
- Existing Djaya and Ranu logos remain the only brand assets. No replacement logo, avatar, illustration, testimonial, or fabricated metric.
- Identity motif: receipt-ledger grouping for monetary values, using tabular figures and dashed subtotal separators only where they clarify transaction math.

## Color

- Primary red: `#B42318`. Purpose: preserve the existing red identity and reserve the strongest color for the current destination and primary action.
- Primary hover: `#8F1D14`. Purpose: provide an unmistakable pressed and hover state without changing layout.
- Warm canvas: `#FFF8F3`. Purpose: reduce the cold admin-template feel and connect the interface to food and retail environments.
- Top wash: a restrained red-to-transparent tint appears only behind the page header to anchor the navigation and first task area; content surfaces stay solid.
- Surface: `#FFFFFF`. Purpose: keep forms, tables, and transaction panels clear against the warm canvas.
- Ink: `#241915`. Purpose: provide high-contrast reading without pure-black harshness.
- Muted ink: `#675A54`. Purpose: support secondary labels while retaining WCAG AA contrast.
- Warm accent: `#9A6700`. Purpose: mark warnings and secondary highlights only, never compete with the red primary action.
- Success: `#166534`. Purpose: communicate completed transactions and active states with text or icons, never color alone.
- Primary red with white measures 6.57:1. Ink on warm canvas measures 16.31:1. Muted ink on warm canvas measures 6.31:1.

## Typography

- Typeface: Plus Jakarta Sans, with system sans-serif fallback.
- Reason: its open forms and moderate roundness feel friendly while remaining compact enough for POS tables, forms, and numeric summaries.
- Money, quantities, and transaction codes use tabular figures for stable alignment.
- Type scale uses `clamp()` for page titles and remains at least 16px for mobile form controls.

## Layout

- Mobile-first, with distinct phone, tablet, and desktop compositions driven by content width.
- One primary action per screen. Secondary actions remain visibly subordinate.
- Dashboard prioritizes current operational facts from the database, not a generic row of equal cards.
- POS prioritizes products and cart total. Checkout remains the dominant action.
- CRUD pages prioritize search, table scanning, and the create or save action.
- Report pages prioritize filters, totals, and readable transaction rows.
- Tables use responsive containers on medium screens and labeled stacked rows where horizontal comparison is no longer useful.

## Components

- Radius scale: 8px controls, 12px cards, 16px major task panels. Purpose: distinguish controls from containers without making every element pill-shaped.
- Spacing follows a compact 8px-based scale. Purpose: keep busy POS and CRUD screens scannable without crowding touch targets.
- Cards group one complete task, metric, or table only. Purpose: separate operational decisions, not decorate every piece of content.
- Shadows: only elevated navigation, open menus, modals, and the active checkout panel receive shadow. Flat content panels use borders.
- Icons: retain the existing icon font only where the glyph matches the action. Every icon-only control receives an accessible name.
- Focus: every interactive element uses a visible red focus ring with offset.
- States: data views include useful empty, loading, and error language. Forms place validation next to the affected field.

## Motion

- MOTION 1: hover, press, focus, drawer, dropdown, and modal state transitions only.
- Duration tokens: 120ms for press feedback, 180ms for controls, 220ms for overlays.
- Motion uses opacity and transform, remains interruptible, and is disabled when `prefers-reduced-motion: reduce` is active.

## Research Decision

The initial ui-ux-pro-max aggregate recommendation used a dark glass dashboard and monospace headings. It was rejected because it conflicts with the selected warm retail direction, daytime POS readability, and antislop rules for unjustified dark mode, glass, and technical typography.
