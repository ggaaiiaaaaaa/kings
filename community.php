<?php
/* Template Name: Community */

if (!defined('ABSPATH')) {
    require_once 'functions.php';
}

$page_title = 'Community | Kings Group Cooperative';
$page_description = 'Building a sustainable future through education, empowerment, and shared success with Kings Group Cooperative\'s community programs.';

// JSON-LD: WebPage schema
$page_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    '@id' => 'https://kingsgroup.com.ph/community/#webpage',
    'url' => 'https://kingsgroup.com.ph/community/',
    'name' => 'Community | Kings Group Cooperative',
    'description' => 'Building a sustainable future through education, empowerment, and shared success with Kings Group Cooperative\'s community programs.',
    'isPartOf' => ['@id' => 'https://kingsgroup.com.ph/#website'],
    'about' => ['@id' => 'https://kingsgroup.com.ph/#organization'],
    'breadcrumb' => [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://kingsgroup.com.ph/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Community', 'item' => 'https://kingsgroup.com.ph/community/'],
        ],
    ],
];

$page_hero_bg = kg_get_field('comm_hero_bg', kg_asset('img/community/hero-community.png'));
get_header();
?>

<!-- Hero -->
<?php
$comm_hero_bg    = kg_get_field('comm_hero_bg', kg_asset('img/community/hero-community.png'));
$comm_hero_bg_style = !empty($comm_hero_bg) ? "background-image: linear-gradient(rgba(10, 37, 64, 0.75), rgba(10, 37, 64, 0.75)), url('" . esc_url($comm_hero_bg) . "');" : '';
?>
<section class="page-hero community-hero" style="<?php echo $comm_hero_bg_style; ?>">
    <div class="container text-center">
        <h1 class="animate-on-scroll">Community</h1>
        <p class="animate-on-scroll" style="max-width: 750px; margin: 1rem auto 0; color: rgba(255,255,255,0.85); font-size: 1.5rem;">Building a sustainable future through education, empowerment, and shared success.</p>
    </div>
</section>


<!-- Queens Section -->
<section class="section section-bg-light" style="padding: 8rem 0;">
    <div class="container" style="text-align: center;">
        <h2 class="section-title" style="margin-bottom: 2.5rem; color: var(--main-blue);">Queens of Kings Group</h2>
        <?php
        $queens_img = kg_get_field('comm_queens_img', kg_asset('img/community/queens-of-kingsgroup.png'));
        echo kg_img($queens_img, 'Queens of Kings Group', 'img-fluid', 'max-width: 600px; width: 100%; border-radius: 20px; box-shadow: var(--shadow-lg); display: block; margin: 0 auto;');
        ?>
    </div>
</section>

<!-- Real Impact Section (moved below Queens) -->
<section class="section section-bg-white" style="padding: 8rem 0;">
    <div class="container">
        <?php
        $impact_img  = kg_get_field('comm_impact_img', kg_asset('img/community/community-impact.JPG'));
        ?>
        <div class="impact-intro-grid animate-on-scroll is-visible">
            <div class="impact-text">
                <h2 class="section-title" style="text-align: left; margin-bottom: 2rem;">Impact</h2>
                <p style="font-size: 1.2rem; color: var(--text-body); line-height: 1.8; margin-bottom: 2.5rem;">
                    <?php echo esc_html(kg_get_field('comm_impact_intro', 'Community is essential to our mission and it is our responsibility to support the aspirations of our members by providing scholarships to our members and their dependents.')); ?>
                </p>
            </div>
            <div class="impact-image-box">
                <div class="impact-img-container">
                    <?php echo kg_img($impact_img, 'Kings Group Community', 'img-fluid'); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Home Culinary School Feature -->
<section class="section section-bg-white" style="padding: 8rem 0;">
    <div class="container">
        <div class="culinary-showcase animate-on-scroll">

            <div class="culinary-grid">
                <div class="culinary-info-card">
                    <div class="card-media">
                        <?php
                        $culinary_img = kg_get_field('comm_culinary_img', kg_asset('img/community/community-culinary.png'));
                        echo kg_img($culinary_img, 'Home Culinary School');
                        ?>
                    </div>
                    <div class="card-body">
                        <p class="intro-text">
                            <?php echo esc_html(kg_get_field('comm_culinary_intro', 'We built Home Culinary and Technical School to have a sustainable education and livelihood programs for our members and their families.')); ?>
                        </p>
                        <p class="desc-text">
                            <?php echo esc_html(kg_get_field('comm_culinary_desc', 'As The Kings expands, so does our scholarship program with Home Culinary and Technical School. We are TESDA accredited and certified.')); ?>
                        </p>
                    </div>
                </div>

                <div class="courses-card glass-card">
                    <h3><?php echo esc_html(kg_get_field('comm_courses_title', 'Available Programs')); ?></h3>
                    <p><?php echo esc_html(kg_get_field('comm_courses_subtitle', 'TESDA accredited and certified certifications for professional growth.')); ?></p>
                    <ul class="course-list">
                        <?php
                        $courses_list = kg_get_field('comm_courses_list');
                        if (!empty($courses_list)) :
                            $courses = explode("\n", $courses_list);
                            foreach ($courses as $course) :
                                $course = trim($course);
                                if (empty($course)) continue;
                                ?>
                                <li><span class="check">✓</span> <?php echo esc_html($course); ?></li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li><span class="check">✓</span> Culinary Arts</li>
                            <li><span class="check">✓</span> Cookery NC II</li>
                            <li><span class="check">✓</span> Bread and Pastry NC II</li>
                            <li><span class="check">✓</span> Food and Beverage Services NC II</li>
                            <li><span class="check">✓</span> Housekeeping NC II</li>
                        <?php endif; ?>
                    </ul>
                    <a href="<?php echo esc_url(kg_get_field('comm_scholarship_btn_url', 'https://thehomeculinaryschool.com/')); ?>" target="_blank" rel="noopener noreferrer"
                        class="btn btn-gold btn-block"><?php echo esc_html(kg_get_field('comm_scholarship_btn_text', 'Apply for Scholarship')); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Layouts */
    .impact-intro-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 5rem;
        align-items: center;
    }

    .impact-stats {
        display: flex;
        gap: 3rem;
        margin-top: 2rem;
    }

    .stat-num {
        display: block;
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--sec-accent-green);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
    }

    .impact-image-box {
        position: relative;
    }

    .impact-img-container {
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(10, 37, 64, 0.08);
        box-shadow: var(--shadow-lg);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
    }
    
    .impact-img-container:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-xl);
    }
    
    .impact-img-container img {
        width: 100%;
        display: block;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .impact-img-container:hover img {
        transform: scale(1.05);
    }

    .image-overlay-card {
        position: absolute;
        bottom: -2rem;
        left: -2rem;
        padding: 2rem;
        max-width: 250px;
        background: var(--glass-strong-bg);
        border: 1px solid var(--glass-strong-border);
        box-shadow: var(--shadow-lg);
    }

    .card-tag {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: var(--neutral-yellow);
        color: var(--main-blue);
        font-size: 0.7rem;
        font-weight: 800;
        border-radius: 4px;
        margin-bottom: 1rem;
    }

    /* Queens Banner */
    .queens-banner {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        padding: 4rem;
        align-items: center;
        background: var(--glass-mid-bg);
        border-radius: 24px;
    }

    .queens-img-container {
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: var(--shadow-lg);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
    }
    
    .queens-img-container:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-xl);
    }
    
    .queens-img-container img {
        width: 100%;
        display: block;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .queens-img-container:hover img {
        transform: scale(1.05);
    }

    /* Culinary Showcase */
    .culinary-header {
        margin-bottom: 4rem;
    }

    .section-tag {
        color: var(--neutral-yellow);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        display: block;
    }

    .culinary-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 3rem;
    }

    .culinary-info-card {
        padding: 0;
    }
 
    .culinary-info-card .card-media {
        height: 350px;
        border-radius: 24px;
        overflow: hidden;
        position: relative;
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(10, 37, 64, 0.08);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
    }

    .culinary-info-card .card-media:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-xl);
    }

    .culinary-info-card .card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .culinary-info-card .card-media:hover img {
        transform: scale(1.05);
    }

    .culinary-info-card .card-body {
        padding: 2.5rem 0 0 0;
    }

    .culinary-info-card .intro-text {
        font-size: 1.25rem;
        color: var(--main-blue);
        font-weight: 600;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .culinary-info-card .desc-text {
        color: var(--text-muted);
        line-height: 1.8;
    }

    .courses-card {
        padding: 3.5rem;
        border-top: 5px solid var(--neutral-yellow);
    }

    .courses-card h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .courses-card p {
        color: var(--text-muted);
        margin-bottom: 2.5rem;
    }

    .course-list {
        list-style: none;
        padding: 0;
        margin-bottom: 3rem;
    }

    .course-list li {
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(10, 37, 64, 0.05);
        display: flex;
        gap: 1rem;
        font-weight: 500;
        color: var(--text-dark);
    }

    .course-list .check {
        color: var(--sec-accent-green);
        font-weight: 800;
    }

    @media (max-width: 992px) {

        .impact-intro-grid,
        .queens-banner,
        .culinary-grid {
            grid-template-columns: 1fr;
            gap: 3rem;
        }

        .queens-banner {
            padding: 2.5rem;
        }

        .image-overlay-card {
            position: static;
            margin-top: 1.5rem;
            max-width: none;
        }

        .impact-stats {
            justify-content: center;
        }
    }
</style>

<?php get_footer(); ?>