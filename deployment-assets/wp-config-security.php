<?php
/**
 * =====================================================================
 * KINGS GROUP — PRODUCTION wp-config.php HARDENING DIRECTIVES
 * =====================================================================
 * 
 * Instructions: Copy the code snippets below into your actual, live
 * wp-config.php file (usually placed just before the "Happy publishing" line).
 */

// 1. DISABLE INTERMEDIATE CODE EDITING
// Disables the built-in theme and plugin editors in WP Admin.
// Highly recommended to prevent attackers from executing code if an admin account is compromised.
define('DISALLOW_FILE_EDIT', true);

// 2. FORCE SSL FOR ADMIN PORTAL
// Ensures all administrator logins and dashboard activities are strictly encrypted over HTTPS.
define('FORCE_SSL_ADMIN', true);

// 3. SECURE ERROR LOGGING (NO PUBLIC EXPOSURE)
// Disables public output of errors (which might leak directory structures or database prefixes)
// and redirects them quietly to a private debug.log file.
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);

// 4. PRODUCTION DATABASE AUTOMATIC REPAIRS CONTROL
// Prevents unauthenticated users from executing repair scripts (only enable if active troubleshooting is required).
define('WP_ALLOW_DATABASE_REPAIR', false);

// 5. PRODUCTION SMTP CONFIGURATION (MAILING BACKBONE)
// Bypasses local mail delivery issues and handles transactional messages securely via SMTP.
// These map directly to the custom phpmailer_init hook in your theme's functions.php.
define('KG_SMTP_HOST', 'smtp.gmail.com');             // Replace with your SMTP server host
define('KG_SMTP_PORT', 587);                          // 587 (TLS) or 465 (SSL)
define('KG_SMTP_USER', 'rhonjames95@gmail.com');      // Your Gmail SMTP username
define('KG_SMTP_PASS', 'ufoo fzql surd zzag');    // Replace with your Google App Password for rhonjames95@gmail.com
define('KG_SMTP_FROM', 'rhonjames95@gmail.com');      // Send-from address (must match SMTP user)
define('KG_SMTP_FROMNAME', 'Kings Group Notification');   // Display name for emails
define('KG_INQUIRY_EMAIL', 'rhonjames95@gmail.com');      // General contact inquiries recipient email
define('KG_CAREER_EMAIL', 'rhonjames95@gmail.com');      // Careers/Applications recipient email
define('KG_QUOTE_EMAIL', 'rhonjames95@gmail.com');      // Team Builder/Quote requests recipient email
define('KG_ADMIN_EMAIL', 'rhonjames95@gmail.com');      // Master fallback admin recipient email
