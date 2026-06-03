<?php
/* Template Name: Trust & Safety */
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_title       = 'Trust & Safety | Kings Group Cooperative';
$page_description = 'Learn how Kings Group Cooperative ensures elite staffing compliance, secure data operations, and worker protection.';

$page_schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'WebPage',
    '@id'         => 'https://kingsgroup.com.ph/trust-safety/#webpage',
    'url'         => 'https://kingsgroup.com.ph/trust-safety/',
    'name'        => 'Trust & Safety | Kings Group Cooperative',
    'description' => 'Trust & Safety portal detailing compliance, data security, and ethical staffing standards.',
    'isPartOf'    => [ '@id' => 'https://kingsgroup.com.ph/#website' ],
];

get_header();
?>

    <!-- Hero -->
    <section class="page-hero" style="padding: 8rem 2rem 4rem; min-height: auto;">
        <div class="container" style="text-align: center;">
            <h1 style="font-size: clamp(1.8rem, 3.5vw, 2.8rem);">Trust & Safety</h1>
            <p style="color: rgba(255,255,255,0.65); margin-top: 0.75rem;">Our commitments to absolute compliance and ethical talent management.</p>
        </div>
    </section>

    <!-- Content -->
    <section class="section section-bg-white">
        <div class="container" style="max-width: 800px;">
            <div class="legal-content animate-on-scroll">

                <p>Since 1999, Kings Group Cooperative has served as a trusted partner for global organizations and elite professionals. As a legitimate worker-owned cooperative registered with the <strong>Cooperative Development Authority (CDA)</strong>, we uphold the highest standards of regulatory compliance, digital security, and ethical labor practices.</p>

                <h2>1. Ethical Staffing & Zero Recruitment Fees</h2>
                <p>We are a worker-owned cooperative, not a traditional agency. We strictly enforce the following protection policies:</p>
                <ul>
                    <li><strong>No Recruitment Fees:</strong> We never charge applicants placement, processing, or training fees at any point in the hiring process.</li>
                    <li><strong>Ethical Compensation:</strong> All members receive competitive compensation packages exceeding regional wage orders, complete statutory benefits, and annual dividend distributions.</li>
                    <li><strong>DOLE Compliance:</strong> We maintain full compliance with the Department of Labor and Employment (DOLE) regulations, including Department Order No. 174 (D.O. 174) for legitimate service contracting.</li>
                </ul>

                <h2>2. Secure Client Operations</h2>
                <p>To protect client intellectual property and data assets, our standard operating framework includes:</p>
                <ul>
                    <li><strong>Comprehensive NDAs:</strong> All cooperative members sign legally binding Non-Disclosure Agreements prior to project deployment.</li>
                    <li><strong>Secure Workspaces:</strong> Clean desk policies, multi-factor authentication requirements, and secure remote network endpoints (VPNs).</li>
                    <li><strong>Endpoint Security:</strong> Managed enterprise-grade antivirus software, regular patch installations, and USB port locks where requested.</li>
                </ul>

                <h2>3. Fraud Alert & Protection</h2>
                <p>Cybercriminals occasionally impersonate reputable agencies. Please protect yourself by verifying these details:</p>
                <ul>
                    <li><strong>Official Emails Only:</strong> All official communications from our recruitment team originate strictly from <strong>@kingsgroup.com.ph</strong> domains. We do not use Telegram, WhatsApp, or personal Gmail addresses for job offers.</li>
                    <li><strong>No Financial Transactions:</strong> Kings Group recruitment officers will never ask you to send money, buy equipment, or share bank account credentials during your onboarding process.</li>
                    <li><strong>Official Portal:</strong> Applications must only be submitted through our verified portal at <strong>kingsgroup.com.ph/careers</strong> or through recognized local channels.</li>
                </ul>

                <h2>4. Incident Reporting</h2>
                <p>If you suspect fraudulent activity, compliance violations, or security anomalies, please report them to our compliance officers immediately:</p>
                <ul>
                    <li><strong>Email:</strong> compliance@kingsgroup.com.ph</li>
                    <li><strong>Hotline:</strong> +63 (2) 8776-6712</li>
                    <li><strong>Address:</strong> Compliance & Risk Division, Kings Group Cooperative, 100 Doña Soledad Ave, Better Living, Parañaque City, Metro Manila, Philippines</li>
                </ul>

                <p style="margin-top: 2rem; padding: 1.25rem; background: var(--bg-light); border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.9rem; color: var(--text-muted);">
                    All reports are handled with absolute confidentiality in strict compliance with the Data Privacy Act of 2012.
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
