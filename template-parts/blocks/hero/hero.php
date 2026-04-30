<?php
/**
 * Hero Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id and class attribute
$id = 'kg-hero-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
$className = 'hero';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$headline = get_field('hero_headline') ?: 'Elite Talent.<br><span>Ethical Staffing.</span>Exceptional Results.';
$description = get_field('hero_description') ?: 'We connect global businesses with the Philippines\' top professionals. Established in 1999 as a worker-owned cooperative, our people aren\'t just staff—they are partners in your success.';
$bg_image_1 = get_field('hero_bg_1') ?: kg_asset('img/HomeCulinary.webp'); // Fallback placeholder image
?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="hero-bg-media" id="hero-slider">
        <img src="<?php echo esc_url($bg_image_1); ?>" class="hero-slide active" alt="Hero Background" loading="eager">
    </div>
    <div class="hero-content">
        <h1><?php echo wp_kses_post($headline); ?></h1>
        <p><?php echo esc_html($description); ?></p>
        <div class="hero-buttons">
            <a href="<?php echo esc_url(home_url('/quote/')); ?>" class="btn btn-primary">
                Build Your Team
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                </svg>
            </a>
            <a href="<?php echo esc_url(home_url('/careers/')); ?>" class="btn btn-outline"
                style="background: rgba(255,255,255,0.1); color: white; border-color: rgba(255,255,255,0.3); backdrop-filter: blur(5px);">
                View Open Roles
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </a>
        </div>
    </div>
</section>