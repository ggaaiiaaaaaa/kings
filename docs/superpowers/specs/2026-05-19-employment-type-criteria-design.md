# Employment Type Application Criteria Design

## Purpose
To create a dynamic application experience that tailors the required information and automated communication based on the specific employment type (Full-Time, Part-Time, Contract, Remote) of the job the candidate is applying for.

## Architecture & Approach
We will use a "Hybrid" approach, modifying both the front-end application form to dynamically show/hide fields, and the back-end form handler to route different email templates and save the new metadata.

### 1. Front-End Form Dynamics (`careers.php`)
The application form will contain hidden HTML blocks for each employment type's specific criteria. We will add JavaScript that listens for changes to the "Preferred Role" dropdown. 

When a role is selected, an AJAX call (or pre-loaded data attribute) will determine the job's `job_type`. The JS will then reveal the corresponding field block:

*   **Full-Time Block:**
    *   `app_notice_period`: Text input (How soon can you start?)
    *   `app_expected_salary`: Number input (Expected Monthly Salary in PHP)
*   **Part-Time Block:**
    *   `app_available_hours`: Select (10-20, 20-30, 30+)
    *   `app_shift_pref`: Select (Morning, Evening, Night, Weekends, Flexible)
*   **Contract Block:**
    *   `app_portfolio`: URL input (Portfolio / Past Work Link)
    *   `app_tin`: Text input (TIN / Business Registration Number)
*   **Remote Block:**
    *   `app_internet_speed`: Number input (Internet Speed in Mbps)
    *   `app_backup_power`: Select (Yes / No)

### 2. Backend Form Handling (`inc/form-handlers.php`)
The `kg_handle_application()` function will be updated to:
1.  **Retrieve Job Type:** Look up the `job_type` meta field for the selected role ID.
2.  **Sanitize New Fields:** Capture and sanitize all the new dynamic fields from `$_POST`.
3.  **Email Routing:** Instead of a single `$reply_body`, use a `switch($job_type)` statement to generate an employment-type-specific acknowledgment email.
    *   *Full-Time:* Highlights company culture, benefits, and long-term career growth.
    *   *Part-Time:* Highlights flexibility and scheduling next steps.
    *   *Contract:* Highlights project deliverables, invoicing procedures, and immediate onboarding.
    *   *Remote:* Highlights communication protocols, time-tracking software, and remote work policies.
4.  **Admin Notification:** The email sent to HR will include the newly collected dynamic criteria so they have all the context.

### 3. Data Storage (`inc/cpt-applications.php`)
The `kg_save_application_post()` function must be updated to accept the new dynamic fields in the `$data` array and save them using `update_post_meta()` (e.g., `kg_app_notice_period`, `kg_app_internet_speed`).

The admin UI (`kg_application_details_box`) will be updated to conditionally display these new meta fields if they exist, so HR can view them directly on the applicant's profile page.

## Success Criteria
- The application form dynamically changes based on the selected job's employment type.
- Different auto-reply emails are sent based on the employment type.
- HR receives the dynamic criteria in their notification email.
- The new criteria are successfully saved to the WordPress database and visible in the admin panel.
