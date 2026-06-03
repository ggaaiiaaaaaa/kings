<?php
/* Template Name: Privacy Policy */
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
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
    <section class="page-hero" style="padding: 8rem 2rem 4rem; min-height: auto;">
        <div class="container" style="text-align: center;">
            <h1 style="font-size: clamp(1.8rem, 3.5vw, 2.8rem);">Privacy Policy</h1>
            <p style="color: rgba(255,255,255,0.65); margin-top: 0.75rem;">Last updated: <?php echo date('F j, Y'); ?></p>
        </div>
    </section>

    <!-- Content -->
    <section class="section section-bg-white">
        <div class="container" style="max-width: 800px;">
            <div class="legal-content animate-on-scroll">

                <p>Kings Group Cooperative ("Kings Group," "we," "us," "our") is committed to protecting the privacy and security of your personal information. This Privacy Policy explains how we collect, use, store, and share information when you use our website and services, in compliance with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong> of the Republic of the Philippines.</p>

                <h2>1. Information We Collect</h2>
                <p>We may collect the following categories of personal information:</p>
                <h3>a) Information You Provide</h3>
                <ul>
                    <li><strong>Contact Information:</strong> Name, email address, phone number</li>
                    <li><strong>Application Data:</strong> CV/resume, employment history, education, LinkedIn profile, preferred role</li>
                    <li><strong>Client Inquiry Data:</strong> Company name, work email, team configuration preferences</li>
                </ul>
                <h3>b) Information Collected Automatically</h3>
                <ul>
                    <li>IP address, browser type, device information</li>
                    <li>Pages visited, time spent on site, referral source</li>
                    <li>Cookies and similar tracking technologies</li>
                </ul>

                <h2>2. How We Use Your Information</h2>
                <p>We process your personal information for the following lawful purposes:</p>
                <ul>
                    <li><strong>Recruitment:</strong> Evaluating your qualifications and matching you with suitable career opportunities</li>
                    <li><strong>Client Services:</strong> Preparing service proposals and facilitating business engagements</li>
                    <li><strong>Communication:</strong> Sending application acknowledgments, status updates, and relevant correspondence</li>
                    <li><strong>Legal Compliance:</strong> Meeting our obligations under Philippine labor, tax, and data privacy regulations</li>
                    <li><strong>Site Improvement:</strong> Analyzing usage patterns to enhance user experience and functionality</li>
                </ul>

                <h2>3. Data Sharing & Third Parties</h2>
                <p>We do not sell your personal information. We may share data with:</p>
                <ul>
                    <li><strong>Client Partners:</strong> Your application data may be shared with prospective employers for roles you have applied to</li>
                    <li><strong>Service Providers:</strong> Email delivery services, cloud hosting, and IT infrastructure providers who process data on our behalf under strict confidentiality agreements</li>
                    <li><strong>Government Authorities:</strong> When required by law, regulation, or legal process</li>
                </ul>

                <h2>4. Data Retention</h2>
                <p>We retain your personal information only for as long as necessary to fulfill the purposes described in this policy, or as required by law. Application data is typically retained for up to <strong>three (3) years</strong> from the date of submission to facilitate future job matching opportunities.</p>

                <h2>5. Data Security</h2>
                <p>We implement industry-standard technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These include:</p>
                <ul>
                    <li>HTTPS/TLS encryption for all data in transit</li>
                    <li>Secure server infrastructure with access controls</li>
                    <li>Regular security audits and vulnerability assessments</li>
                    <li>Employee confidentiality training and data handling protocols</li>
                </ul>

                <h2>6. Your Rights</h2>
                <p>Under the Data Privacy Act of 2012, you have the right to:</p>
                <ul>
                    <li><strong>Access</strong> your personal data held by us</li>
                    <li><strong>Correct</strong> inaccurate or incomplete information</li>
                    <li><strong>Object</strong> to the processing of your data for specific purposes</li>
                    <li><strong>Erasure</strong> of your data when it is no longer necessary for the purpose collected</li>
                    <li><strong>Data Portability</strong> — receive your data in a structured, commonly used format</li>
                    <li><strong>Lodge a complaint</strong> with the National Privacy Commission (NPC)</li>
                </ul>

                <h2>7. Cookies</h2>
                <p>Our Site uses cookies and similar technologies to enhance your browsing experience. You may control cookie settings through your browser preferences. Disabling cookies may limit certain features of the Site.</p>

                <h2>8. Children's Privacy</h2>
                <p>Our services are not directed to individuals under 18 years of age. We do not knowingly collect personal information from minors. If we discover that we have inadvertently collected data from a minor, we will promptly delete it.</p>

                <h2>9. Changes to This Policy</h2>
                <p>We may update this Privacy Policy from time to time. The revised policy will be posted on this page with an updated "Last updated" date. We encourage you to review this policy periodically.</p>

                <h2>10. Contact Us</h2>
                <p>For privacy-related inquiries, data access requests, or complaints, please contact our Data Protection Officer:</p>
                <ul>
                    <li><strong>Email:</strong> privacy@kingsgroup.com.ph</li>
                    <li><strong>Phone:</strong> +63 (2) 8776-6712</li>
                    <li><strong>Address:</strong> 100 Doña Soledad Ave, Better Living Subdivision, Parañaque City 1711, Metro Manila, Philippines</li>
                </ul>

                <p style="margin-top: 2rem; padding: 1.25rem; background: var(--bg-light); border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.9rem; color: var(--text-muted);">
                    You may also file a complaint with the <strong>National Privacy Commission</strong> at <a href="https://www.privacy.gov.ph" target="_blank" rel="noopener" style="color: var(--main-blue);">www.privacy.gov.ph</a>.
                </p>

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
