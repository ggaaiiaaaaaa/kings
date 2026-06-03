<?php
$content = file_get_contents('quote.php');
$start_pos = strpos($content, '<div class="roles-catalog">');

// Find the end of the roles-catalog div
$end_pos = strpos($content, '<script>', $start_pos);
$end_pos = strrpos(substr($content, 0, $end_pos), '</div>', -10); // Find the last div before <script>

if ($start_pos !== false && $end_pos !== false) {
    // The exact HTML/PHP block to insert
    $new_content = '<div class="roles-catalog">
                    <?php
                    $jobs_query = new WP_Query(array(\'post_type\' => \'jobs\', \'posts_per_page\' => -1));
                    if($jobs_query->have_posts()):
                        while($jobs_query->have_posts()): $jobs_query->the_post();
                            $base_price = get_field(\'base_price\') ?: 1000;
                            $include = get_field(\'include_in_team_builder\');
                            if($include !== false): // Only show if toggle is true or not set
                    ?>
                            <div class="builder-role-card">
                                <div class="builder-role-info">
                                    <div class="builder-role-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                    </div>
                                    <div class="builder-role-text">
                                        <h4><?php the_title(); ?></h4>
                                        <p><?php echo wp_trim_words(get_the_excerpt(), 10); ?></p>
                                    </div>
                                </div>
                                <button class="builder-add-btn" onclick="addRoleToCart(\'<?php echo esc_js(get_the_title()); ?>\', <?php echo (int)$base_price; ?>); closeRoleModal();">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Add
                                </button>
                            </div>
                    <?php 
                            endif;
                        endwhile;
                        wp_reset_postdata();
                    else:
                        echo \'<p style="padding:2rem;">No roles available. Please add some jobs in the WordPress dashboard.</p>\';
                    endif;
                    ?>
                </div>';

    $content = substr_replace($content, $new_content, $start_pos, ($end_pos - $start_pos) + 6);
    file_put_contents('quote.php', $content);
    echo "Success";
} else {
    echo "Failed to find delimiters.";
}
