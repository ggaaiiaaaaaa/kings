<?php
/* Template Name: Quote */
?>
<?php
if (!defined('ABSPATH')) {
    require_once 'functions.php';
}
$page_title = 'Get a Quote | Kings Group Interactive Pricing';
$page_description = 'Use our interactive pricing tool to get an instant estimate for your staffing and managed labor services.';

// JSON-LD: WebPage schema for the quote/pricing calculator page
$page_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    '@id' => 'https://kingsgroup.com.ph/quote/#webpage',
    'url' => 'https://kingsgroup.com.ph/quote/',
    'name' => 'Get a Quote | Kings Group Interactive Pricing',
    'description' => 'Use our interactive pricing tool to get an instant estimate for your staffing and managed labor services.',
    'isPartOf' => ['@id' => 'https://kingsgroup.com.ph/#website'],
    'breadcrumb' => [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://kingsgroup.com.ph/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Get a Quote', 'item' => 'https://kingsgroup.com.ph/quote/'],
        ],
    ],
];

$page_hero_bg = kg_get_field('quote_bg', kg_asset('img/quote/hero-quote.JPG'));
get_header();
?>

<!-- Modern Premium Hero -->
<?php
$quote_headline = kg_get_field('quote_headline', 'Build Your Team');
$quote_desc = kg_get_field('quote_desc', 'Get a custom quote for managed services, staff leasing, or HR tech solutions.');
$quote_bg = kg_get_field('quote_bg', kg_asset('img/quote/hero-quote.JPG'));
?>
<section class="page-hero"
    style="background-image: linear-gradient(rgba(10, 37, 64, 0.7), rgba(10, 37, 64, 0.7)), url('<?php echo esc_url($quote_bg); ?>');">
    <div class="container text-center">
        <h1><?php echo wp_kses_post($quote_headline); ?></h1>
        <p><?php echo esc_html($quote_desc); ?></p>
    </div>
</section>


<!-- How It Works Section -->
<section class="section section-bg-light" style="padding: 5rem 0 4rem;">
    <div class="container">

        <!-- Centred Section Header -->
        <?php
        $quote_b_title = kg_get_field('quote_b_title', 'Estimate Your Monthly Investment');
        $quote_b_instructions = kg_get_field('quote_calc_instructions', 'Select the roles you need, adjust experience levels, and instantly see a transparent baseline for your offshore team.');
        ?>
        <div style="text-align: center; margin-bottom: 4rem;">
            <p
                style="text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; color: var(--gold); font-weight: 700; margin-bottom: 0.75rem;">
                How It Works</p>
            <h2 style="color: var(--main-blue); font-size: 2.2rem; margin-bottom: 1rem;">
                <?php echo esc_html($quote_b_title); ?>
            </h2>
            <p
                style="color: var(--text-muted); font-size: 1.05rem; max-width: 560px; margin: 0 auto; line-height: 1.7;">
                <?php echo esc_html($quote_b_instructions); ?>
            </p>
        </div>

        <!-- 3-Step Flow -->
        <div class="quote-steps-container">
            <!-- Connecting line -->
            <div class="quote-steps-line"></div>

            <!-- Step 1 -->
            <div class="quote-step-box">
                <div class="quote-step-num">1</div>
                <h4 class="quote-step-title">Select Roles</h4>
                <p class="quote-step-desc">Browse our catalog and add the professionals you need.</p>
            </div>

            <!-- Step 2 -->
            <div class="quote-step-box">
                <div class="quote-step-num gold">2</div>
                <h4 class="quote-step-title">Review Estimate</h4>
                <p class="quote-step-desc">See real-time monthly costs with full transparency.</p>
            </div>

            <!-- Step 3 -->
            <div class="quote-step-box">
                <div class="quote-step-num">3</div>
                <h4 class="quote-step-title">Get Your Quote</h4>
                <p class="quote-step-desc">Submit your details and receive a full proposal from our team.</p>
            </div>
        </div>

    </div>
</section>

<div class="team-builder-wrapper">
    <div class="container animate-on-scroll">
        <!-- Glass Header Controls -->
        <div
            style="display:flex;justify-content:flex-end;align-items:center;margin-bottom:2rem;padding:1.5rem;background:rgba(255,255,255,0.45);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.5);border-radius:16px;">
            <!-- Currency Switcher -->
            <div
                style="display:flex;align-items:center;gap:0.5rem;background:rgba(10,37,64,0.06);padding:0.25rem;border-radius:30px;">
                <button type="button" id="curr-USD" class="currency-btn"
                    style="padding:0.4rem 1rem;font-size:0.85rem;font-weight:700;border:none;border-radius:20px;background:none;color:var(--text-muted);cursor:pointer;transition:var(--transition);"
                    onclick="switchCurrency('USD')">USD ($)</button>
                <button type="button" id="curr-AUD" class="currency-btn active"
                    style="padding:0.4rem 1rem;font-size:0.85rem;font-weight:700;border:none;border-radius:20px;background:var(--main-blue);color:#fff;cursor:pointer;transition:var(--transition);"
                    onclick="switchCurrency('AUD')">AUD (A$)</button>
                <button type="button" id="curr-PHP" class="currency-btn"
                    style="padding:0.4rem 1rem;font-size:0.85rem;font-weight:700;border:none;border-radius:20px;background:none;color:var(--text-muted);cursor:pointer;transition:var(--transition);"
                    onclick="switchCurrency('PHP')">PHP (₱)</button>
            </div>
        </div>

        <div class="builder-grid-single">

            <div class="builder-cart">
                <div class="cart-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="color: white; margin: 0; display: flex; align-items: center;">
                        <svg style="margin-right: 0.5rem;" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Your Team Selection
                    </h3>
                    <button class="btn btn-gold" onclick="openRoleModal()"
                        style="padding: 0.4rem 1rem; font-size: 0.9rem;">
                        + Add Member
                    </button>
                </div>

                <div id="cartContainer" style="width: 100%;">
                    <div class="cart-empty-state" id="cartEmpty">
                        <h2 style="font-size: 1.3rem; margin: 0;">Build your offshore team with Kings Group.</h2>
                        <p style="color: var(--text-muted); font-size: 0.9rem; max-width: 380px; margin: 0;">Select
                            roles below and instantly see a transparent monthly estimate.</p>
                        <button class="btn btn-gold btn-large mt-4" onclick="openRoleModal()">
                            Get started
                        </button>
                    </div>


                    <div class="cart-table-wrapper" id="cartTableWrapper" style="display: none;">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Role Function</th>
                                    <th>Experience Level</th>
                                    <th style="text-align: center;">Headcount</th>
                                    <th style="text-align: right;">Est. Monthly</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <!-- Table items injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Team Size:</span>
                        <span id="summaryTotalQty" style="font-weight:bold; color:var(--text-dark);">0</span>
                    </div>
                    <div class="summary-row" style="color:var(--main-blue);">
                        <span>Est. Monthly Base:</span>
                        <span id="summaryTotalBase">$0</span>
                    </div>

                    <div class="savings-alert" id="savingsAlert" style="display:none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" style="margin-right: 0.5rem;">
                            <path d="m5 12 5 5L20 7" />
                        </svg>
                        Saving ~<span id="summarySavings" style="margin-left: 0.25rem;">0</span> vs. local hire
                    </div>
                    <div class="summary-total">
                        <span>Estimated Total</span>
                        <div class="total-price" id="cartTotal">$0</div>
                    </div>
                </div>

                <div class="cart-action">
                    <div id="quote-error"
                        style="display:none;background:#fef2f2;border:1px solid #fca5a5;padding:0.75rem 1rem;margin-bottom:1rem;border-radius:6px;">
                        <p style="margin:0;color:#991b1b;font-size:0.9rem;" id="quote-error-msg"></p>
                    </div>
                    <form onsubmit="event.preventDefault(); submitQuote();">
                        <input type="text" id="quoteName" placeholder="Your Full Name" required>
                        <input type="email" id="quoteEmail" placeholder="Your Work Email" required>
                        <div style="display:flex; gap:0.5rem; margin-bottom: 1rem;">
                            <select id="quoteCountryCode" style="width:110px; padding:0.8rem 0.5rem; border:1px solid var(--border-color); border-radius:8px; font-family:inherit; font-size:0.95rem; box-sizing:border-box; background:#fff; cursor:pointer; outline:none;">
                                <option value="+63" selected>PH (+63)</option>
                                <option value="+61">AU (+61)</option>
                                <option value="+1">US (+1)</option>
                                <option value="+44">UK (+44)</option>
                                <option value="+971">AE (+971)</option>
                                <option value="">Other</option>
                            </select>
                            <input type="tel" id="quotePhone" placeholder="912 345 6789" style="flex:1; padding:0.8rem 1rem; border:1px solid var(--border-color); border-radius:8px; font-family:inherit; font-size:0.95rem; box-sizing:border-box; outline:none;" required>
                        </div>
                        <!-- honeypot -->
                        <input type="text" name="kg_hp_field" id="kg_hp_quote" style="display:none;" tabindex="-1"
                            autocomplete="off">
                        
                        <!-- Cloudflare Turnstile CAPTCHA Widget -->
                        <div class="cf-turnstile" data-sitekey="<?php echo esc_attr(defined('CF_TURNSTILE_SITE_KEY') ? CF_TURNSTILE_SITE_KEY : ''); ?>" data-appearance="interaction-only" style="margin-bottom: 1.25rem;"></div>

                        <button type="submit" class="btn btn-gold" id="btnSubmitQuote" disabled>
                            Request Detailed Quote
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- Quote Success Modal -->
<div id="quoteSuccessOverlay"
    style="display:none;position:fixed;inset:0;background:rgba(10,37,64,0.6);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
    <div
        style="background:#fff;border-radius:20px;padding:3rem 2.5rem;max-width:480px;width:90%;text-align:center;position:relative;box-shadow:0 24px 64px rgba(10,37,64,0.25);">
        <button onclick="closeQuoteModal()"
            style="position:absolute;top:1rem;right:1.25rem;background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--text-muted);">&times;</button>
        <div
            style="width:80px;height:80px;margin:0 auto 1.5rem;background:rgba(0,208,156,0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)"
                stroke-width="2.5">
                <polyline points="20 6 9 17 4 12" />
            </svg>
        </div>
        <h3 style="font-size:1.6rem;margin-bottom:0.5rem;color:var(--text-dark);">Quote Submitted!</h3>
        <p style="color:var(--text-muted);font-size:1rem;margin-bottom:0.5rem;" id="quoteSuccessName"></p>
        <p style="color:var(--text-muted);font-size:0.95rem;margin-bottom:2rem;">Our sales team will review your team
            configuration and reach out within 1 business day with a full proposal.</p>
        <div
            style="background:var(--bg-subtle);border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.5rem;text-align:left;">
            <p
                style="margin:0 0 0.4rem;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-muted);">
                Estimated Monthly Total</p>
            <p style="margin:0;font-size:1.8rem;font-weight:800;color:var(--main-blue);" id="quoteSuccessTotal">$0</p>
        </div>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary"
            style="width:100%;display:block;padding:0.9rem;">Back to Home</a>
    </div>
</div>

<!-- Role Selection Modal -->
<div id="roleModalOverlay" class="role-modal-overlay" onclick="handleModalClick(event)">
    <div class="role-modal-container">
        <div class="role-modal-header">
            <h3>Select a Role</h3>
            <button class="role-modal-close" onclick="closeRoleModal()">&times;</button>
        </div>
        <div class="role-modal-body">
            <div class="roles-catalog">
                <?php
                $jobs_query = new WP_Query(array(
                    'post_type' => 'jobs',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => 'include_in_team_builder',
                            'value' => '1',
                            'compare' => '=',
                        ),
                    ),
                ));
                $seen_titles = array();
                if ($jobs_query->have_posts()):
                    while ($jobs_query->have_posts()):
                        $jobs_query->the_post();
                        $job_title = get_the_title();
                        if ( in_array( $job_title, $seen_titles, true ) ) {
                            continue;
                        }
                        $seen_titles[] = $job_title;
                        $job_id = get_the_ID();
                        $job_desc = get_the_excerpt();
                        $base_price = get_post_meta($job_id, 'base_price', true) ?: 1000;
                        ?>
                        <div class="builder-role-card">
                            <div class="builder-role-info">
                                <div class="builder-role-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="builder-role-text">
                                    <h4><?php echo esc_html($job_title); ?></h4>
                                    <p><?php echo esc_html(wp_trim_words($job_desc, 10)); ?></p>
                                </div>
                            </div>
                            <button class="builder-add-btn"
                                onclick="addRoleToCart('<?php echo esc_js($job_title); ?>', <?php echo (int) $base_price; ?>); closeRoleModal();">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg> Add
                            </button>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else:
                    // Premium Static Fallbacks
                    $static_roles = array(
                        array('Customer Support', 'High-empathy, omnichannel support specialists working 24/7/365.', 900),
                        array('Virtual Assistant', 'Executive support, schedule management, email handling, and tasks.', 850),
                        array('Graphic Designer', 'Branding, creative assets, social media graphics, and collateral design.', 1100),
                        array('Web Developer', 'Front-end and full-stack engineers building modern responsive platforms.', 1400),
                        array('Accountant / Bookkeeper', 'General ledger management, payroll processing, tax filing, and audits.', 1200),
                        array('Digital Marketing', 'PPC specialists, content strategists, and SEO managers maximizing ROI.', 1050),
                        array('Data Entry Specialist', 'Accurate, high-speed data migration, database management, and cleanup.', 800),
                    );
                    foreach ($static_roles as $role):
                        ?>
                        <div class="builder-role-card">
                            <div class="builder-role-info">
                                <div class="builder-role-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="builder-role-text">
                                    <h4><?php echo esc_html($role[0]); ?></h4>
                                    <p><?php echo esc_html($role[1]); ?></p>
                                </div>
                            </div>
                            <button class="builder-add-btn"
                                onclick="addRoleToCart('<?php echo esc_js($role[0]); ?>', <?php echo (int) $role[2]; ?>); closeRoleModal();">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg> Add
                            </button>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>

            </div>
        </div>
    </div>
</div>

<script>
    const cartData = [];
    let currentCurrency = 'AUD';

    const currencyConfig = {
        USD: { symbol: '$', rate: 1.0, suffix: '/mo' },
        AUD: { symbol: 'A$', rate: 1.5, suffix: '/mo' },
        PHP: { symbol: '₱', rate: 58.0, suffix: '/mo' }
    };

    function switchCurrency(code) {
        currentCurrency = code;
        document.querySelectorAll('.currency-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.style.background = 'none';
            btn.style.color = 'var(--text-muted)';
        });
        const activeBtn = document.getElementById('curr-' + code);
        if (activeBtn) {
            activeBtn.classList.add('active');
            activeBtn.style.background = 'var(--main-blue)';
            activeBtn.style.color = '#fff';
        }
        updateCartUI();
    }



    function openRoleModal() {
        document.getElementById('roleModalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeRoleModal() {
        document.getElementById('roleModalOverlay').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function handleModalClick(e) {
        if (e.target.id === 'roleModalOverlay') {
            closeRoleModal();
        }
    }

    function updateCartUI() {
        const emptyState = document.getElementById('cartEmpty');
        const tableWrapper = document.getElementById('cartTableWrapper');
        const tbody = document.getElementById('cartTableBody');
        const btnSubmit = document.getElementById('btnSubmitQuote');

        if (cartData.length === 0) {
            emptyState.style.display = 'flex';
            tableWrapper.style.display = 'none';
            btnSubmit.disabled = true;
            updateTotals();
            return;
        }

        emptyState.style.display = 'none';
        tableWrapper.style.display = 'block';
        btnSubmit.disabled = false;

        tbody.innerHTML = '';

        const cfg = currencyConfig[currentCurrency];

        cartData.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.className = 'fade-in';

            const itemPrice = Math.round(item.basePrice * item.multiplier * item.qty * cfg.rate);

            tr.innerHTML = `
                    <td>
                        <div class="role-col">
                            <div class="role-col-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div class="role-col-text">
                                <h4>${item.role}</h4>
                                <p>Dedicated Offshore Talent</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="kg-table-select" id="level-select-${index}" role="combobox" aria-haspopup="listbox" aria-expanded="false" aria-controls="options-list-${index}" aria-label="Experience Level Select">
                            <div class="kg-table-select-trigger" tabindex="0" onclick="toggleTableSelect(event, ${index})" onkeydown="handleTableSelectKey(event, ${index})">
                                <span>${item.multiplier == 1.0 ? 'Junior' : item.multiplier == 1.4 ? 'Mid-Level' : 'Senior'}</span>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </div>
                            <div class="kg-table-select-options" id="options-list-${index}" role="listbox" aria-label="Experience Level Options">
                                <div class="kg-table-option ${item.multiplier == 1.0 ? 'selected' : ''}" role="option" aria-selected="${item.multiplier == 1.0 ? 'true' : 'false'}" tabindex="0" onclick="selectLevelOption(${index}, 1.0, 'Junior')" onkeydown="handleTableOptionKey(event, ${index}, 1.0, 'Junior')">Junior</div>
                                <div class="kg-table-option ${item.multiplier == 1.4 ? 'selected' : ''}" role="option" aria-selected="${item.multiplier == 1.4 ? 'true' : 'false'}" tabindex="0" onclick="selectLevelOption(${index}, 1.4, 'Mid-Level')" onkeydown="handleTableOptionKey(event, ${index}, 1.4, 'Mid-Level')">Mid-Level</div>
                                <div class="kg-table-option ${item.multiplier == 1.8 ? 'selected' : ''}" role="option" aria-selected="${item.multiplier == 1.8 ? 'true' : 'false'}" tabindex="0" onclick="selectLevelOption(${index}, 1.8, 'Senior')" onkeydown="handleTableOptionKey(event, ${index}, 1.8, 'Senior')">Senior</div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <div class="qty-control-wrapper">
                            <button type="button" onclick="updateQty(${index}, -1)">–</button>
                            <span>${item.qty}</span>
                            <button type="button" onclick="updateQty(${index}, 1)">+</button>
                        </div>
                    </td>
                    <td class="price-col" style="text-align: right;">
                        ${cfg.symbol}${itemPrice.toLocaleString()}<span style="font-size:0.8rem;color:var(--text-muted);font-weight:normal;">${cfg.suffix}</span>
                    </td>
                    <td class="action-col">
                        <button class="btn-remove-row" onclick="removeFromCart(${index})" title="Remove">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </td>
                `;
            tbody.appendChild(tr);
        });

        updateTotals();
    }

    function addRoleToCart(roleName, basePrice) {
        const existing = cartData.find(i => i.role === roleName && i.multiplier == 1.0);
        if (existing) {
            existing.qty++;
        } else {
            cartData.push({
                role: roleName,
                basePrice: basePrice,
                multiplier: 1.0,
                qty: 1
            });
        }
        updateCartUI();
    }

    function removeFromCart(index) {
        cartData.splice(index, 1);
        updateCartUI();
    }

    function updateLevel(index, newMultiplier) {
        cartData[index].multiplier = parseFloat(newMultiplier);
        updateCartUI();
    }

    // Keep direct link for custom dropdown options click
    window.selectLevelOption = function (index, multiplier, label) {
        updateLevel(index, multiplier);
    };

    function updateQty(index, delta) {
        const item = cartData[index];
        item.qty += delta;
        if (item.qty < 1) item.qty = 1;
        updateCartUI();
    }

    function updateTotals() {
        let totalQty = 0;
        let totalPrice = 0;

        cartData.forEach(item => {
            totalQty += item.qty;
            totalPrice += (item.basePrice * item.multiplier * item.qty);
        });

        const cfg = currencyConfig[currentCurrency];
        const convertedBase = Math.round(totalPrice * cfg.rate);



        document.getElementById('summaryTotalQty').innerText = totalQty;
        document.getElementById('summaryTotalBase').innerText = cfg.symbol + convertedBase.toLocaleString();
        document.getElementById('cartTotal').innerText = cfg.symbol + convertedBase.toLocaleString();

        const savingsAlert = document.getElementById('savingsAlert');
        if (totalQty > 0) {
            const usCost = totalPrice * 3.5;
            const savings = usCost - totalPrice;
            const convertedSavings = Math.round(savings * cfg.rate);
            document.getElementById('summarySavings').innerText = cfg.symbol + convertedSavings.toLocaleString();
            savingsAlert.style.display = 'flex';
        } else {
            savingsAlert.style.display = 'none';
        }
    }

    function showQuoteError(msg) {
        const box = document.getElementById('quote-error');
        document.getElementById('quote-error-msg').textContent = msg;
        box.style.display = 'block';
        box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function closeQuoteModal() {
        document.getElementById('quoteSuccessOverlay').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function submitQuote() {
        const name = document.getElementById('quoteName').value.trim();
        const email = document.getElementById('quoteEmail').value.trim();
        const phoneCode = document.getElementById('quoteCountryCode').value;
        const phoneNum = document.getElementById('quotePhone').value.trim();
        const phone = (phoneCode + ' ' + phoneNum).trim();
        const errorBox = document.getElementById('quote-error');
        errorBox.style.display = 'none';

        if (!name || !email || !phoneNum) {
            showQuoteError('Please fill in your name, email, and phone before submitting.');
            return;
        }
        if (cartData.length === 0) {
            showQuoteError('Your cart is empty. Please add at least one role.');
            return;
        }

        // Extract values
        let totalQty = 0;
        let totalPrice = 0;
        cartData.forEach(item => {
            totalQty += item.qty;
            totalPrice += (item.basePrice * item.multiplier * item.qty);
        });

        const roles = cartData.map(item => ({
            role: item.role,
            level: item.multiplier == 1.0 ? 'Junior' : item.multiplier == 1.4 ? 'Mid-Level' : 'Senior',
            qty: item.qty,
            unit_price: Math.round(item.basePrice * item.multiplier),
            subtotal: Math.round(item.basePrice * item.multiplier * item.qty),
        }));

        const btn = document.getElementById('btnSubmitQuote');
        btn.disabled = true;
        btn.textContent = 'Sending…';

        const cfg = currencyConfig[currentCurrency];
        const convertedTotal = Math.round(totalPrice * cfg.rate);
        const totalFormatted = cfg.symbol + convertedTotal.toLocaleString() + cfg.suffix;

        const turnstileResponse = document.querySelector('[name="cf-turnstile-response"]')?.value || '';

        const body = new FormData();
        body.append('action', 'kg_submit_quote');
        body.append('kg_nonce', '<?php echo wp_create_nonce("kg_quote_nonce"); ?>');
        body.append('quote_name', name);
        body.append('quote_email', email);
        body.append('quote_phone', phone);
        body.append('quote_roles', JSON.stringify(roles));
        body.append('quote_currency', currentCurrency);
        body.append('quote_discount_percent', '0');
        body.append('quote_discount_amount', '0');
        body.append('quote_total', totalFormatted);
        body.append('kg_hp_field', document.getElementById('kg_hp_quote').value);
        body.append('cf-turnstile-response', turnstileResponse);

        fetch(KG_AJAX.url, { method: 'POST', body })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Show branded success modal
                    document.getElementById('quoteSuccessName').textContent = 'Thank you, ' + name + '! Check your email for a summary.';
                    document.getElementById('quoteSuccessTotal').textContent = totalFormatted;
                    const overlay = document.getElementById('quoteSuccessOverlay');
                    overlay.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    // Clear cart
                    cartData.length = 0;
                    document.getElementById('quoteName').value = '';
                    document.getElementById('quoteEmail').value = '';
                    updateCartUI();
                    if (typeof turnstile !== 'undefined') turnstile.reset();
                } else {
                    showQuoteError(data.data && data.data.message ? data.data.message : 'Something went wrong. Please try again.');
                    btn.disabled = false;
                    btn.textContent = 'Request Detailed Quote';
                    if (typeof turnstile !== 'undefined') turnstile.reset();
                }
            })
            .catch(() => {
                showQuoteError('Network error. Please check your connection and try again.');
                btn.disabled = false;
                btn.textContent = 'Request Detailed Quote';
                if (typeof turnstile !== 'undefined') turnstile.reset();
            });
    }

    // Close modal on overlay click
    document.getElementById('quoteSuccessOverlay').addEventListener('click', function (e) {
        if (e.target === this) closeQuoteModal();
    });
    // Close on ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeQuoteModal();
    });

    // Table custom dropdown functions
    window.toggleTableSelect = function (e, index) {
        e.stopPropagation();
        const wrapper = document.getElementById(`level-select-${index}`);
        if (!wrapper) return;
        
        const isOpen = wrapper.classList.contains('open');
        document.querySelectorAll('.kg-table-select').forEach(other => {
            if (other !== wrapper) {
                other.classList.remove('open');
                other.setAttribute('aria-expanded', 'false');
            }
        });
        wrapper.classList.toggle('open');
        wrapper.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
    };

    // Close dropdowns on outside click
    document.addEventListener('click', () => {
        document.querySelectorAll('.kg-table-select').forEach(drop => {
            drop.classList.remove('open');
            drop.setAttribute('aria-expanded', 'false');
        });
    });

    window.handleTableSelectKey = function (e, index) {
        if (e.key === ' ' || e.key === 'Enter' || e.key === 'ArrowDown') {
            e.preventDefault();
            const wrapper = document.getElementById(`level-select-${index}`);
            if (wrapper) {
                if (!wrapper.classList.contains('open')) {
                    toggleTableSelect(e, index);
                }
                setTimeout(() => {
                    const firstOpt = wrapper.querySelector('.kg-table-option');
                    if (firstOpt) firstOpt.focus();
                }, 50);
            }
        }
    };

    window.handleTableOptionKey = function (e, index, multiplier, label) {
        const optionEl = e.currentTarget;
        if (e.key === ' ' || e.key === 'Enter') {
            e.preventDefault();
            optionEl.click();
            const wrapper = document.getElementById(`level-select-${index}`);
            if (wrapper) {
                wrapper.querySelector('.kg-table-select-trigger').focus();
            }
        } else if (e.key === 'ArrowDown') {
            e.preventDefault();
            const next = optionEl.nextElementSibling;
            if (next && next.classList.contains('kg-table-option')) next.focus();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const prev = optionEl.previousElementSibling;
            if (prev && prev.classList.contains('kg-table-option')) prev.focus();
        } else if (e.key === 'Escape') {
            e.preventDefault();
            const wrapper = document.getElementById(`level-select-${index}`);
            if (wrapper) {
                wrapper.classList.remove('open');
                wrapper.setAttribute('aria-expanded', 'false');
                wrapper.querySelector('.kg-table-select-trigger').focus();
            }
        }
    };
</script>

<?php get_footer(); ?>