# Kings Group Website — Setup Guide

**Theme:** Kings Group Website  
**Stack:** WordPress + ACF + Custom PHP  
**For:** Site administrator or hosting provider

---

## Step 1 — Install WordPress

Install a fresh WordPress instance on your hosting provider.  
Minimum requirements:
- WordPress 6.0 or higher
- PHP 7.4 or higher (8.1+ recommended)
- MySQL 5.7 or higher

---

## Step 2 — Install ACF (Advanced Custom Fields)

**This must be done BEFORE activating the theme.**

1. In WP Admin, go to **Plugins > Add New**
2. Search for **Advanced Custom Fields**
3. Install and **Activate** it

> The free version is sufficient. If you need the "Theme Settings" panel (footer text, social links), you will need **ACF Pro**.

---

## Step 3 — Upload and Activate the Theme

1. Go to **Appearance > Themes > Add New > Upload Theme**
2. Upload the `kingsgroup.zip` file
3. Click **Activate**

Upon activation, the theme automatically:
- Creates all 9 pages (Home, Our Story, Careers, Team Builder, Member Benefits, Labor Management, HR Tech, Our Network, Contact Us)
- Assigns the correct page template to each page
- Sets the Home page as the static front page
- Seeds all text content (headlines, descriptions, values, benefits, leadership bios, etc.)
- Seeds placeholder images (Unsplash) — replace these with real photos
- Creates 8 default job roles for the Team Builder calculator

---

## Step 4 — Flush Permalinks

1. Go to **Settings > Permalinks**
2. Select **Post name**
3. Click **Save Changes**

This ensures custom post type URLs (e.g., `/jobs/`, `/applications/`) work correctly.

---

## Step 5 — Configure SMTP Email

The Contact, Careers, and Quote forms send email via SMTP. Without this, form submissions will silently fail.

### 5a — Create a Gmail App Password

1. Sign in to the Gmail account that will send site emails
2. Go to **myaccount.google.com > Security**
3. Under "How you sign in to Google", enable **2-Step Verification** if not already on
4. Go back to Security, scroll to **App Passwords**
5. Create a new app password — name it "Kings Group Website"
6. Copy the 16-character password shown (you only see it once)

### 5b — Add constants to wp-config.php

Open `wp-config.php` in your WordPress root folder and add these lines just above the line that says `/* That's all, stop editing! */`:

```php
/** Kings Group SMTP Configuration */
define('KG_SMTP_HOST',     'smtp.gmail.com');
define('KG_SMTP_PORT',     587);
define('KG_SMTP_USER',     'your-gmail@gmail.com');
define('KG_SMTP_PASS',     'xxxx xxxx xxxx xxxx'); // 16-char App Password from Step 5a
define('KG_SMTP_FROM',     'your-gmail@gmail.com');
define('KG_SMTP_FROMNAME', 'Kings Group');
define('KG_ADMIN_EMAIL',   'your-gmail@gmail.com'); // All form submissions are sent to this address
```

Replace `your-gmail@gmail.com` and the App Password with your actual values.

---

## Step 6 — Set Up Navigation Menus

The theme has two menu locations: **Primary Navigation** (header) and **Footer Navigation**.

1. Go to **Appearance > Menus**
2. Create a menu named **Main Menu**
3. Add the 9 pages to it
4. Under **Menu Settings**, check **Primary Navigation** and save
5. Create a second menu named **Footer Menu**, add relevant pages, check **Footer Navigation** and save

---

## Step 7 — Replace Placeholder Content

The theme seeds placeholder content to get you started. Replace these with real assets:

### Photos
All images are currently Unsplash stock photos. Replace via **Pages > [page name] > ACF fields**:
- Hero background images (all pages)
- Leadership team photos (Our Story page)
- Section images throughout

### Testimonials
The testimonials section is empty by default (the CPT has no posts seeded). Add real testimonials:
1. Go to **WP Admin > Testimonials > Add New**
2. Fill in: Quote, Name/Role, Photo URL, Display Order
3. Publish — it appears on the home page immediately

### Company Logos
The Group of Companies section on the Our Story page has company names but no logos:
1. Go to **Pages > Our Story**
2. Scroll to the ACF fields for each company
3. Upload the logo image for each

### Story Page Video
The "Our Roots" video field is blank by default:
1. Go to **Pages > Our Story**
2. Find the **Roots Video URL** ACF field
3. Paste the YouTube or Vimeo embed URL

---

## Step 8 — Verify Forms

Test each form after SMTP is configured:

1. **Contact form** — go to `/contact/`, submit a message, confirm email arrives
2. **Careers form** — go to `/careers/`, submit with a CV file attached
3. **Quote form** — go to `/team-builder/`, build a team and submit

Submitted entries are also saved in WP Admin under:
- **Applications** — career form submissions
- **Inquiries** — contact form leads
- **Quote Leads** — team builder requests

---

## Content Management Reference

| What to edit | Where in WP Admin |
|---|---|
| Page headlines, descriptions, images | Pages > [page] > ACF fields (scroll below editor) |
| Testimonials | Testimonials > All Testimonials |
| Job listings (Careers page + Team Builder) | Jobs > All Jobs |
| Career form submissions | Applications |
| Contact form leads | Inquiries |
| Quote requests | Quote Leads |
| Footer text | Appearance > Theme Settings (ACF Pro only) |

---

## Troubleshooting

**Pages are blank or unstyled**  
Make sure ACF was activated before the theme. If you activated the theme first, deactivate it, activate ACF, then reactivate the theme.

**Data didn't populate**  
The populator runs once on `init` and is gated by a flag. If it failed silently, go to `wp-config.php` and confirm ACF is active, then go to **WP Admin > Tools > Site Health** to check for PHP errors.

**Emails not sending**  
Check that `KG_SMTP_PASS` is the App Password (16 characters, no spaces in the define), not your regular Gmail password. Also confirm 2-Step Verification is enabled on the Gmail account.

**Team Builder shows no roles**  
Go to **Jobs** and confirm job posts exist and have **Base Price** filled in. The populator creates 8 defaults — if they're missing, ACF wasn't active when the theme was first activated.

**Permalinks returning 404**  
Go to **Settings > Permalinks** and click **Save Changes** again to flush rewrite rules.
