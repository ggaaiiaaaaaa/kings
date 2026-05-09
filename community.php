<?php /* Template Name: Community */ ?>
<?php get_header(); ?>

<!-- Hero -->
<section class="page-hero community-bg" style="background-image: linear-gradient(rgba(10, 37, 64, 0.7), rgba(10, 37, 64, 0.7)), url('https://images.unsplash.com/photo-1544027993-37dbfe43562a?auto=format&fit=crop&w=1920&q=80');">
    <div class="container text-center animate-on-scroll">
        <h1><?php echo esc_html(kg_get_field('comm_hero_title', 'Our Commitment to Community')); ?></h1>
    </div>
</section>

<!-- Impact Content -->
<section class="section section-bg-light">
    <div class="container">
        
        <div style="display: grid; grid-template-columns: 1fr; gap: 4rem; align-items: center; max-width: 1100px; margin: 0 auto;">
            
            <!-- Queens Section -->
            <div class="queens-showcase animate-on-scroll">
                <div class="glass-card" style="padding: 3rem; text-align: center;">
                    <h2 class="section-title" style="margin-bottom: 1rem;"><?php echo esc_html(kg_get_field('comm_queens_title', 'Queens of Kings Group')); ?></h2>
                    <p style="color: var(--text-muted); max-width: 800px; margin: 0 auto;">
                        <?php echo esc_html(kg_get_field('comm_queens_desc', 'Dedicated to empowering women within the Kings Group network through specialized resources, mentorship, and support structures designed for professional and personal growth.')); ?>
                    </p>
                </div>
            </div>

            <!-- Introduction / Scholarship -->
            <div class="animate-on-scroll" style="text-align: center;">
                <h2 class="section-title" style="margin-bottom: 1.5rem; font-size: 2.5rem; color: var(--main-blue);">Impact</h2>
                <p style="font-size: 1.25rem; color: var(--text-dark); line-height: 1.8; max-width: 800px; margin: 0 auto;">
                    <?php echo esc_html(kg_get_field('comm_impact_intro', 'Community is essential to our mission and it is our responsibility to support the aspirations of our members by providing scholarships to our members and their dependents.')); ?>
                </p>
            </div>

            <!-- Home Culinary School Feature -->
            <div class="culinary-feature animate-on-scroll" style="margin-top: 2rem;">
                <div class="glass-card" style="padding: 3.5rem; border-top: 4px solid var(--neutral-yellow);">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">
                        
                        <div>
                            <div style="aspect-ratio: 16/9; border-radius: var(--card-radius); overflow: hidden; margin-bottom: 1.5rem;">
                                <?php 
                                $culinary_img = kg_get_field('comm_culinary_img');
                                echo kg_img($culinary_img, 'Home Culinary School', '', 'width: 100%; height: 100%; object-fit: cover;');
                                ?>
                            </div>
                            <h3 style="font-size: 1.8rem; margin-bottom: 1.25rem; color: var(--main-blue);">Home Culinary and Technical School</h3>
                            <p style="color: var(--text-muted); line-height: 1.7; margin-bottom: 1.5rem;">
                                <?php echo esc_html(kg_get_field('comm_culinary_intro', 'We built Home Culinary and Technical School to have a sustainable education and livelihood programs for our members and their families.')); ?>
                            </p>
                            <p style="color: var(--text-muted); line-height: 1.7;">
                                <?php echo esc_html(kg_get_field('comm_culinary_desc', 'As The Kings expands, so does our scholarship program with Home Culinary and Technical School. We are TESDA accredited and certified.')); ?>
                            </p>
                        </div>

                        <div style="background: rgba(10, 37, 64, 0.03); padding: 2rem; border-radius: var(--card-radius);">
                            <h4 style="font-size: 1.1rem; text-transform: uppercase; color: var(--text-dark); margin-bottom: 1.25rem; letter-spacing: 0.5px;">Courses Offered</h4>
                            <ul style="list-style: none; padding: 0; margin-bottom: 2.5rem; display: flex; flex-direction: column; gap: 1rem;">
                                <li style="display: flex; align-items: flex-start; gap: 0.75rem; color: var(--text-muted); line-height: 1.5;"><svg style="flex-shrink: 0; margin-top: 2px;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> Culinary Arts</li>
                                <li style="display: flex; align-items: flex-start; gap: 0.75rem; color: var(--text-muted); line-height: 1.5;"><svg style="flex-shrink: 0; margin-top: 2px;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> Cookery NC II</li>
                                <li style="display: flex; align-items: flex-start; gap: 0.75rem; color: var(--text-muted); line-height: 1.5;"><svg style="flex-shrink: 0; margin-top: 2px;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> Bread and Pastry NC II</li>
                                <li style="display: flex; align-items: flex-start; gap: 0.75rem; color: var(--text-muted); line-height: 1.5;"><svg style="flex-shrink: 0; margin-top: 2px;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> Food and Beverage Services NC II</li>
                                <li style="display: flex; align-items: flex-start; gap: 0.75rem; color: var(--text-muted); line-height: 1.5;"><svg style="flex-shrink: 0; margin-top: 2px;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> Housekeeping NC II</li>
                            </ul>
                            <a href="https://thehomeculinaryschool.com/" target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="width: 100%; text-align: center;">APPLY NOW</a>
                        </div>
                        
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php get_footer(); ?>