# PROJECT CONTEXT — Kings Group Website
# Universal session state. Read by: Antigravity, Gemini CLI, Claude Code CLI
# Last updated: 2026-04-30 via Claude Code

---

## PROJECT OVERVIEW
```
Name:     Kings Group Website
Type:     WordPress Theme Development (Phase 6 complete)
Stack:    HTML, CSS (Liquid Glass), JS, PHP, ACF, WP Mail SMTP
Features: Recruitment, Staffing, Service Pricing, Career, Network, Membership
Status:   All three forms fully wired with real AJAX + branded HTML emails.
```

---

## CURRENT STATE
```
Phase:    Phase 6 complete — Forms & Email (Contact, Careers CV upload, Quote team builder)
Branch:   master
```

---

## LAST SESSION
```
Date:      2026-04-30
Tool used: Claude Code
```

### What was done
- [x] **Phase 6: inc/email-templates.php created** — Shared branded HTML email builders: `kg_email_wrap()`, `kg_email_row()`, `kg_email_heading()`, `kg_email_para()`, `kg_email_button()`, `kg_email_banner()`. Kings Group branding (#0A2540 header/footer, #00D09C accents).
- [x] **Phase 6: inc/form-handlers.php created** — Three AJAX handlers: `kg_handle_contact`, `kg_handle_application`, `kg_handle_quote`. All use nonce verification, honeypot spam protection, and `wp_mail()` for HTML emails.
- [x] **Phase 6: contact.php wired** — Real HTML form with AJAX submit. Sends inquiry to info@kingsgroup.com.ph with Reply-To set to sender. Auto-reply sent to visitor confirming receipt.
- [x] **Phase 6: careers.php wired** — CV upload form posts `multipart/form-data` to AJAX handler. File validated (PDF/DOCX, 5MB max), saved via `wp_handle_upload()`. Kings Group gets email with CV attached; applicant gets auto-reply with "Browse Open Positions" button.
- [x] **Phase 6: quote.php wired** — `submitQuote()` replaced with real AJAX. Sends `quote_roles` as JSON. PHP builds branded roles table (role, level×qty, unit price, subtotal, grand total). Kings Group gets quote request; client gets confirmation email with same table.
- [x] **Phase 6: functions.php updated** — `wp_localize_script()` adds `KG_AJAX` object (AJAX URL + 3 nonces) to JS. `require_once form-handlers.php` added.
- [x] **Phase 6: Nav fixes** — `kg_create_default_menus()` force-deletes & recreates menus on init to prevent stale DB duplication. About dropdown moved before Get a Quote in header. Footer reverted to hardcoded three-column layout.
- [x] **Phase 6: Permalink flush** — `kg_flush_rewrite_once()` uses option flag so `/jobs/` URL resolves automatically without manual WP Admin visit.

### Decisions made
- [Decision] No Mailtrap/sandbox — user tests emails directly via WP Mail SMTP + Gmail SMTP on localhost. Production deployment after manual testing.
- [Decision] Honeypot spam protection chosen over reCAPTCHA — simpler, no external JS dependency, silent bot rejection.
- [Decision] Quote roles JSON from JS uses keys `role`, `level`, `qty`, `unit_price`, `subtotal` — PHP handler reads same keys.
- [Decision] CV files saved to WP uploads via `wp_handle_upload()` for Media Library integration and automatic filename deduplication.

### Files modified
- contact.php, careers.php, quote.php, functions.php, style.css, header.php, footer.php
- Created: inc/email-templates.php, inc/form-handlers.php

---

## ACTIVE TASKS

### In Progress
- (none)

### Up Next (priority order)
- [ ] Manual email testing on localhost (test contact form, careers CV upload, quote submission)
- [ ] Production deployment when testing passes

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
