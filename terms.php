<?php
/* Template Name: Terms of Service */
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_title       = 'Terms of Service | Kings Group Cooperative';
$page_description = 'Read the Terms of Service governing your use of the Kings Group Cooperative website and services.';

$page_schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'WebPage',
    '@id'         => 'https://kingsgroup.com.ph/terms/#webpage',
    'url'         => 'https://kingsgroup.com.ph/terms/',
    'name'        => 'Terms of Service | Kings Group Cooperative',
    'description' => 'Terms of Service governing the use of Kings Group Cooperative website and services.',
    'isPartOf'    => [ '@id' => 'https://kingsgroup.com.ph/#website' ],
];

get_header();
?>

    <!-- Hero -->
    <section class="page-hero" style="padding: 8rem 2rem 4rem; min-height: auto;">
        <div class="container" style="text-align: center;">
            <h1 style="font-size: clamp(1.8rem, 3.5vw, 2.8rem);">Terms of Service</h1>
            <p style="color: rgba(255,255,255,0.65); margin-top: 0.75rem;">Last updated: <?php echo date('F j, Y'); ?></p>
        </div>
    </section>

    <!-- Content -->
    <section class="section section-bg-white">
        <div class="container" style="max-width: 800px;">
            <div class="legal-content animate-on-scroll">

                <h2>1. Acceptance of Terms</h2>
                <p>By accessing and using the Kings Group Cooperative website ("Site"), you acknowledge that you have read, understood, and agree to be bound by these Terms of Service ("Terms"). If you do not agree with any part of these Terms, you must not use the Site.</p>

                <h2>2. Description of Services</h2>
                <p>Kings Group Cooperative ("Kings Group," "we," "us") provides workforce solutions, talent acquisition, managed services, and related staffing solutions through this Site. The Site also serves as a platform for job seekers to discover career opportunities and submit applications.</p>

                <h2>3. User Eligibility</h2>
                <p>You must be at least 18 years of age to use the Site's application and recruitment services. By using these services, you represent and warrant that you meet this age requirement and have the legal capacity to enter into a binding agreement.</p>

                <h2>4. User Accounts & Applications</h2>
                <p>When submitting an application or inquiry through the Site, you agree to provide accurate, current, and complete information. You are responsible for the accuracy of all data submitted, including your CV, contact details, and professional qualifications.</p>

                <h2>5. Intellectual Property</h2>
                <p>All content on the Site — including but not limited to text, graphics, logos, images, software, and design — is the property of Kings Group Cooperative or its licensors and is protected by applicable intellectual property laws. You may not reproduce, distribute, or create derivative works from this content without our prior written consent.</p>

                <h2>6. Prohibited Conduct</h2>
                <p>You agree not to:</p>
                <ul>
                    <li>Submit false, misleading, or fraudulent information through any form on the Site</li>
                    <li>Use automated scripts, bots, or crawlers to access or interact with the Site</li>
                    <li>Attempt to gain unauthorized access to the Site's backend systems or databases</li>
                    <li>Upload or transmit any harmful code, viruses, or malware</li>
                    <li>Impersonate any person, entity, or Kings Group representative</li>
                </ul>

                <h2>7. Limitation of Liability</h2>
                <p>To the maximum extent permitted by Philippine law, Kings Group Cooperative shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits, data, or use, arising out of or related to your use of the Site or our services.</p>

                <h2>8. Third-Party Links</h2>
                <p>The Site may contain links to external websites operated by third parties. Kings Group does not control or endorse these third-party sites and is not responsible for their content, privacy practices, or terms of service.</p>

                <h2>9. Governing Law</h2>
                <p>These Terms shall be governed by and construed in accordance with the laws of the Republic of the Philippines. Any disputes arising from these Terms shall be subject to the exclusive jurisdiction of the courts of Parañaque City, Metro Manila.</p>

                <h2>10. Changes to Terms</h2>
                <p>We reserve the right to modify these Terms at any time. Changes will be effective upon posting to the Site. Your continued use of the Site after any such changes constitutes your acceptance of the revised Terms.</p>

                <h2>11. Contact Information</h2>
                <p>For questions regarding these Terms of Service, please contact us:</p>
                <ul>
                    <li><strong>Email:</strong> legal@kingsgroup.com.ph</li>
                    <li><strong>Phone:</strong> +63 (2) 8776-6712</li>
                    <li><strong>Address:</strong> 100 Doña Soledad Ave, Better Living Subdivision, Parañaque City 1711, Metro Manila, Philippines</li>
                </ul>

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
