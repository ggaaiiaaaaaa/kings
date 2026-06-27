</main>

<!-- Footer -->
<footer>
    <div class="container animate-on-scroll">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo"><img
                        src="<?php echo kg_asset('img/[LOGO] Footer.webp'); ?>" alt="Kings Group Footer Logo"
                        class="brand-logo-footer" loading="lazy"></a>
                <p style="color: rgba(255,255,255,0.7); font-size: 0.95rem;">Empowering global teams with ethical
                    Philippine talent through a worker-owned cooperative model.</p>
            </div>

            <div class="footer-links-col">
                <h4>Company</h4>
                <a href="<?php echo esc_url(home_url('/story/')); ?>">Our Story</a>
                <a href="<?php echo esc_url(home_url('/careers/')); ?>">Careers</a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact Us</a>
            </div>
            <div class="footer-links-col">
                <h4>Members</h4>
                <a href="https://zckings.azurewebsites.net/" target="_blank" rel="noopener">Member Portal</a>
                <a href="https://kingslending.timefree.ph/" target="_blank" rel="noopener">Kings Lending</a>
                <a href="<?php echo esc_url(home_url('/benefits/')); ?>">Benefits</a>
            </div>
        </div>

        <div class="footer-bottom">
            <div>&copy; 2026 Kings Group Cooperative. All rights reserved. Designed by <a
                    href="https://www.itmonsterszc.com/">ITMonsters</a></div>
            
            <div class="region-switcher" style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.65);">
                <?php if (kg_get_user_geo() === 'PH') : ?>
                    Looking for international services? <a href="<?php echo esc_url(home_url('/?geo=INTL')); ?>" onclick="document.cookie='kg_user_geo=INTL; path=/; max-age=2592000; SameSite=Lax'; document.cookie='kg_consent_accepted=true; path=/; max-age=2592000; SameSite=Lax';" class="region-switch-link">Switch to International Site</a>
                <?php else : ?>
                    Looking for local PH careers? <a href="<?php echo esc_url(home_url('/?geo=PH')); ?>" onclick="document.cookie='kg_user_geo=PH; path=/; max-age=2592000; SameSite=Lax'; document.cookie='kg_consent_accepted=true; path=/; max-age=2592000; SameSite=Lax';" class="region-switch-link">Switch to Philippines Site</a>
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

            <div class="social-links">
                <a href="https://www.facebook.com/KingsCooperative" target="_blank" rel="noopener" aria-label="Facebook" class="social-icon-link">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" class="feather feather-facebook"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
                </a>
            </div>
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
<?php wp_footer(); ?>
</body>

</html>