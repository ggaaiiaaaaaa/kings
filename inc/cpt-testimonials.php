<?php
/**
 * Testimonials CPT (kg_testimonial)
 * Replaces the ACF testi_1_* through testi_4_* approach on the home page.
 * Admin adds testimonials in WP Admin → Testimonials, with no limit of 4.
 */

function kg_register_testimonials_cpt() {
    register_post_type( 'kg_testimonial', array(
        'labels' => array(
            'name'               => 'Testimonials',
            'singular_name'      => 'Testimonial',
            'menu_name'          => 'Testimonials',
            'add_new_item'       => 'Add New Testimonial',
            'edit_item'          => 'Edit Testimonial',
            'new_item'           => 'New Testimonial',
            'view_item'          => 'View Testimonial',
            'search_items'       => 'Search Testimonials',
            'not_found'          => 'No testimonials found.',
            'not_found_in_trash' => 'No testimonials found in Trash.',
        ),
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 25,
        'menu_icon'     => 'dashicons-format-quote',
        'supports'      => array( 'title' ), // title = author name
        'has_archive'   => false,
        'hierarchical'  => false,
        'rewrite'       => false,
    ) );
}
add_action( 'init', 'kg_register_testimonials_cpt' );

// Metabox for the quote, role, photo URL, and display order
function kg_testimonial_add_metabox() {
    add_meta_box(
        'kg_testimonial_details',
        'Testimonial Details',
        'kg_testimonial_metabox_html',
        'kg_testimonial',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'kg_testimonial_add_metabox' );

function kg_testimonial_metabox_html( $post ) {
    wp_nonce_field( 'kg_save_testimonial', 'kg_testimonial_nonce' );
    $quote   = get_post_meta( $post->ID, '_kg_testi_quote', true );
    $role    = get_post_meta( $post->ID, '_kg_testi_role',  true );
    $img_url = get_post_meta( $post->ID, '_kg_testi_img',   true );
    $order   = get_post_meta( $post->ID, '_kg_testi_order', true );
    ?>
    <table class="form-table">
        <tr>
            <th style="width:140px; padding-top:12px;"><label for="kg_testi_quote">Quote Text</label></th>
            <td><textarea id="kg_testi_quote" name="kg_testi_quote" rows="4" style="width:100%;"><?php echo esc_textarea( $quote ); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="kg_testi_role">Role / Company</label></th>
            <td><input type="text" id="kg_testi_role" name="kg_testi_role" value="<?php echo esc_attr( $role ); ?>" style="width:100%;" placeholder="e.g. COO, Global Logistics Tech"></td>
        </tr>
        <tr>
            <th><label for="kg_testi_img">Photo URL</label></th>
            <td>
                <input type="url" id="kg_testi_img" name="kg_testi_img" value="<?php echo esc_attr( $img_url ); ?>" style="width:100%;" placeholder="https://...">
                <p class="description">Upload a photo via <em>Media Library</em>, copy its URL, and paste it here.</p>
            </td>
        </tr>
        <tr>
            <th><label for="kg_testi_order">Display Order</label></th>
            <td>
                <input type="number" id="kg_testi_order" name="kg_testi_order" value="<?php echo esc_attr( $order !== '' ? $order : '0' ); ?>" min="0" step="1" style="width:70px;">
                <span class="description"> Lower number = appears first on the page (0, 1, 2 …)</span>
            </td>
        </tr>
    </table>
    <?php
}

// Save metabox fields on post save
function kg_save_testimonial_meta( $post_id ) {
    if ( ! isset( $_POST['kg_testimonial_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['kg_testimonial_nonce'], 'kg_save_testimonial' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['kg_testi_quote'] ) ) {
        update_post_meta( $post_id, '_kg_testi_quote', sanitize_textarea_field( $_POST['kg_testi_quote'] ) );
    }
    if ( isset( $_POST['kg_testi_role'] ) ) {
        update_post_meta( $post_id, '_kg_testi_role', sanitize_text_field( $_POST['kg_testi_role'] ) );
    }
    if ( isset( $_POST['kg_testi_img'] ) ) {
        update_post_meta( $post_id, '_kg_testi_img', esc_url_raw( $_POST['kg_testi_img'] ) );
    }
    if ( isset( $_POST['kg_testi_order'] ) ) {
        update_post_meta( $post_id, '_kg_testi_order', absint( $_POST['kg_testi_order'] ) );
    }
}
add_action( 'save_post_kg_testimonial', 'kg_save_testimonial_meta' );

// Custom list-table columns
function kg_testimonial_columns( $cols ) {
    return array(
        'cb'             => $cols['cb'],
        'title'          => 'Author Name',
        'kg_testi_quote' => 'Quote',
        'kg_testi_role'  => 'Role / Company',
        'kg_testi_photo' => 'Photo',
        'kg_testi_order' => 'Order',
    );
}
add_filter( 'manage_kg_testimonial_posts_columns', 'kg_testimonial_columns' );

function kg_testimonial_column_content( $col, $post_id ) {
    switch ( $col ) {
        case 'kg_testi_quote':
            echo esc_html( wp_trim_words( get_post_meta( $post_id, '_kg_testi_quote', true ), 12, '…' ) );
            break;
        case 'kg_testi_role':
            echo esc_html( get_post_meta( $post_id, '_kg_testi_role', true ) );
            break;
        case 'kg_testi_photo':
            $url = get_post_meta( $post_id, '_kg_testi_img', true );
            if ( $url ) {
                echo '<img src="' . esc_url( $url ) . '" width="40" height="40" style="object-fit:cover;border-radius:50%;display:block;">';
            } else {
                echo '<span style="color:#999;">—</span>';
            }
            break;
        case 'kg_testi_order':
            echo esc_html( get_post_meta( $post_id, '_kg_testi_order', true ) ?: '0' );
            break;
    }
}
add_action( 'manage_kg_testimonial_posts_custom_column', 'kg_testimonial_column_content', 10, 2 );

// Make Order column sortable
function kg_testimonial_sortable_columns( $cols ) {
    $cols['kg_testi_order'] = 'kg_testi_order';
    return $cols;
}
add_filter( 'manage_edit-kg_testimonial_sortable_columns', 'kg_testimonial_sortable_columns' );

// Default and custom sort for the admin list
function kg_testimonial_sort_query( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) return;
    if ( $query->get('post_type') !== 'kg_testimonial' ) return;

    if ( $query->get('orderby') === 'kg_testi_order' || ! $query->get('orderby') ) {
        $query->set( 'meta_key', '_kg_testi_order' );
        $query->set( 'orderby', 'meta_value_num' );
        $query->set( 'order', 'ASC' );
    }
}
add_action( 'pre_get_posts', 'kg_testimonial_sort_query' );

/**
 * Returns all published testimonials ordered by display order ascending.
 * Used by front-page.php and index.php.
 */
function kg_get_testimonials() {
    if ( ! function_exists('get_posts') ) return array();
    return get_posts( array(
        'post_type'      => 'kg_testimonial',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'meta_value_num',
        'meta_key'       => '_kg_testi_order',
        'order'          => 'ASC',
    ) );
}
