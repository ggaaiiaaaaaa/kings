# Kings Group — Implementation Plan (Remaining Work)

> **Last audited:** 2026-04-26 by Antigravity  
> **Status:** ACF backend ✅ complete → Now focused on frontend wiring + UI polish

---

## ✅ Completed (Previous Sessions)

| # | Task | Status |
|---|------|--------|
| 1 | ACF field definitions for all 9 pages + Jobs CPT | ✅ Done |
| 2 | Fixed wrong field name prefixes (`service_` → `slab_`/`skit_`, `network_` → `net_`) | ✅ Done |
| 3 | Fixed `story_description` → `story_desc` template mismatch | ✅ Done |
| 4 | Added missing ACF fields (`quote_calc_instructions`, `base_price`, `include_in_team_builder`) | ✅ Done |
| 5 | Data populator with ~80+ field values across all pages | ✅ Done |
| 6 | Added `instructions`, `placeholder`, `rows` to all ACF fields | ✅ Done |
| 7 | Jobs CPT registered with ACF field group | ✅ Done |
| 8 | Liquid Glass UI design completed | ✅ Done |
| 9 | WordPress theme activated and presentation-ready | ✅ Done |
| 10 | Story page ACF fields for Timeline (7), Leadership (3), Companies (11) | ✅ Done |
| 11 | Data populator for Story Timeline, Leaders, Companies | ✅ Done |
| 12 | **Service Labor expanded:** 7 tabs, ~40 fields (pills, 4 features, manpower, offshore, checklist×3, onboarding) | ✅ Done |
| 13 | **Service Kit expanded:** 5 tabs, ~25 fields (pills, How We Work, 6 features, Moving Forward) | ✅ Done |
| 14 | **Data populator expanded for Service Labor & Service Kit** | ✅ Done |

---

## ✅ Phase 1: Template ACF Wiring (COMPLETE)

> **Goal:** Replace all hardcoded content in templates with `get_field()` calls so WordPress admin can edit everything.
>
> **Pattern to use everywhere:**
> ```php
> $field = function_exists('get_field') && get_field('field_name') 
>     ? get_field('field_name') 
>     : 'Default static content';
> ```

### Task 1.1 — Homepage (`front-page.php`)

| Section | Current State | Fields to Wire |
|---------|--------------|----------------|
| Hero (L14-48) | ✅ ACF with fallback | None |
| Trust Bar (L51-116) | ❌ Hardcoded label | `trust_label` |
| Staffing Intro (L119-126) | ❌ Hardcoded | `home_intro_title`, `home_intro_sub` |
| Client Advantage (L129-219) | ❌ Hardcoded | `adv_headline`, `adv_subheadline`, `adv_desc`, `adv_stat`, `adv_img`, `adv_f1_title`, `adv_f1_desc`, `adv_f2_title`, `adv_f2_desc`, `adv_f3_title`, `adv_f3_desc` |
| Applicant Focus (L222-312) | ❌ Hardcoded | `app_headline`, `app_subheadline`, `app_desc`, `app_img`, `app_f1_title`, `app_f1_desc`, `app_f2_title`, `app_f2_desc`, `app_f3_title`, `app_f3_desc` |
| Testimonials (L316-397) | ❌ Hardcoded | `testi_title`, `testi_subtitle`, + 2× quote/name/role/img |
| Network Affiliates (L399-474) | Static | Keep as-is |

---

### Task 1.2 — Story Page (`story.php`)

| Section | Current State | Fields to Wire |
|---------|--------------|----------------|
| Hero | ✅ ACF with fallback | None |
| Vision & Mission | ✅ ACF with fallback | None |
| Core Values | ❌ Hardcoded 9 values | `story_values_title`, `story_values_intro`, 9× title/desc |
| History Timeline | ❌ Hardcoded 7 entries | `story_timeline_title`, `story_timeline_intro`, 7× year/title/desc/img |
| Leadership Team | ❌ Hardcoded 3 members | `story_team_title`, `story_team_intro`, 3× name/role/img/creds |
| Group of Companies | ❌ Hardcoded 11 logos | `story_companies_title`, `story_companies_intro`, 11× img/name |

---

### Task 1.3 — Careers Page (`careers.php`)

| Section | Fields to Wire |
|---------|----------------|
| Hero | `careers_headline`, `careers_desc`, `careers_bg` |
| Application Form | `careers_form_title`, `careers_form_desc` |
| Job Listings | Replace hardcoded jobs with `WP_Query` from Jobs CPT |

---

### Task 1.4 — Benefits Page (`benefits.php`)

| Section | Fields to Wire |
|---------|----------------|
| Hero | `benefits_headline`, `benefits_desc`, `benefits_bg` |
| Lucky 9 Benefits | `benefits_list_title`, `benefits_list_desc`, 9× title/desc |

---

### Task 1.5 — Service Labor (`service-labor.php`)

| Section | Current State | Fields to Wire |
|---------|--------------|----------------|
| Hero (L14-23) | ✅ ACF with fallback | None |
| Service Intro (L27-54) | ⚠️ Partial (title+desc only) | `slab_intro_img`, `slab_intro_pills` |
| Managed Services — Section A (L56-117) | ❌ Hardcoded | `slab_managed_title`, `slab_managed_desc`, 4× `slab_feat{n}_title`/`slab_feat{n}_desc`/`slab_feat{n}_img` |
| Total Manpower Solutions (L119-135) | ❌ Hardcoded | `slab_manpower_title`, `slab_manpower_text`, `slab_manpower_img` |
| Staff Leasing — Section B (L295-322) | ⚠️ Partial (title+desc only) | `slab_offshore_title`, `slab_offshore_text`, `slab_offshore_img` |
| Improving Manpower (L324-365) | ❌ Hardcoded | `slab_improve_title`, `slab_improve_desc`, `slab_improve_img`, 3× `slab_check{n}_title`/`slab_check{n}_desc` |
| Onboarding Journey (L367-453) | ❌ Hardcoded header | `slab_onboard_title`, `slab_onboard_desc` |
| Comparison Tables | Static (structural) | Keep as-is — tables are design elements |

---

### Task 1.6 — Service Kit (`service-kit.php`)

| Section | Current State | Fields to Wire |
|---------|--------------|----------------|
| Hero (L14-23) | ✅ ACF with fallback | None |
| Service Intro (L27-57) | ⚠️ Partial (title+desc only) | `skit_intro_pills` |
| How We Work (L59-73) | ❌ Hardcoded | `skit_hww_title`, `skit_hww_text`, `skit_hww_img` |
| Platform Features (L75-186) | ❌ Hardcoded 6 cards | 6× `skit_feat{n}_title`/`skit_feat{n}_desc` |
| Moving Forward (L189-200) | ❌ Hardcoded | `skit_forward_title`, `skit_forward_text` |

---

### Task 1.7 — Network Page (`network.php`)

| Section | Fields to Wire |
|---------|----------------|
| Hero | `net_headline`, `net_desc`, `net_bg` |
| Stats Strip | 4× `net_s{n}_num` / `net_s{n}_label` |

---

### Task 1.8 — Quote Page (`quote.php`)

| Section | Fields to Wire |
|---------|----------------|
| Hero | `quote_headline`, `quote_desc`, `quote_bg` |
| Builder Text | `quote_b_title`, `quote_calc_instructions` |
| Team Builder | Replace hardcoded job list with `WP_Query` from Jobs CPT |

---

### Task 1.9 — Contact Page (`contact.php`)

| Section | Fields to Wire |
|---------|----------------|
| Hero | `contact_headline`, `contact_desc`, `contact_bg` |
| Corporate Info | `contact_address`, `contact_email`, `contact_phone` |

---

## ✅ Phase 2: UI/UX Micro-Interactions (COMPLETE)

### Task 2.1 — Tilt/Parallax Effects ✅
- [x] 3D tilt on .engagement-card, .folder-item, .feature-card, .affiliate-showcase-image, .testimonial-card, .st-item
- [x] Hero parallax — .hero-bg-media scrolls at 40% speed (respects prefers-reduced-motion)

### Task 2.2 — Persona-Based Color Logic ✅
- [x] Client persona (blue-dominant) — CSS tokens on body.persona-client
- [x] Talent persona (green-dominant) — CSS tokens on body.persona-talent
- [x] Smooth 0.4s transitions on all token-consuming properties
- [x] Fixed pill toggle UI (bottom-right), persists to localStorage

---

## 🔲 Phase 3: SEO & Structured Data (CURRENT FOCUS)

## 📋 Future Phases (Not Active Yet)

- **Phase 3:** SEO & Structured Data (meta tags, JSON-LD schemas)
- **Phase 4:** WordPress Template Hierarchy (menu wiring, single-jobs.php, archives)
- **Phase 5:** Performance & Image Optimization (WebP, lazy loading, CSS audit)
- **Phase 6:** Forms & Production Readiness (Contact Form 7, SMTP, security, deployment)

---

## Quick Reference: ACF Field Count by Page

| Page | Tabs | Total Fields | Status |
|------|------|-------------|--------|
| Homepage | 6 | ~35 | ✅ Wired |
| Story | 6 | ~70 | ✅ Wired |
| Careers | 2 | ~5 | ✅ Wired |
| Benefits | 2 | ~22 | ✅ Wired |
| Service Labor | 7 | ~40 | ✅ Wired |
| Service Kit | 5 | ~25 | ✅ Wired |
| Network | 2 | ~11 | ✅ Wired |
| Quote | 2 | ~5 | ✅ Wired |
| Contact | 2 | ~6 | ✅ Wired |
| Jobs CPT | 1 | 2 | ✅ ACF defined, ✅ wired |
| Global Options | 2 | 4 | ✅ ACF defined, ⚠️ needs wiring |
| **TOTAL** | **~37** | **~225** | |

---

## Template Wiring Pattern

```php
// All templates should use this pattern:
$field = function_exists('get_field') && get_field('field_name') 
    ? get_field('field_name') 
    : 'Default static content';
```
