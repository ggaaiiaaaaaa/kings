# Design Doc: Community & News Integration
**Date:** 2026-05-07
**Status:** Finalized / Ready for Implementation

---

## 1. Overview
The goal is to implement two distinct pages: **News** (article-focused) and **Community** (impact-focused), and integrate them into the existing WordPress theme's navigation system. All redundant "Team/Leadership" content is removed from the Community page as it is already covered in `story.php`.

---

## 2. Navigation Updates (header.php)

### About Mega Menu (Left Section)
- **Our Story**: Existing (Legacy and timeline).
- **Mission & Values**: Existing (Core principles).
- **News**: NEW link to the News archive.
- **Community**: NEW link to the Community page.

### Applicant Nav (Right Section)
- **Find a Job**: Existing.
- **Member Portal**: Existing.
- **Log In**: Existing.
- *(Note: "Community Benefits" link removed as requested).*

---

## 3. Page Designs

### A. News Page (Archive)
- **Template:** `index.php` (The primary news loop).
- **Layout:**
    - **Hero:** "News & Updates" with Liquid Glass styling.
    - **Article Grid:** 3-column responsive grid.
    - **Card Design:** Featured image, "News" category tag, Date, Title, Excerpt, "Read More" link.

### B. Community Page (Impact-Focused)
- **Template:** `page-community.php`.
- **Content Sections:**
    - **Hero:** "Our Commitment to Community" — High-impact visual.
    - **Welcome:** "Welcome to The KINGS — Find great opportunities now!"
    - **Queens of Kings Group:** Dedicated initiative showcase.
    - **Impact Programs (ACF Driven):** 
        - **Scholarship Program**: Descriptive text on member/dependent support.
        - **Home Culinary School**: Full list of TESDA NC II courses (Culinary, Cookery, Bread/Pastry, F&B, Housekeeping) + [APPLY NOW] button.

---

## 4. Technical Architecture
- **Post Management:** Standard WP `posts` categorized as "News" or "Community".
- **ACF Field Group:** Manage program descriptions and course lists for `page-community.php`.
- **Custom Template:** `page-community.php` created to handle the specific layout.
- **Navbar Logic:** Update `header.php` hardcoded menu and `functions.php` auto-menu generator.

---

## 5. Verification Plan
- [ ] Verify separate loops: `index.php` for all/news, and `page-community.php` for community-specific highlights.
- [ ] Confirm no "Team" redundancy on Community page.
- [ ] Test navbar links across all breakpoints.
- [ ] Validate ACF field population for the Culinary School courses.
