<?php
/* Template Name: Terms of Service */
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_hero_bg     = kg_get_field('terms_bg', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=2000&q=80');
$terms_headline   = kg_get_field('terms_headline', 'Terms of Service');
$terms_desc       = kg_get_field('terms_desc', 'Last updated: ' . date('F j, Y'));
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
    <section class="page-hero" style="padding: 8rem 2rem 4rem; min-height: auto; <?php echo !empty($page_hero_bg) ? 'background-image: linear-gradient(rgba(10, 37, 64, 0.75), rgba(10, 37, 64, 0.85)), url(\'' . esc_url($page_hero_bg) . '\');' : ''; ?>">
        <div class="container" style="text-align: center;">
            <h1 style="font-size: clamp(1.8rem, 3.5vw, 2.8rem);"><?php echo esc_html($terms_headline); ?></h1>
            <p style="color: rgba(255,255,255,0.65); margin-top: 0.75rem;"><?php echo esc_html($terms_desc); ?></p>
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
                    <h2>1. Acceptance of Terms</h2>
                    <p>By accessing, browsing, or using the Kings Group Cooperative website ("Site"), you acknowledge that you have read, understood, and agree to be bound by these Terms of Service ("Terms") along with our Privacy Policy and Trust & Safety Guidelines. If you do not agree to these Terms, you must immediately cease all access and use of the Site. These Terms constitute a binding legal agreement between you and Kings Group Cooperative.</p>

                    <h2>2. Description of Services & Site Scope</h2>
                    <p>Kings Group Cooperative ("Kings Group," "we," "us," "our") is a registered worker-owned cooperative providing specialized workforce solutions, managed services, recruitment contracting, and business process outsourcing solutions. The Site provides information regarding our staffing options, client service request forms (including our interactive quote builder), dynamic job boards (our careers and jobs listing portals), and applicant tracking systems (ATS) through which prospective candidates can submit application profiles.</p>

                    <h2>3. User Representation & Eligibility</h2>
                    <p>By accessing the Site or utilizing any application or inquiry services, you represent and warrant that:</p>
                    <ul>
                        <li>You are at least eighteen (18) years of age or have reached the legal age of majority in your jurisdiction.</li>
                        <li>You possess the legal capacity and authority to enter into these binding Terms.</li>
                        <li>All information you submit to the Site (including CVs, certificates, references, portfolios, and contact details) is genuine, accurate, and completely truthful.</li>
                        <li>Your use of the Site does not violate any applicable local, national, or international laws or regulations.</li>
                    </ul>

                    <h2>4. Applicant & Recruitment Submissions (ATS)</h2>
                    <p>When you submit an application, upload a CV, or join our talent pooling system:</p>
                    <ul>
                        <li><strong>Application Restrictions:</strong> To ensure processing efficiency and prevent system abuse, applications are subject to automated checks and cooldown protocols. Candidates are restricted from submitting multiple active applications simultaneously. Furthermore, a strict fourteen (14) day cooldown period applies following a final rejection or benched/pooling assignment before a new application may be processed for similar roles.</li>
                        <li><strong>Intentional Blank Fields:</strong> If you clear a text field in our forms, it represents an intentional empty value rather than a system error. The site will record and sync the blank state accordingly.</li>
                        <li><strong>Data Verification:</strong> We reserve the right, but assume no obligation, to verify any credentials, employment history, or education claims submitted by you. Providing falsified information, forged documents, or misleading statements is grounds for immediate application disqualification, removal from talent pools, and potential legal action.</li>
                        <li><strong>No Guarantee of Placement:</strong> Submitting an application or resume through our Site does not guarantee an interview, an offer of employment, or placement with any client partner.</li>
                    </ul>

                    <h2>5. Client Inquiries & Quote Estimator</h2>
                    <p>For prospective clients requesting service proposals, business terms, or utilizing our interactive team builder and quote estimators:</p>
                    <ul>
                        <li>All generated quotes, estimates, and talent configurations are for informational purposes only and do not constitute a formal, binding contract or guaranteed pricing.</li>
                        <li>Actual service fees, managed payroll costs, and staffing terms will be dictated solely by a formally executed Service Agreement (B2B Contract) signed by authorized representatives of both parties.</li>
                    </ul>

                    <h2>6. Intellectual Property Rights</h2>
                    <p>All materials published on or available through the Site — including text, custom graphics, brand assets, logos, design systems (including our Liquid Glass styling paradigms), code structures, scripts, and software — are the exclusive property of Kings Group Cooperative or its licensors and are protected under international copyright, trademark, and intellectual property laws. 
                    You are granted a limited, non-exclusive, non-transferable, and revocable license to access the Site for personal, non-commercial use (for job search purposes) or legitimate business evaluation (for prospective client inquiries). You may not extract, scrape, copy, modify, republish, or distribute any site content without our prior written authorization.</p>

                    <h2>7. Prohibited Code of Conduct</h2>
                    <p>You agree not to engage in any of the following prohibited behaviors:</p>
                    <ul>
                        <li>Submitting fraudulent, defamatory, offensive, or harassing content.</li>
                        <li>Using web scrapers, spiders, robots, crawlers, indexers, or other automated mechanisms to download, monitor, or extract data from the Site without explicit consent.</li>
                        <li>Engaging in reverse-engineering, decompiling, or attempting to extract source code or core shims of the Site or its databases.</li>
                        <li>Attempting to bypass security protocols, authentication barriers, geo-routing redirection rules, or cookie consent gates.</li>
                        <li>Transmitting malware, viruses, trojans, logic bombs, or any script designed to compromise site performance or server resources.</li>
                    </ul>

                    <h2>8. Disclaimer of Warranties</h2>
                    <p>The Site and all services, content, and application portals are provided on an "as is" and "as available" basis without warranties of any kind, either express or implied. To the fullest extent permissible under applicable law, Kings Group Cooperative disclaims all warranties, including but not limited to implied warranties of merchantability, fitness for a particular purpose, non-infringement, security, and accuracy. We do not warrant that the Site will operate uninterrupted, error-free, or free of viruses or other harmful elements.</p>

                    <h2>9. Limitation of Liability</h2>
                    <p>To the maximum extent permitted by applicable law, in no event shall Kings Group Cooperative, its officers, directors, members, employees, or affiliates be liable for any direct, indirect, incidental, special, consequential, or exemplary damages, including but not limited to loss of profits, goodwill, data, career opportunities, or business interruption, arising out of or in connection with:</p>
                    <ul>
                        <li>Your use of, or inability to use, the Site or its application portals.</li>
                        <li>Any unauthorized access to, alteration of, or disclosure of your data submissions.</li>
                        <li>Falsified communications or impersonation schemes carried out by third parties pretending to represent Kings Group (outside our verified email domains).</li>
                    </ul>

                    <h2>10. Indemnification</h2>
                    <p>You agree to defend, indemnify, and hold harmless Kings Group Cooperative, its directors, officers, employees, and members from and against any claims, liabilities, damages, judgments, awards, losses, costs, expenses, or fees (including reasonable attorneys' fees) arising out of or relating to your violation of these Terms or your misuse of the Site, including but not limited to the submission of fraudulent credentials or violation of third-party intellectual property rights.</p>

                    <h2>11. Dispute Resolution & Governing Law</h2>
                    <p>These Terms and any dispute arising out of your use of the Site shall be governed by, interpreted, and enforced in accordance with the laws of the Republic of the Philippines. 
                    In the event of a dispute, you agree to first submit the matter to amicable mediation and consultation. Should mediation fail, any legal action, suit, or proceeding arising out of or relating to these Terms shall be instituted exclusively in the proper courts of Parañaque City, Metro Manila, Philippines, to the exclusion of all other venues.</p>

                    <h2>12. General Provisions</h2>
                    <ul>
                        <li><strong>Severability:</strong> If any provision of these Terms is found to be invalid or unenforceable by a court of competent jurisdiction, the remaining provisions shall remain in full force and effect.</li>
                        <li><strong>No Waiver:</strong> Our failure to enforce any right or provision of these Terms does not constitute a waiver of such right or provision.</li>
                        <li><strong>Entire Agreement:</strong> These Terms constitute the entire agreement between you and Kings Group Cooperative regarding the use of the Site, superseding all prior understandings.</li>
                    </ul>

                    <h2>13. Contact Information & Legal Inquiries</h2>
                    <p>For questions, formal notices, or legal inquiries regarding these Terms, please contact us:</p>
                    <ul>
                        <li><strong>Legal Department:</strong> legal@kingsgroup.com.ph</li>
                        <li><strong>Compliance Office:</strong> compliance@kingsgroup.com.ph</li>
                        <li><strong>Phone Support:</strong> +63 (2) 8776-6712</li>
                        <li><strong>Zamboanga HQ Address:</strong> DVN Building, Melaño Calixto St, Zamboanga City, Philippines</li>
                        <li><strong>Manila Office Address:</strong> 100 Doña Soledad Ave, Better Living Subdivision, Parañaque City 1711, Metro Manila, Philippines</li>
                    </ul>
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
