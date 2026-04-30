# PROJECT CONTEXT — Kings Group Website
# Universal session state. Read by: Antigravity, Gemini CLI, Claude Code CLI
# Last updated: 2026-04-30 via Gemini CLI

---

## PROJECT OVERVIEW
```
Name:     Kings Group Website
Type:     WordPress Theme Development (Phases 4 & 5 complete)
Stack:    HTML, CSS (Liquid Glass), JS, PHP, ACF
Features: Recruitment, Staffing, Service Pricing, Career, Network, Membership
Status:   WP template hierarchy complete; image performance optimized.
```

---

## CURRENT STATE
```
Phase:    Phase 5 complete — WP menus, CPT templates, lazy loading, WebP helpers
Branch:   master
Commit:   423ccb8 perf: add loading=eager/lazy to all img tags
```

---

## LAST SESSION
```
Date:      2026-04-30
Tool used: Claude Code
```

### What was done
- [x] **Phase 4: WP nav menus wired** — header.php uses `wp_nav_menu()` for menu-1 (client) and menu-2 (applicant). footer.php uses `wp_nav_menu()` for footer menu with full hardcoded fallback. CSS `display:contents` reset makes WP list items flow as bare nav links seamlessly.
- [x] **Phase 4: single-jobs.php created** — Full CPT single template with JobPosting JSON-LD schema, breadcrumb nav, two-column layout (content + sticky sidebar with Apply CTA + job details card), ACF fields (location, type, salary_min, salary_max, department), mobile responsive.
- [x] **Phase 4: archive-jobs.php created** — Grid layout with glass cards per job, type badge, location, excerpt, "View Position →" button, pagination, empty state.
- [x] **Phase 4: search.php created** — Search results page with post-type badge, title, date, excerpt, pagination, no-results state.
- [x] **Phase 5: kg_webp() helper added** — Auto-serves .webp over .png/.jpg if file exists on disk (filesystem check). Future-proofs image references.
- [x] **Phase 5: kg_img() updated** — Added `$loading` parameter (defaults to `'lazy'`), outputs `loading="..."` HTML attribute.
- [x] **Phase 5: add_image_size() calls added** — kg-hero (1920×800), kg-card (600×400), kg-thumbnail (300×200) registered in WP Media Library.
- [x] **Phase 5: Lazy loading audit complete** — All `<img>` tags across all templates now have explicit `loading=` attribute. Hero/header images use `loading="eager"` (LCP optimization). All other images use `loading="lazy"` (60+ trust bar logos, network cards, story photos, testimonials, affiliates, footer logo).
- [x] **ACF field group for jobs CPT** — inc/acf-fields.php: job_location, job_type (select), job_salary_min, job_salary_max, job_department.

### Decisions made
- [Decision] Header/footer nav menus use WP dynamic menus with CSS `display:contents` trick — no visual change, fully editable from WP Admin.
- [Decision] `kg_webp()` does filesystem check so it only serves .webp if the file actually exists — safe for gradual rollout.
- [Decision] Hero slide 1 (`.active`) gets `loading="eager"`, slides 2-3 get `loading="lazy"` — avoids penalizing LCP on non-visible slides.

### Files modified
- header.php, footer.php, functions.php, inc/acf-fields.php, style.css
- Created: single-jobs.php, archive-jobs.php, search.php

---

## ACTIVE TASKS

### In Progress
- (none)

### Up Next (priority order)
- [ ] Phase 6: Forms & production readiness (Contact Form 7, SMTP, deployment) — user to decide scope.

### Done
- [x] Initial God Mode project setup
- [x] Project Context Alignment
- [x] Liquid Glass UI Implementation
- [x] ACF Metabox Audit & Fix
- [x] Intelligent ACF Fallback Logic (`kg_get_field`)
- [x] Contact Page Premium Redesign
- [x] Phase 2: Tilt, Parallax, Persona Toggle & Color Logic
- [x] Phase 3: SEO Meta Tags, Canonical URLs, OG/Twitter, JSON-LD Structured Data (all 9 pages)
- [x] Phase 4: WordPress Template Hierarchy (menus, CPT templates, archive, search)
- [x] Phase 5: Performance & Image Optimization (WebP helpers, lazy loading, image sizes)

---

## BLOCKERS / OPEN QUESTIONS
- [ ] None at the moment.

---

## ARCHITECTURE NOTES
```
[ACF Logic: Use kg_get_field($name, $fallback) to handle intentional empty strings.]
[Liquid Glass: Uses backdrop-filter: blur() with rgba(255,255,255,0.45).]
[Contact Map: uses maps.google.com/maps with q=address and iwloc=B for red pin.]
```

---

## ACTIVE SKILLS
- @.skills/html-css-js.md — Native web development best practices
- @.skills/wordpress-dev.md — Architecting for WordPress theme conversion
- @.skills/wp-theme-architect.md — Advanced WP theme architecture (CPTs, ACF)
- @.skills/seo.md — Semantic HTML and metadata for search ranking
- @.skills/structured-data-expert.md — JSON-LD for "Google for Jobs" and rich snippets
- @.skills/php.md — Backend logic and WordPress template hierarchy
- @.skills/performance.md — Asset optimization and fast LCP
- @.skills/responsive-design.md — Mobile-first fluid layouts
- @.skills/glassmorphism-ux.md — Liquid Glass UI/UX interactions
- @.skills/typography-design.md — Fluid typography and corporate hierarchy
- @.skills/token-saver.md — Session efficiency
