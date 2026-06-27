<?php
/* Template Name: Privacy Policy */
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_hero_bg     = kg_get_field('privacy_bg', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=2000&q=80');
$privacy_headline = kg_get_field('privacy_headline', 'Privacy Policy');
$privacy_desc     = kg_get_field('privacy_desc', 'Last updated: ' . date('F j, Y'));
$page_title       = 'Privacy Policy | Kings Group Cooperative';
$page_description = 'Learn how Kings Group Cooperative collects, uses, and protects your personal information in compliance with Philippine data privacy laws.';

$page_schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'WebPage',
    '@id'         => 'https://kingsgroup.com.ph/privacy/#webpage',
    'url'         => 'https://kingsgroup.com.ph/privacy/',
    'name'        => 'Privacy Policy | Kings Group Cooperative',
    'description' => 'Privacy Policy detailing how Kings Group Cooperative handles your personal data.',
    'isPartOf'    => [ '@id' => 'https://kingsgroup.com.ph/#website' ],
];

get_header();
?>

    <!-- Hero -->
    <section class="page-hero" style="padding: 8rem 2rem 4rem; min-height: auto; <?php echo !empty($page_hero_bg) ? 'background-image: linear-gradient(rgba(10, 37, 64, 0.75), rgba(10, 37, 64, 0.85)), url(\'' . esc_url($page_hero_bg) . '\');' : ''; ?>">
        <div class="container" style="text-align: center;">
            <h1 style="font-size: clamp(1.8rem, 3.5vw, 2.8rem);"><?php echo esc_html($privacy_headline); ?></h1>
            <p style="color: rgba(255,255,255,0.65); margin-top: 0.75rem;"><?php echo esc_html($privacy_desc); ?></p>
        </div>
    </section>

    <!-- Content -->
    <section class="section section-bg-white">
        <div class="container" style="max-width: 800px;">
            <div class="legal-content animate-on-scroll">
                <?php
                $wp_content = '';
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        $wp_content = get_the_content();
                    }
                }
                
                if (!empty(trim($wp_content))) {
                    echo apply_filters('the_content', $wp_content);
                } else {
                    ?>
                    <p>Kings Group Cooperative ("Kings Group," "we," "us," "our") is fully committed to safeguarding the privacy and security of your personal data. This Privacy Policy details how we collect, process, utilize, share, retain, and dispose of your personal information when you visit our website, utilize our job boards, submit applications through our applicant tracking systems (ATS), or request service quotes, all in accordance with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong> of the Republic of the Philippines, its Implementing Rules and Regulations (IRR), and other relevant data privacy frameworks.</p>

                    <h2>1. Information We Collect</h2>
                    <p>In the course of providing recruitment, talent pooling, and business solutions, we collect both personal information and non-personal diagnostic data. The scope of information depends on your interactions with the Site:</p>
                    
                    <h3>a) Job Applicants & Careers Portal (ATS)</h3>
                    <ul>
                        <li><strong>Identity Data:</strong> Full name, date of birth, gender, nationality, and digital signature.</li>
                        <li><strong>Contact Credentials:</strong> Home address, telephone number, mobile number, and personal email address.</li>
                        <li><strong>Professional & Academic History:</strong> Full curriculum vitae (CV/resume), educational credentials, employment background, certifications, technical skill proficiencies, professional references, and LinkedIn profile URL.</li>
                        <li><strong>Application Metadata:</strong> Target positions, desired salary compensation, geographic preference, and application timestamp.</li>
                        <li><strong>Status and Logs:</strong> Recruitment stage tracking (screening, interviewing, deployed, or pooling/benched) and corresponding communication history.</li>
                    </ul>

                    <h3>b) Client Inquiries & Quote Requestors</h3>
                    <ul>
                        <li><strong>Professional Contact Data:</strong> Corporate name, business address, work email address, and direct corporate phone line.</li>
                        <li><strong>Operational Specifications:</strong> Department size needs, required employee headcount, service terms, billing preferences, and currency selection.</li>
                    </ul>

                    <h3>c) Automated & Diagnostic Data</h3>
                    <ul>
                        <li><strong>Technical Log Data:</strong> Internet Protocol (IP) address, browser client user-agent string, operating system version, and system language preferences.</li>
                        <li><strong>Clickstream Analytics:</strong> Referral traffic sources, specific pages visited on our Site, duration of visits, scroll depths, and interaction paths.</li>
                        <li><strong>Location Geolocation Data:</strong> Country level geo-routing parameters deduced via HTTP query arguments (`?geo=`), Cloudflare IP headers (`CF-IPCountry`), cookie markers, or dynamic IP geolocation APIs.</li>
                    </ul>

                    <h2>2. Legal Basis & How We Use Your Information</h2>
                    <p>We process your data based on your explicit consent, contractual necessity, or legitimate interest, specifically for the following purposes:</p>
                    <ul>
                        <li><strong>Recruitment Processing:</strong> Reviewing and evaluating applicant qualifications, matching candidate profiles with appropriate job vacancies, and scheduling recruitment interviews.</li>
                        <li><strong>Talent Pooling System:</strong> Retaining candidate details in our pooling database to match with future job opportunities. This placement is strictly bound by our application cooldown filters (such as the 14-day re-application cooldown) to ensure data sanity.</li>
                        <li><strong>Dynamic Notification Services:</strong> Dispatching automated emails and status updates regarding application pipelines (such as transitions to screening, interviewing, or pooling).</li>
                        <li><strong>B2B Quote Coordination:</strong> Processing client requests, calculating workforce pricing options, and drafting formal service agreement proposals.</li>
                        <li><strong>System Optimization:</strong> Monitoring site traffic and diagnosing server workloads to protect the integrity of the Site.</li>
                        <li><strong>Compliance & Regulatory Reporting:</strong> Meeting legal obligations mandated by the Cooperative Development Authority (CDA), Department of Labor and Employment (DOLE) including D.O. 174 compliance, National Privacy Commission (NPC), and tax authorities.</li>
                    </ul>

                    <h2>3. Information Sharing & Third-Party Disclosures</h2>
                    <p>Kings Group Cooperative strictly enforces a zero-sale policy on personal data. We do not sell or lease candidate details. However, we may share relevant personal data under secure protocols with:</p>
                    <ul>
                        <li><strong>Prospective Client Partners:</strong> Sharing resumes, skill matrices, and candidate profiles with verified client organizations looking to fill specific professional positions.</li>
                        <li><strong>Service Contracting Subcontractors:</strong> Partnering with third-party service providers (such as email dispatch networks, cloud hosting servers, and analytical systems) who act strictly as Data Processors under legally binding Data Processing Agreements.</li>
                        <li><strong>Legal & Regulatory Authorities:</strong> Providing data in response to official legal warrants, court subpoenas, regulatory audits, or where disclosure is mandated by law to prevent fraud or coordinate safety operations.</li>
                    </ul>

                    <h2>4. Data Retention & Secure Disposal</h2>
                    <p>We establish clear retention criteria to minimize data footprints:</p>
                    <ul>
                        <li><strong>Applicant Records:</strong> Active application and pooling data is securely archived for up to <strong>three (3) years</strong>. After this duration, records are automatically queued for deletion unless consent is renewed.</li>
                        <li><strong>Security Logs:</strong> Basic diagnostic server logs are purged after ninety (90) days.</li>
                        <li><strong>Data Destruction Protocols:</strong> Digital files, including uploaded PDFs and CV documents, are overwritten and permanently erased using secure file-deletion commands. Physical documentation, if any, is destroyed via cross-cut document shredders.</li>
                    </ul>

                    <h2>5. Technical & Organizational Security Measures</h2>
                    <p>We apply robust administrative, physical, and technical safeguards to keep your personal data secure:</p>
                    <ul>
                        <li><strong>Data in Transit:</strong> The website forces secure connections using HTTPS with TLS 1.3 encryption protocols.</li>
                        <li><strong>Private Upload Gating:</strong> Uploaded CVs are written to a restricted server directory (`/wp-content/uploads/secure-cvs`) gated by strict `.htaccess` rules preventing direct public access.</li>
                        <li><strong>Authenticated Downloads:</strong> Access to applicant files is restricted to authorized recruitment teams. CV files are fetched via secure, temporary, single-use signed download links.</li>
                        <li><strong>Access Control:</strong> Administrative user roles use the principle of least privilege, requiring secure credentials to view talent pipelines.</li>
                    </ul>

                    <h2>6. Your Rights Under Philippine Data Privacy Law</h2>
                    <p>As a data subject, you hold comprehensive rights under the Data Privacy Act of 2012, which we respect and facilitate:</p>
                    <ul>
                        <li><strong>Right to be Informed:</strong> Knowing whether your data is being processed, collected, or shared.</li>
                        <li><strong>Right to Access:</strong> Requesting a copy of the personal information we hold about you.</li>
                        <li><strong>Right to Object:</strong> Objecting to the processing of your data, including processing for marketing or profiling.</li>
                        <li><strong>Right to Rectification:</strong> Requiring us to correct inaccurate or outdated records.</li>
                        <li><strong>Right to Erasure (Blocking):</strong> Ordering the removal or destruction of your personal data from our systems under lawful grounds.</li>
                        <li><strong>Right to Damages:</strong> Seeking compensation for damages sustained due to inaccurate, incomplete, or unauthorized use of personal data.</li>
                        <li><strong>Right to Data Portability:</strong> Obtaining your data in a structured, portable electronic format.</li>
                    </ul>

                    <h2>7. Cookie & Tracking Technologies</h2>
                    <p>Our website utilizes session and persistent cookies to remember user language preferences, track geo-routing options, manage consent modal flags (such as the 30-day legal consent gate cookie), and assess traffic patterns. You may configure your browser to block or refuse cookies; however, some sections of the Site may not load correctly as a result.</p>

                    <h2>8. Children's Privacy</h2>
                    <p>The recruitment and corporate services offered on this Site are strictly directed to individuals aged 18 and older. We do not knowingly compile or store data from minors. If you believe a minor has submitted personal info, contact us, and we will delete the data immediately.</p>

                    <h2>9. Policy Amendments</h2>
                    <p>We reserves the right to revise this Privacy Policy to reflect changing regulatory requirements or platform upgrades. Material modifications will be highlighted on our Site and dated accordingly.</p>

                    <h2>10. Data Protection Officer (DPO) Contact Details</h2>
                    <p>For inquiries, exercise of data subject rights, or privacy-related complaints, please reach out to our Data Protection Officer:</p>
                    <ul>
                        <li><strong>Email:</strong> privacy@kingsgroup.com.ph</li>
                        <li><strong>Alternative Contact:</strong> compliance@kingsgroup.com.ph</li>
                        <li><strong>Landline:</strong> +63 (2) 8776-6712</li>
                        <li><strong>Zamboanga HQ Address:</strong> DVN Building, Melaño Calixto St, Zamboanga City, Philippines</li>
                        <li><strong>Manila Office Address:</strong> 100 Doña Soledad Ave, Better Living Subdivision, Parañaque City 1711, Metro Manila, Philippines</li>
                    </ul>

                    <p style="margin-top: 2rem; padding: 1.25rem; background: var(--bg-light); border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.9rem; color: var(--text-muted);">
                        You may also file formal complaints regarding data privacy issues with the <strong>National Privacy Commission (NPC)</strong> at their official portal: <a href="https://www.privacy.gov.ph" target="_blank" rel="noopener" style="color: var(--main-blue); text-decoration: underline;">www.privacy.gov.ph</a>.
                    </p>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>

<style>
    .legal-content h2 {
        font-size: 1.15rem;
        color: var(--main-blue);
        margin-top: 2.5rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border-color);
    }
    .legal-content h2:first-child {
        margin-top: 0;
    }
    .legal-content h3 {
        font-size: 1rem;
        color: var(--text-dark);
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .legal-content p {
        color: var(--text-body);
        line-height: 1.8;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }
    .legal-content ul {
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .legal-content li {
        color: var(--text-body);
        line-height: 1.7;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }
</style>

<?php get_footer(); ?>
