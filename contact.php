<?php
/* Template Name: Contact Us */
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_title       = 'Contact Us | Kings Group';
$page_description = 'Get in touch with Kings Group for general inquiries, support, or partnership opportunities.';

// JSON-LD: ContactPage + LocalBusiness — surface address/phone in Google Knowledge Panel
$page_schema = [
    '@context'    => 'https://schema.org',
    '@graph'      => [
        [
            '@type'       => 'ContactPage',
            '@id'         => 'https://kingsgroup.com.ph/contact/#webpage',
            'url'         => 'https://kingsgroup.com.ph/contact/',
            'name'        => 'Contact Us | Kings Group',
            'description' => 'Get in touch with Kings Group for general inquiries, support, or partnership opportunities.',
            'isPartOf'    => [ '@id' => 'https://kingsgroup.com.ph/#website' ],
            'breadcrumb'  => [
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    [ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home',       'item' => 'https://kingsgroup.com.ph/' ],
                    [ '@type' => 'ListItem', 'position' => 2, 'name' => 'Contact Us', 'item' => 'https://kingsgroup.com.ph/contact/' ],
                ],
            ],
        ],
        [
            '@type'       => 'LocalBusiness',
            '@id'         => 'https://kingsgroup.com.ph/#localbusiness',
            'name'        => 'Kings Group Cooperative',
            'url'         => 'https://kingsgroup.com.ph/',
            'telephone'   => '+63-2-8776-6712',
            'email'       => 'info@kingsgroup.com.ph',
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => '100 Doña Soledad Ave, Better Living Subdivision',
                'addressLocality' => 'Parañaque',
                'addressRegion'   => 'Metro Manila',
                'postalCode'      => '1711',
                'addressCountry'  => 'PH',
            ],
            'geo' => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => '14.4793',
                'longitude' => '121.0117',
            ],
            'openingHoursSpecification' => [
                [
                    '@type'     => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday'],
                    'opens'     => '08:00',
                    'closes'    => '17:00',
                ],
            ],
            'image'       => kg_asset('img/[LOGO] Main Logo Black.webp'),
            'priceRange'  => '$$',
        ],
    ],
];

get_header();
?>

    <?php
    $contact_headline = kg_get_field('contact_headline', 'Contact Us');
    $contact_desc = kg_get_field('contact_desc', 'We are here to help. Reach out to our team for any inquiries.');
    $contact_bg = kg_get_field('contact_bg', '');
    ?>
    <section class="page-hero"
        style="background-image: linear-gradient(rgba(10, 37, 64, 0.7), rgba(10, 37, 64, 0.7)), url('<?php echo esc_url($contact_bg); ?>');">
        <div class="container text-center">
            <?php if (!empty($contact_headline)): ?>
                <h1><?php echo wp_kses_post($contact_headline); ?></h1>
            <?php endif; ?>
            <?php if (!empty($contact_desc)): ?>
                <p><?php echo esc_html($contact_desc); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <section class="section section-bg-light" style="padding: 6rem 0 8rem; background: linear-gradient(180deg, var(--bg-subtle) 0%, var(--bg-white) 100%);">
        <div class="container animate-on-scroll">

            <!-- TOP SECTION: Get In Touch (Form) -->
            <div class="contact-card contact-form-card" style="margin-bottom: 4rem;">
                <?php
                $form_title = kg_get_field('contact_form_title', 'Get In Touch');
                $form_desc = kg_get_field('contact_form_desc', 'THE KINGS is a community of jobs and workspaces. We are excited to welcome you to THE KINGS!');
                $form_shortcode = kg_get_field('contact_form_shortcode', '[contact-form-7 id="123" title="Contact form 1"]');
                ?>
                
                <div class="contact-header-block text-center">
                    <?php if (!empty($form_title)): ?>
                        <h2 class="contact-heading" style="text-align: center; margin-bottom: 1rem;"><?php echo esc_html($form_title); ?></h2>
                    <?php endif; ?>
                    
                    <?php if (!empty($form_desc)): ?>
                        <p class="contact-desc" style="margin: 0 auto; max-width: 600px; text-align: center;"><?php echo esc_html($form_desc); ?></p>
                    <?php endif; ?>
                </div>

                <div class="contact-form-wrapper" style="max-width: 800px; margin: 0 auto;">

                    <div id="contact-success" style="display:none;background:#f0fdf9;border:1px solid #00D09C;padding:1.5rem 2rem;margin-bottom:1.5rem;border-radius:8px;">
                        <p style="margin:0;color:#065f46;font-weight:600;font-size:1.05rem;" id="contact-success-msg"></p>
                    </div>
                    <div id="contact-error" style="display:none;background:#fef2f2;border:1px solid #fca5a5;padding:1rem 1.5rem;margin-bottom:1.5rem;border-radius:8px;">
                        <p style="margin:0;color:#991b1b;font-size:0.95rem;" id="contact-error-msg"></p>
                    </div>

                    <form id="kg-contact-form" novalidate>
                        <!-- Honeypot — hidden from real users, catches bots -->
                        <div style="display:none;" aria-hidden="true">
                            <input type="text" name="kg_hp_field" value="" tabindex="-1" autocomplete="off">
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
                            <div>
                                <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Your Name *</label>
                                <input type="text" name="contact_name" required placeholder="e.g. Maria Santos"
                                    style="width:100%;padding:0.9rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;outline:none;transition:var(--transition);box-sizing:border-box;"
                                    onfocus="this.style.borderColor='var(--main-blue)'" onblur="this.style.borderColor='var(--border-color)'">
                            </div>
                            <div>
                                <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Email Address *</label>
                                <input type="email" name="contact_email" required placeholder="you@company.com"
                                    style="width:100%;padding:0.9rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;outline:none;transition:var(--transition);box-sizing:border-box;"
                                    onfocus="this.style.borderColor='var(--main-blue)'" onblur="this.style.borderColor='var(--border-color)'">
                            </div>
                        </div>
                        <div style="margin-bottom:1.25rem;">
                            <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Subject</label>
                            <input type="text" name="contact_subject" placeholder="How can we help?"
                                style="width:100%;padding:0.9rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;outline:none;transition:var(--transition);box-sizing:border-box;"
                                onfocus="this.style.borderColor='var(--main-blue)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>
                        <div style="margin-bottom:1.75rem;">
                            <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Message *</label>
                            <textarea name="contact_message" required rows="6" placeholder="Tell us about your inquiry..."
                                style="width:100%;padding:0.9rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;outline:none;resize:vertical;transition:var(--transition);box-sizing:border-box;"
                                onfocus="this.style.borderColor='var(--main-blue)'" onblur="this.style.borderColor='var(--border-color)'"></textarea>
                        </div>
                        <button type="submit" id="contact-submit" class="btn btn-primary" style="padding:1rem 2.5rem;font-size:1rem;width:100%;">
                            Send Message
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                    </form>

                    <script>
                    document.getElementById('kg-contact-form').addEventListener('submit', function(e) {
                        e.preventDefault();
                        const btn        = document.getElementById('contact-submit');
                        const successBox = document.getElementById('contact-success');
                        const errorBox   = document.getElementById('contact-error');
                        successBox.style.display = 'none';
                        errorBox.style.display   = 'none';

                        btn.disabled    = true;
                        btn.textContent = 'Sending…';

                        const form = new FormData(this);
                        form.append('action',   'kg_submit_contact');
                        form.append('kg_nonce', KG_AJAX.contact_nonce);

                        fetch(KG_AJAX.url, { method: 'POST', body: form })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    successBox.style.display = 'block';
                                    document.getElementById('contact-success-msg').textContent = data.data.message;
                                    this.reset();
                                } else {
                                    errorBox.style.display = 'block';
                                    document.getElementById('contact-error-msg').textContent = data.data.message;
                                }
                            })
                            .catch(() => {
                                errorBox.style.display = 'block';
                                document.getElementById('contact-error-msg').textContent = 'Network error. Please try again.';
                            })
                            .finally(() => {
                                btn.disabled = false;
                                btn.innerHTML = 'Send Message <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>';
                            });
                    });
                    </script>
                </div>
            </div>
            
            <!-- BOTTOM SECTION: Contact Us & Visit Us Cards -->
            <div class="info-cards-grid">
                
                <!-- Contact Us Card -->
                <div class="info-card glass-card">
                    <?php
                    $info_title = kg_get_field('contact_info_title', 'Contact Us');
                    $telephone = kg_get_field('contact_telephone', '+63 (2) 87766712');
                    $mobile = kg_get_field('contact_mobile', '+63 (917) 634 2088 / +63 (917) 710 3221');
                    $email = kg_get_field('contact_email', 'info@kingsgroup.com.ph');
                    ?>
                    
                    <?php if (!empty($info_title)): ?>
                        <h3 class="info-heading">
                            <span class="info-heading-dot"></span>
                            <?php echo esc_html($info_title); ?>
                        </h3>
                    <?php endif; ?>
                    
                    <div class="info-details-premium">
                        <?php if (!empty($telephone)): ?>
                            <div class="premium-info-item">
                                <div class="premium-icon-box">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                </div>
                                <div class="premium-info-text">
                                    <div class="premium-info-label">Telephone</div>
                                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $telephone)); ?>" class="premium-info-value info-link"><?php echo esc_html($telephone); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($mobile)): ?>
                            <div class="premium-info-item">
                                <div class="premium-icon-box">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                                </div>
                                <div class="premium-info-text">
                                    <div class="premium-info-label">Mobile</div>
                                    <div class="premium-info-value"><?php echo esc_html($mobile); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($email)): ?>
                            <div class="premium-info-item">
                                <div class="premium-info-box">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                </div>
                                <div class="premium-info-text">
                                    <div class="premium-info-label">Email</div>
                                    <a href="mailto:<?php echo esc_attr($email); ?>" class="premium-info-value info-link" style="word-break: break-all;"><?php echo esc_html($email); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Visit Us Card -->
                <div class="info-card glass-card">
                    <?php
                    $visit_title = kg_get_field('contact_visit_title', 'Visit Us');
                    $address = kg_get_field('contact_address', 'Kings Headquarters, 100 Doña Soledad Avenue, Better Living, Paranaque City, Metro Manila, Philippines, 1711');
                    ?>
                    
                    <?php if (!empty($visit_title)): ?>
                        <h3 class="info-heading">
                            <span class="info-heading-dot info-heading-dot--green"></span>
                            <?php echo esc_html($visit_title); ?>
                        </h3>
                    <?php endif; ?>
                    
                    <div class="info-details-premium">
                        <?php if (!empty($address)): ?>
                            <div class="premium-info-item">
                                <div class="premium-icon-box premium-icon-box--green">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                </div>
                                <div class="premium-info-text">
                                    <div class="premium-info-label">Headquarters</div>
                                    <p class="premium-info-value" style="margin: 0; line-height: 1.6;">
                                        <?php echo wp_kses_post(nl2br($address)); ?>
                                    </p>
                                    <a href="https://maps.google.com/?q=<?php echo urlencode(strip_tags($address)); ?>" target="_blank" rel="noopener noreferrer" class="premium-info-value info-link" style="color: var(--sec-accent-green); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; margin-top: 1rem;">
                                        Get Directions
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
            </div>

        </div>
    </section>

    <section class="section map-section" style="position: relative; height: 60vh; min-height: 500px; width: 100%; overflow: hidden; background: var(--bg-subtle);">
        <iframe 
            src="https://maps.google.com/maps?q=Better%20Living%20Subdivision,%20100%20Do%C3%B1a%20Soledad%20Ave,%20Para%C3%B1aque,%201711%20Metro%20Manila&t=&z=17&ie=UTF8&iwloc=B&output=embed" 
            width="100%" 
            height="100%" 
            style="border:0; position: absolute; inset: 0; z-index: 0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </section>

    <!-- Custom CSS for Premium Typography & Layout -->
    <style>
        .info-cards-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .contact-card {
            background: var(--bg-white);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(0,0,0,0.03);
            max-width: 1000px;
            margin: 0 auto;
        }

        .contact-form-card {
            padding: 5rem 4rem;
        }

        .info-card.glass-card {
            background: linear-gradient(135deg, var(--main-blue) 0%, var(--main-blue-light) 100%);
            padding: 4rem 3rem;
            color: white;
            border: 1px solid rgba(255,255,255,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
        }

        .info-card.glass-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        .glass-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(255,255,255,0.1) 0%, transparent 60%);
            pointer-events: none;
        }

        .contact-header-block {
            margin-bottom: 3rem;
        }

        .contact-heading {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            color: var(--main-blue);
            margin-bottom: 1rem;
            line-height: 1.1;
            letter-spacing: -0.03em;
        }

        .contact-desc {
            font-size: 1.05rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .cf7-placeholder {
            padding: 3rem 2rem;
            background: var(--bg-subtle);
            border: 1px dashed var(--border-color);
            text-align: center;
            color: var(--text-light);
            font-family: monospace;
            border-radius: var(--card-radius-lg);
        }

        .info-heading {
            font-size: 1.35rem;
            font-weight: 500;
            color: white;
            margin-bottom: 2rem;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            z-index: 1;
        }

        .info-heading-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--neutral-yellow);
            display: inline-block;
            box-shadow: 0 0 12px var(--neutral-yellow);
        }
        .info-heading-dot--green {
            background: var(--sec-accent-green);
            box-shadow: 0 0 12px var(--sec-accent-green);
        }

        .info-details-premium {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            position: relative;
            z-index: 1;
        }

        .premium-info-item {
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
        }

        .premium-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--neutral-yellow);
            flex-shrink: 0;
            border: 1px solid rgba(255,255,255,0.15);
        }
        
        .premium-icon-box--green {
            color: var(--sec-accent-green);
        }

        .premium-info-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .premium-info-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: rgba(255,255,255,0.5);
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .premium-info-value {
            font-size: 1.15rem;
            color: rgba(255,255,255,0.95);
            font-weight: 300;
            letter-spacing: 0.02em;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .info-link:hover {
            color: var(--neutral-yellow) !important;
        }

        @media (max-width: 991px) {
            .info-cards-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .contact-form-card {
                padding: 3rem 2rem;
            }
            .info-card.glass-card {
                padding: 3rem 2rem;
            }
        }
    </style>

<?php get_footer(); ?>