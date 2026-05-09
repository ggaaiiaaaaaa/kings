# 🚀 Kings Group: High-Speed Deployment Manual (Turbo Mode)

This manual is optimized for a tight deadline. It focuses on the most critical tasks to get the site from your local XAMPP to the live server.

---

## 🕒 Phase 2: CMS Integration via Free ACF Meta Boxes (Estimated Time: 4-6 Hours)

*Strategy Selected: Option A (Free ACF + Meta Boxes). Safest, fastest, and guarantees design integrity.*

### 1. Programmatic Field Setup (The "Backend Forms")
*   **Goal:** Create the data entry boxes in the WordPress dashboard without needing the Pro version.
*   **Action:** Install **Advanced Custom Fields (Free)**.
*   **CLI Command to help you:** *"Write the PHP code to register an ACF field group for the Homepage Hero section in functions.php."*

### 2. The Auto-Population Script (The "Time Saver")
*   **Goal:** Automatically insert all current hardcoded text and placeholder images into the database so the client doesn't start with a blank page.
*   **Action:** Create a PHP script hooked to `admin_init` that runs once to automatically fill the newly created ACF Meta Boxes with default data.
*   **CLI Command to help you:** *"Create an auto-population script that creates the Home page and fills its ACF fields with my current placeholder text and images using update_post_meta."*

### 3. The Job Board Engine (Automatic Updates)
*   **Goal:** New jobs added in WP must show up on the Careers page AND the Team Builder.
*   **Action:** Register the `jobs` Custom Post Type.
*   **Action:** Build the `single-jobs.php` template.
*   **CLI Command to help you:** *"Register a 'Jobs' post type with fields for 'Base Price' and 'Include in Team Builder' toggle."*

### 4. High-Speed Code "Wiring"
*   **Goal:** Connect the actual website design to the data in the Meta Boxes.
*   **Action:** Go page-by-page (starting with `front-page.php`). Delete the hardcoded HTML text and replace it with `<?php the_field('field_name'); ?>`.
*   **CLI Command to help you:** *"Replace the hardcoded headline in front-page.php with get_field('hero_headline')."*

---

## 🔍 Phase 3: Functionality & SEO (Estimated Time: 2-3 Hours)

### 5. Forms & Leads
*   **Action:** Install **Contact Form 7**.
*   **Action:** Replace your static HTML `<form>` tags with the plugin's shortcode.
*   **Action:** Test that the "Get a Quote" emails actually arrive in your inbox.

### 6. Search Engine Dominance
*   **Action:** Install **RankMath SEO**.
*   **Action:** Set up the "Organization" Schema for the whole site.
*   **Action:** Ensure the "Job Posting" Schema is active for all Career listings.

---

## 🛡️ Phase 4: The "Missing 10%" (Production Readiness)
*These steps separate a "working" site from a professional, bulletproof business asset.*

### 7. SMTP & Email Deliverability (CRITICAL)
*   [ ] Install **WP Mail SMTP** or a similar plugin.
*   [ ] Connect it to the client's real email provider (Google Workspace, Office 365, SendGrid).
*   [ ] Send a test email from the "Get a Quote" form to guarantee it doesn't go to Spam.

### 8. Analytics & Tracking
*   [ ] Install **Site Kit by Google** or manually insert the **Google Analytics 4 (GA4)** tracking code into the `<head>`.
*   [ ] Verify tracking is capturing data.

### 9. Basic Security Hardening
*   [ ] Install a security plugin (like **Solid Security** or **WPS Hide Login**).
*   [ ] Change the default WordPress login URL from `wp-admin` to something custom (e.g., `/kg-secure-login`).
*   [ ] Enable brute-force protection to block malicious bots.

### 10. Legal & Compliance
*   [ ] Create a basic **Privacy Policy** and **Terms of Service** page.
*   [ ] Add a lightweight **Cookie Consent Banner** plugin to comply with international client regulations (GDPR/CCPA).

---

## 🚢 Phase 5: Final Deployment (The Finish Line)

### 11. Pre-Flight Checklist
*   [ ] Check all links on mobile.
*   [ ] Run a final "Broken Link" check.
*   [ ] Optimize all images using a plugin like **Imagify** or **Smush**.

### 8. Moving to the Live Server
1.  **Export:** Use **All-in-One WP Migration** plugin.
2.  **Upload:** Create a blank WP install on the live server and import your file.
3.  **URL Swap:** Update the site URL to `kingsgroup.com` (The plugin usually does this for you).
4.  **SSL:** Activate HTTPS.
5.  **Cache:** Install **WP Rocket**.

---

## ⚡ How to use the AI (Gemini CLI) to finish faster:
Don't write the PHP logic yourself. Tell me:
*   *"I've created an ACF field called 'hero_subtitle'. Replace the text on line 45 of front-page.php with this field."*
*   *"I need a PHP loop for the Careers page that fetches the last 6 job posts."*
*   *"Fix any 404 errors in my style.css image paths."*

**You are ready. Go win that presentation!**
