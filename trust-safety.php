<?php
/* Template Name: Trust & Safety */
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_hero_bg     = kg_get_field('trust_bg', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=2000&q=80');
$trust_headline   = kg_get_field('trust_headline', 'Trust & Safety');
$trust_desc       = kg_get_field('trust_desc', 'Our commitments to absolute compliance and ethical talent management.');
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
    <section class="page-hero" style="padding: 8rem 2rem 4rem; min-height: auto; <?php echo !empty($page_hero_bg) ? 'background-image: linear-gradient(rgba(10, 37, 64, 0.75), rgba(10, 37, 64, 0.85)), url(\'' . esc_url($page_hero_bg) . '\');' : ''; ?>">
        <div class="container" style="text-align: center;">
            <h1 style="font-size: clamp(1.8rem, 3.5vw, 2.8rem);"><?php echo esc_html($trust_headline); ?></h1>
            <p style="color: rgba(255,255,255,0.65); margin-top: 0.75rem;"><?php echo esc_html($trust_desc); ?></p>
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
                    <p>Since 1999, Kings Group Cooperative has served as a trusted partner for global organizations and elite professionals. As a legitimate worker-owned cooperative registered with the <strong>Cooperative Development Authority (CDA)</strong>, we uphold the highest standards of regulatory compliance, digital security, and ethical labor practices. We are dedicated to creating a safe, transparent, and compliant ecosystem for both our client partners and cooperative members.</p>

                    <h2>1. Ethical Staffing & Zero Recruitment Fees</h2>
                    <p>As a true worker-owned cooperative, our business model centers on the mutual benefit and protection of our members. We strictly enforce the following policies:</p>
                    <ul>
                        <li><strong>No Recruitment Fees:</strong> We never charge applicants for placement, documentation, processing, training, background checks, or onboarding kits at any point before, during, or after the recruitment process.</li>
                        <li><strong>Guaranteed Fair Compensation:</strong> All members receive compensation packages exceeding statutory regional minimum wage orders, complete with all legally mandated benefits (SSS, PhilHealth, Pag-IBIG), 13th-month pay, and annual cooperative patronage refunds (dividends).</li>
                        <li><strong>Full DOLE Compliance:</strong> We operate in strict adherence to the Department of Labor and Employment (DOLE) rules, maintaining active registration under <strong>Department Order No. 174 (D.O. 174)</strong>. This guarantees that all services rendered represent legitimate job contracting and avoid prohibited labor-only contracting schemes.</li>
                    </ul>

                    <h2>2. Secure Client IT & Data Operations</h2>
                    <p>We implement stringent security standards to protect our clients' intellectual property and proprietary data assets:</p>
                    <ul>
                        <li><strong>Binding Non-Disclosure Agreements (NDAs):</strong> Every cooperative member undergoes security training and signs a comprehensive, legally binding NDA before being deployed to any client project.</li>
                        <li><strong>Secure Remote Workspace Audits:</strong> For remote or offshore roles, members must comply with strict work-from-home security standards, including clean-desk policies, restricted local administrative access, and dedicated secure VPN tunnels.</li>
                        <li><strong>Endpoint Security Controls:</strong> Client-facing computers are loaded with enterprise-grade endpoint protection software, regular security patch updates, and USB data port locks where requested by the client.</li>
                        <li><strong>Physical Security:</strong> Our physical centers feature secure badge access, round-the-clock security monitoring, and restricted device zones for highly sensitive tasks.</li>
                    </ul>

                    <h2>3. Fraud & Recruitment Phishing Alert</h2>
                    <p>Cybercriminals and bad actors occasionally target job seekers by impersonating reputable companies like Kings Group. Protect yourself by keeping these safety rules in mind:</p>
                    <ul>
                        <li><strong>Verified Domains Only:</strong> All official emails from our recruitment team originate strictly from the <strong>@kingsgroup.com.ph</strong> domain name. We never use generic domains (like `@gmail.com`, `@outlook.com`) or messaging apps (like WhatsApp, Telegram, Viber) as our initial mode of formal communication for job offers.</li>
                        <li><strong>No Financial Transactions:</strong> Kings Group recruitment officers will never ask you to send money, transfer cryptocurrency, purchase training materials, or pay for equipment during onboarding.</li>
                        <li><strong>Official Job Portals:</strong> Always verify job postings on our official website: <a href="https://kingsgroup.com.ph/careers" target="_blank" rel="noopener" style="color: var(--main-blue); text-decoration: underline;">kingsgroup.com.ph/careers</a> or our verified social media channels.</li>
                    </ul>

                    <h2>4. Whistleblower & Incident Reporting</h2>
                    <p>If you suspect fraudulent job listings, ethical violations, safety concerns, or compliance anomalies, we encourage you to report them immediately to our compliance division. We handle all reports with strict confidentiality and enforce a zero-tolerance policy against retaliation of any kind:</p>
                    <ul>
                        <li><strong>Email:</strong> compliance@kingsgroup.com.ph</li>
                        <li><strong>Alternative Legal Email:</strong> legal@kingsgroup.com.ph</li>
                        <li><strong>Compliance Line:</strong> +63 (2) 8776-6712</li>
                        <li><strong>Zamboanga HQ Address:</strong> Compliance & Risk Division, Kings Group Cooperative, DVN Building, Melaño Calixto St, Zamboanga City, Philippines</li>
                        <li><strong>Manila Office Address:</strong> Compliance & Risk Division, Kings Group Cooperative, 100 Doña Soledad Ave, Better Living Subdivision, Parañaque City 1711, Metro Manila, Philippines</li>
                    </ul>

                    <p style="margin-top: 2rem; padding: 1.25rem; background: var(--bg-light); border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.9rem; color: var(--text-muted);">
                        All reports are investigated fully by our Compliance & Risk Committee. We cooperate with the National Bureau of Investigation (NBI) and local police authorities to prosecute recruitment fraud and phishing operations.
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
