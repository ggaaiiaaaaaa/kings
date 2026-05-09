# PROJECT CONTEXT — Kings Group Website
# Universal session state. Read by: Antigravity, Gemini CLI, Claude Code CLI
# Last updated: 2026-05-01 via Claude Code

---

## PROJECT OVERVIEW
```
Name:     Kings Group Website
Type:     WordPress Theme Development (Phase 6 complete)
Stack:    HTML, CSS (Liquid Glass), JS, PHP, ACF, WP Mail SMTP
Features: Recruitment, Staffing, Service Pricing, Career, Network, Membership
Status:   Phase 6 complete. Handoff-ready. SETUP_GUIDE.md included in theme.
```

---

## CURRENT STATE
```
Phase:    Phase 6 complete + all admin tracking CPTs + testimonials CPT + handoff prep
Branch:   master
```

---

## LAST SESSION
```
Date:      2026-05-08
Tool used: Gemini CLI
```

### What was done
- [x] **Community Page Fixes** — Resolved a fatal PHP error and a database template assignment issue that was breaking the layout of the Community page.
    - Created and executed a script to re-assign the `community.php` template in the database and clear the raw post content.
    - Replaced a call to a non-existent `kg_no_image()` function with the correct `kg_img()` helper function.
- [x] **Obsidian Vault Sync** — Synchronized the project context with the Obsidian vault (`godmode-brain`). Updated index, active session, spec, changelog, and hub files to reflect current status.

### Decisions made
- Maintained the original custom HTML structure for `community.php` after the user evaluated and decided to revert an experimental "Liquid Glass" design iteration for the Impact section.
- Separate News and Community to distinguish between temporal articles and permanent social impact missions.

### Files modified
- `PROJECT_CONTEXT.md`
- `community.php`
- `inc/acf-fields.php`
- `inc/data-populator.php`

---

## ACTIVE TASKS

### In Progress
- [ ] Handoff Preparation (Pre-flight checks and testing).

### Up Next (priority order)
- [ ] Add KG_ADMIN_EMAIL and SMTP constants to local wp-config.php for testing.
- [ ] Manual email testing on localhost (contact form, careers CV upload, quote submission).
- [ ] Add real testimonials in WP Admin → Testimonials (replace the empty placeholder state).
- [ ] Verify website across mobile breakpoints.
- [ ] Zip kingsgroup theme folder and hand off to client.

### Done
- [x] Initial God Mode project setup
- [x] Project Context Alignment
- [x] Liquid Glass UI Implementation
- [x] ACF Metabox Audit & Fix
- [x] Intelligent ACF Fallback Logic (kg_get_field)
- [x] Contact Page Premium Redesign
- [x] Phase 2: Tilt, Parallax, Persona Toggle & Color Logic
- [x] Phase 3: SEO Meta Tags, Canonical URLs, OG/Twitter, JSON-LD Structured Data (all 9 pages)
- [x] Phase 4: WordPress Template Hierarchy (menus, CPT templates, archive, search)
- [x] Phase 5: Performance & Image Optimization (WebP helpers, lazy loading, image sizes)
- [x] Phase 6: Forms & Email (contact, careers CV upload, quote team builder — AJAX + HTML emails)
- [x] WP Admin CPTs: kg_application, kg_inquiry, kg_quote_lead
- [x] UX: all alert() removed from quote.php and careers.php
- [x] kg_testimonial CPT + front-page.php + index.php updated
- [x] Client handoff prep: SETUP_GUIDE.md + KG_ADMIN_EMAIL fix
- [x] Generated internship weekly reports (Weeks 7-11) based on project progress.

---

## BLOCKERS / OPEN QUESTIONS
- [ ] None at the moment.

---

## ARCHITECTURE NOTES
```
[ACF Logic: Use kg_get_field($name, $fallback) to handle intentional empty strings.]
[Liquid Glass: Uses backdrop-filter: blur() with rgba(255,255,255,0.45).]
[Contact Map: uses maps.google.com/maps with q=address and iwloc=B for red pin.]
[SMTP: phpmailer_init at priority 999 in functions.php reads KG_SMTP_* from wp-config.php.]
[kg_flush_response(): Connection:close + Content-Length trick — browser gets JSON before SMTP runs.]
[CPT no-create pattern: capabilities => array('create_posts' => 'do_not_allow'), map_meta_cap => true.]
[Quote roles keys: role, level, qty, unit_price, subtotal — JS and PHP must match.]
[Testimonials: kg_get_testimonials() returns all published kg_testimonial posts ordered by _kg_testi_order ASC.]
[KG_ADMIN_EMAIL: recipient for all 3 form handlers. Falls back to get_option('admin_email') if not defined.]
[wp-config.php constants: KG_SMTP_HOST, KG_SMTP_PORT, KG_SMTP_USER, KG_SMTP_PASS, KG_SMTP_FROM, KG_SMTP_FROMNAME, KG_ADMIN_EMAIL.]
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
