<?php
/* Template Name: Quote */
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_title       = 'Get a Quote | Kings Group Interactive Pricing';
$page_description = 'Use our interactive pricing tool to get an instant estimate for your staffing and managed labor services.';

// JSON-LD: WebPage schema for the quote/pricing calculator page
$page_schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'WebPage',
    '@id'         => 'https://kingsgroup.com.ph/quote/#webpage',
    'url'         => 'https://kingsgroup.com.ph/quote/',
    'name'        => 'Get a Quote | Kings Group Interactive Pricing',
    'description' => 'Use our interactive pricing tool to get an instant estimate for your staffing and managed labor services.',
    'isPartOf'    => [ '@id' => 'https://kingsgroup.com.ph/#website' ],
    'breadcrumb'  => [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            [ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home',       'item' => 'https://kingsgroup.com.ph/' ],
            [ '@type' => 'ListItem', 'position' => 2, 'name' => 'Get a Quote','item' => 'https://kingsgroup.com.ph/quote/' ],
        ],
    ],
];

get_header();
?>

    <!-- Modern Premium Hero -->
    <?php
    $quote_headline = kg_get_field('quote_headline', 'Build Your Team');
    $quote_desc = kg_get_field('quote_desc', 'Get a custom quote for managed services, staff leasing, or HR tech solutions.');
    $quote_bg = kg_get_field('quote_bg', '');
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
                <h2 style="color: var(--main-blue); font-size: 2.2rem; margin-bottom: 1rem;"><?php echo esc_html($quote_b_title); ?></h2>
                <p
                    style="color: var(--text-muted); font-size: 1.05rem; max-width: 560px; margin: 0 auto; line-height: 1.7;">
                    <?php echo esc_html($quote_b_instructions); ?></p>
            </div>

            <!-- 3-Step Flow -->
            <div
                style="display: flex; align-items: flex-start; justify-content: center; position: relative; max-width: 860px; margin: 0 auto;">
                <!-- Connecting line -->
                <div
                    style="position: absolute; top: 32px; left: calc(16.67% + 20px); right: calc(16.67% + 20px); height: 2px; background: linear-gradient(to right, var(--main-blue), var(--gold), var(--main-blue)); opacity: 0.35; z-index: 0;">
                </div>

                <!-- Step 1 -->
                <div style="flex: 1; text-align: center; padding: 0 1.5rem; position: relative; z-index: 1;">
                    <div
                        style="width: 64px; height: 64px; border-radius: 50%; background: var(--main-blue); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; font-size: 1.35rem; font-weight: 800; border: 4px solid white; box-shadow: 0 4px 16px rgba(10,37,64,0.18);">
                        1</div>
                    <h4 style="color: var(--main-blue); font-size: 1.05rem; font-weight: 700; margin-bottom: 0.4rem;">
                        Select Roles</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; margin: 0;">Browse our
                        catalog and add the professionals you need.</p>
                </div>

                <!-- Step 2 -->
                <div style="flex: 1; text-align: center; padding: 0 1.5rem; position: relative; z-index: 1;">
                    <div
                        style="width: 64px; height: 64px; border-radius: 50%; background: var(--gold); color: var(--main-blue); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; font-size: 1.35rem; font-weight: 800; border: 4px solid white; box-shadow: 0 4px 16px rgba(196,160,84,0.25);">
                        2</div>
                    <h4 style="color: var(--main-blue); font-size: 1.05rem; font-weight: 700; margin-bottom: 0.4rem;">
                        Review Estimate</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; margin: 0;">See real-time
                        monthly costs with full transparency.</p>
                </div>

                <!-- Step 3 -->
                <div style="flex: 1; text-align: center; padding: 0 1.5rem; position: relative; z-index: 1;">
                    <div
                        style="width: 64px; height: 64px; border-radius: 50%; background: var(--main-blue); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; font-size: 1.35rem; font-weight: 800; border: 4px solid white; box-shadow: 0 4px 16px rgba(10,37,64,0.18);">
                        3</div>
                    <h4 style="color: var(--main-blue); font-size: 1.05rem; font-weight: 700; margin-bottom: 0.4rem;">
                        Get Your Quote</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; margin: 0;">Submit your
                        details and receive a full proposal from our team.</p>
                </div>
            </div>

        </div>
    </section>

    <div class="team-builder-wrapper">
        <div class="container animate-on-scroll">
            <div class="builder-grid-single">

                <div class="builder-cart">
                    <div class="cart-header"
                        style="display: flex; justify-content: space-between; align-items: center;">
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
                            <div class="empty-state-image">
                                <?php echo kg_img('', 'Team member'); ?>
                            </div>
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
                        <div id="quote-error" style="display:none;background:#fef2f2;border:1px solid #fca5a5;padding:0.75rem 1rem;margin-bottom:1rem;border-radius:6px;">
                            <p style="margin:0;color:#991b1b;font-size:0.9rem;" id="quote-error-msg"></p>
                        </div>
                        <form onsubmit="event.preventDefault(); submitQuote();">
                            <input type="text" id="quoteName" placeholder="Your Full Name" required>
                            <input type="email" id="quoteEmail" placeholder="Your Work Email" required>
                            <!-- honeypot -->
                            <input type="text" name="kg_hp_field" id="kg_hp_quote" style="display:none;" tabindex="-1" autocomplete="off">
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
    <div id="quoteSuccessOverlay" style="display:none;position:fixed;inset:0;background:rgba(10,37,64,0.6);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
        <div style="background:#fff;border-radius:20px;padding:3rem 2.5rem;max-width:480px;width:90%;text-align:center;position:relative;box-shadow:0 24px 64px rgba(10,37,64,0.25);">
            <button onclick="closeQuoteModal()" style="position:absolute;top:1rem;right:1.25rem;background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--text-muted);">&times;</button>
            <div style="width:80px;height:80px;margin:0 auto 1.5rem;background:rgba(0,208,156,0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h3 style="font-size:1.6rem;margin-bottom:0.5rem;color:var(--text-dark);">Quote Submitted!</h3>
            <p style="color:var(--text-muted);font-size:1rem;margin-bottom:0.5rem;" id="quoteSuccessName"></p>
            <p style="color:var(--text-muted);font-size:0.95rem;margin-bottom:2rem;">Our sales team will review your team configuration and reach out within 1 business day with a full proposal.</p>
            <div style="background:var(--bg-subtle);border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.5rem;text-align:left;">
                <p style="margin:0 0 0.4rem;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-muted);">Estimated Monthly Total</p>
                <p style="margin:0;font-size:1.8rem;font-weight:800;color:var(--main-blue);" id="quoteSuccessTotal">$0</p>
            </div>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary" style="width:100%;display:block;padding:0.9rem;">Back to Home</a>
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
                        'post_type'      => 'jobs',
                        'post_status'    => 'publish',
                        'posts_per_page' => -1,
                        'orderby'        => 'title',
                        'order'          => 'ASC',
                        'meta_query'     => array(
                            array(
                                'key'     => 'include_in_team_builder',
                                'value'   => '1',
                                'compare' => '=',
                            ),
                        ),
                    ));
                    if ($jobs_query->have_posts()) :
                        while ($jobs_query->have_posts()) : $jobs_query->the_post();
                            $job_id    = get_the_ID();
                            $job_title = get_the_title();
                            $job_desc  = get_the_excerpt();
                            $base_price = get_post_meta($job_id, 'base_price', true) ?: 1000;
                    ?>
                            <div class="builder-role-card">
                                <div class="builder-role-info">
                                    <div class="builder-role-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                    </div>
                                    <div class="builder-role-text">
                                        <h4><?php echo esc_html($job_title); ?></h4>
                                        <p><?php echo esc_html(wp_trim_words($job_desc, 10)); ?></p>
                                    </div>
                                </div>
                                <button class="builder-add-btn" onclick="addRoleToCart('<?php echo esc_js($job_title); ?>', <?php echo (int)$base_price; ?>); closeRoleModal();">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Add
                                </button>
                            </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p style="padding:2rem;">No roles available. Please add some jobs in the WordPress dashboard.</p>';
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        const cartData = [];

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

            cartData.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.className = 'fade-in';

                const itemPrice = Math.round(item.basePrice * item.multiplier * item.qty);

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
                        <select class="level-select" onchange="updateLevel(${index}, this.value)">
                            <option value="1.0" ${item.multiplier == 1.0 ? 'selected' : ''}>Junior</option>
                            <option value="1.4" ${item.multiplier == 1.4 ? 'selected' : ''}>Mid-Level</option>
                            <option value="1.8" ${item.multiplier == 1.8 ? 'selected' : ''}>Senior</option>
                        </select>
                    </td>
                    <td style="text-align: center;">
                        <div class="qty-control-wrapper">
                            <button type="button" onclick="updateQty(${index}, -1)">–</button>
                            <span>${item.qty}</span>
                            <button type="button" onclick="updateQty(${index}, 1)">+</button>
                        </div>
                    </td>
                    <td class="price-col" style="text-align: right;">
                        $${itemPrice.toLocaleString()}<span style="font-size:0.8rem;color:var(--text-muted);font-weight:normal;">/mo</span>
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

            document.getElementById('summaryTotalQty').innerText = totalQty;
            document.getElementById('cartTotal').innerText = "$" + Math.round(totalPrice).toLocaleString();
            document.getElementById('summaryTotalBase').innerText = "$" + Math.round(totalPrice).toLocaleString();

            const savingsAlert = document.getElementById('savingsAlert');
            if (totalQty > 0) {
                const usCost = totalPrice * 3.5;
                const savings = usCost - totalPrice;
                document.getElementById('summarySavings').innerText = "$" + Math.round(savings).toLocaleString();
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
            const name  = document.getElementById('quoteName').value.trim();
            const email = document.getElementById('quoteEmail').value.trim();
            const errorBox = document.getElementById('quote-error');
            errorBox.style.display = 'none';

            if (!name || !email) {
                showQuoteError('Please fill in your name and email before submitting.');
                return;
            }
            if (cartData.length === 0) {
                showQuoteError('Your cart is empty. Please add at least one role.');
                return;
            }

            const roles = cartData.map(item => ({
                role:       item.role,
                level:      item.multiplier == 1.0 ? 'Junior' : item.multiplier == 1.4 ? 'Mid-Level' : 'Senior',
                qty:        item.qty,
                unit_price: Math.round(item.basePrice * item.multiplier),
                subtotal:   Math.round(item.basePrice * item.multiplier * item.qty),
            }));

            const btn = document.getElementById('btnSubmitQuote');
            btn.disabled = true;
            btn.textContent = 'Sending…';

            const body = new FormData();
            body.append('action',      'kg_submit_quote');
            body.append('kg_nonce',    KG_AJAX.quote_nonce);
            body.append('quote_name',  name);
            body.append('quote_email', email);
            body.append('quote_roles', JSON.stringify(roles));
            body.append('kg_hp_field', document.getElementById('kg_hp_quote').value);

            fetch(KG_AJAX.url, { method: 'POST', body })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        // Show branded success modal
                        document.getElementById('quoteSuccessName').textContent = 'Thank you, ' + name + '! Check your email for a summary.';
                        document.getElementById('quoteSuccessTotal').textContent = data.data.total || '$0';
                        const overlay = document.getElementById('quoteSuccessOverlay');
                        overlay.style.display = 'flex';
                        document.body.style.overflow = 'hidden';
                        // Clear cart
                        cartData.length = 0;
                        document.getElementById('quoteName').value  = '';
                        document.getElementById('quoteEmail').value = '';
                        updateCartUI();
                    } else {
                        showQuoteError(data.data && data.data.message ? data.data.message : 'Something went wrong. Please try again.');
                        btn.disabled = false;
                        btn.textContent = 'Request Detailed Quote';
                    }
                })
                .catch(() => {
                    showQuoteError('Network error. Please check your connection and try again.');
                    btn.disabled = false;
                    btn.textContent = 'Request Detailed Quote';
                });
        }

        // Close modal on overlay click
        document.getElementById('quoteSuccessOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeQuoteModal();
        });
        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeQuoteModal();
        });
    </script>

<?php get_footer(); ?>



