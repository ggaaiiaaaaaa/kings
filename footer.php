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
            <div class="social-links">
                <a href="https://www.facebook.com/KingsCooperative" target="_blank" rel="noopener">Facebook</a>
            </div>
        </div>
    </div>
</footer>

<!-- Interactive Scripts -->
<?php wp_footer(); ?>
</body>

</html>