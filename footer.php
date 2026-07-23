</main>

<!-- Footer -->
<footer>
    <div class="container animate-on-scroll">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo"><img
                        src="<?php echo kg_asset('img/[LOGO] Footer.webp'); ?>" alt="Kings Group Footer Logo"
                        class="brand-logo-footer" loading="lazy"></a>
                <?php
                $desc = get_theme_mod('footer_description', 'Empowering global teams with ethical Philippine talent through a worker-owned cooperative model.');
                ?>
                <p style="color: rgba(255,255,255,0.7); font-size: 0.95rem; margin-bottom: 1.5rem;">
                    <?php echo wp_kses_post($desc); ?></p>

                <?php
                $fb_url = get_theme_mod('footer_facebook_url', 'https://www.facebook.com/KingsCooperative');
                if (!empty($fb_url)):
                    ?>
                    <style>
                        .footer-social-link {
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            width: 38px;
                            height: 38px;
                            border-radius: 50%;
                            background-color: rgba(255, 255, 255, 0.05);
                            color: #ffffff;
                            transition: all 0.3s ease;
                            text-decoration: none;
                            border: 1px solid rgba(255, 255, 255, 0.1);
                        }

                        .footer-social-link:hover {
                            background-color: #ffd166;
                            color: #0a2540;
                            border-color: #ffd166;
                            transform: translateY(-2px);
                        }
                    </style>
                    <div class="footer-socials" style="display: flex; gap: 1rem;">
                        <a href="<?php echo esc_url($fb_url); ?>" target="_blank" rel="noopener noreferrer"
                            class="footer-social-link" aria-label="Facebook">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <?php
            $col1_title = get_theme_mod('footer_col1_title', 'Company');
            ?>
            <div class="footer-links-col">
                <h4><?php echo esc_html($col1_title); ?></h4>
                <?php
                if (has_nav_menu('footer_company')) {
                    $menu_html = wp_nav_menu(array(
                        'theme_location' => 'footer_company',
                        'echo' => false,
                        'fallback_cb' => false,
                        'container' => false,
                        'items_wrap' => '%3$s',
                        'depth' => 1,
                    ));
                    echo strip_tags($menu_html, '<a>');
                } else {
                    // Fallback
                    ?>
                    <a href="<?php echo esc_url(home_url('/story/')); ?>">Our Story</a>
                    <a href="<?php echo esc_url(home_url('/careers/')); ?>">Careers</a>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact Us</a>
                    <?php
                }
                ?>
            </div>

            <?php
            $col2_title = get_theme_mod('footer_col2_title', 'Members');
            ?>
            <div class="footer-links-col">
                <h4><?php echo esc_html($col2_title); ?></h4>
                <?php
                if (has_nav_menu('footer_members')) {
                    $menu_html = wp_nav_menu(array(
                        'theme_location' => 'footer_members',
                        'echo' => false,
                        'fallback_cb' => false,
                        'container' => false,
                        'items_wrap' => '%3$s',
                        'depth' => 1,
                    ));
                    echo strip_tags($menu_html, '<a>');
                } else {
                    // Fallback
                    ?>
                    <a href="https://zckings.azurewebsites.net/" target="_blank" rel="noopener">Member Portal</a>
                    <a href="https://kingslending.timefree.ph/" target="_blank" rel="noopener">Kings Lending</a>
                    <a href="<?php echo esc_url(home_url('/benefits/')); ?>">Benefits</a>
                    <?php
                }
                ?>
            </div>
        </div>

        <div class="footer-bottom">
            <?php
            $copyright = get_theme_mod('footer_copyright', '&copy; 2026 Kings Group Cooperative. All rights reserved. Designed by <a href="https://www.itmonsterszc.com/">ITMonsters</a>');
            ?>
            <div><?php echo wp_kses_post($copyright); ?></div>

            <div class="region-switcher" style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.65);">
                <?php if (kg_get_user_geo() === 'PH'): ?>
                    Looking for international services? <a href="<?php echo esc_url(home_url('/?geo=INTL')); ?>"
                        onclick="document.cookie='kg_user_geo=INTL; path=/; max-age=2592000; SameSite=Lax'; document.cookie='kg_consent_accepted=true; path=/; max-age=2592000; SameSite=Lax';"
                        class="region-switch-link">Switch to International Site</a>
                <?php else: ?>
                    Looking for local PH careers? <a href="<?php echo esc_url(home_url('/?geo=PH')); ?>"
                        onclick="document.cookie='kg_user_geo=PH; path=/; max-age=2592000; SameSite=Lax'; document.cookie='kg_consent_accepted=true; path=/; max-age=2592000; SameSite=Lax';"
                        class="region-switch-link">Switch to Philippines Site</a>
                <?php endif; ?>
            </div>
            <style>
                .footer-bottom a.region-switch-link {
                    color: #38bdf8 !important;
                    text-decoration: none;
                    margin-left: 5px;
                    transition: color 0.2s ease;
                }

                .footer-bottom a.region-switch-link:hover {
                    color: #7dd3fc !important;
                }
            </style>
            <style>
                .social-icon-link {
                    color: rgba(255, 255, 255, 0.65);
                    transition: color 0.2s ease, transform 0.2s ease;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                }

                .social-icon-link:hover {
                    color: #ffd166;
                    transform: translateY(-2px);
                }
            </style>
        </div>
    </div>
</footer>

<!-- Interactive Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var phoneInputs = document.querySelectorAll('.kg-phone-input');
        window.kgPhoneInstances = {};
        phoneInputs.forEach(function(input, index) {
            var inputName = input.getAttribute('name');
            var iti = window.intlTelInput(input, {
                initialCountry: "ph",
                preferredCountries: ["ph", "us", "gb", "au", "ae"],
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
            });
            // We manually handle the hidden input to ensure it works with AJAX FormData
            if (inputName) {
                input.removeAttribute('name');
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = inputName;
                
                var form = input.closest('form');
                if (form) {
                    form.appendChild(hiddenInput);
                } else {
                    input.parentNode.insertBefore(hiddenInput, input.nextSibling);
                }

                var updateHidden = function() {
                    hiddenInput.value = iti.getNumber();
                };
                input.addEventListener('input', updateHidden);
                input.addEventListener('countrychange', updateHidden);
                updateHidden(); // populate initially
            }
            input.setAttribute('data-iti-id', index);
            window.kgPhoneInstances[index] = iti;
        });
    });
</script>
<?php wp_footer(); ?>
</body>

</html>