<?php
// Force WordPress to believe comments are open, overriding ANY database settings or plugins!
add_filter( 'comments_open', '__return_true', 9999 );

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="kingsgroup-comments-area">

    <?php 
    // Since we forcefully included this file to bypass GoDaddy's blocks, we must manually fetch the comments from the database.
    $post_comments = get_comments( array(
        'post_id' => get_the_ID(),
        'status'  => 'approve' // Only show approved comments
    ) );
    
    if ( ! empty( $post_comments ) ) : ?>
        <h3 class="comments-title">
            <?php
            $comment_count = count( $post_comments );
            if ( 1 === $comment_count ) {
                echo '1 Comment';
            } else {
                echo $comment_count . ' Comments';
            }
            ?>
        </h3>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 50,
                'type'        => 'comment',
            ), $post_comments );
            ?>
        </ol>

        <?php the_comments_navigation(); ?>

    <?php endif; // Check for have_comments(). ?>

    <?php
    // If comments are closed and there are comments, leave a little note
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
        ?>
        <p class="no-comments"><?php _e( 'Comments are closed.', 'kingsgroup' ); ?></p>
    <?php endif; ?>

    <?php
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $html_req = ( $req ? " required='required'" : '' );
    
    // We clean up the standard WordPress "logged in as" text to be simpler and prettier.
    $user_identity = wp_get_current_user()->exists() ? wp_get_current_user()->display_name : '';
    $logout_url = wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) );
    $logged_in_as_text = sprintf(
        '<p class="logged-in-as">%s <a href="%s">%s</a></p>',
        sprintf( __( 'Posting as %s.', 'kingsgroup' ), '<strong>' . esc_html( $user_identity ) . '</strong>' ),
        esc_url( $logout_url ),
        __( 'Log out?', 'kingsgroup' )
    );
    
    $args = array(
        'class_form'           => 'kingsgroup-comment-form',
        'class_submit'         => 'submit-btn',
        'title_reply'          => __( 'Share Your Thoughts', 'kingsgroup' ),
        'title_reply_to'       => __( 'Reply to %s', 'kingsgroup' ),
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h3>',
        'comment_notes_before' => '', // Removed "email will not be published" note for cleaner look
        'logged_in_as'         => $logged_in_as_text, // Simplified logged in text
        'fields'               => array(
            'author' => '<div class="comment-form-grid"><div class="comment-form-field comment-form-author">' .
                        '<input id="author" name="author" type="text" placeholder="Your Name" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . $html_req . ' /></div>',
            'email'  => '<div class="comment-form-field comment-form-email">' .
                        '<input id="email" name="email" type="email" placeholder="Your Email Address" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . $html_req  . ' /></div></div>',
            'url'    => '', // Removed website URL field to prevent spam
        ),
        'comment_field'        => '<div class="comment-form-field comment-form-comment">' .
                                  '<textarea id="comment" name="comment" cols="45" rows="4" maxlength="65525" required="required" placeholder="Write your thoughts here..."></textarea></div>',
    );
    
    comment_form( $args );
    ?>
</div>

<style>
/* Modern Comments Styling */
.kingsgroup-comments-area {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
    font-family: var(--font-body, "Inter", sans-serif);
}

.kingsgroup-comments-area .comments-title {
    font-family: var(--font-header, sans-serif);
    font-size: 1.35rem;
    font-weight: 800;
    color: #0a2540;
    margin-bottom: 2rem;
}

.kingsgroup-comments-area ol.comment-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.kingsgroup-comments-area .comment-list .comment {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #edf2f7;
}

.kingsgroup-comments-area .comment-list .comment:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

/* Simple Container */
.kingsgroup-comments-area .comment-body {
    position: relative;
}

/* Reset Global Footer Styles for Comment Meta (Fixes the dark blue box) */
.kingsgroup-comments-area footer.comment-meta {
    background: transparent !important;
    padding: 0 !important;
    margin: 0 0 0.75rem 0 !important;
    border: none !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
    flex-wrap: wrap;
}

/* Hide WP 'says:' text */
.kingsgroup-comments-area .says {
    display: none !important; 
}

/* Avatar Styling */
.kingsgroup-comments-area .comment-author .avatar {
    border-radius: 50%; /* Classic circle */
    width: 40px;
    height: 40px;
    object-fit: cover;
}

/* Flex layout for author text */
.kingsgroup-comments-area .comment-author {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.kingsgroup-comments-area .comment-author .fn {
    font-weight: 700;
    color: #0a2540;
    font-size: 1rem;
    font-style: normal;
    text-decoration: none;
}

.kingsgroup-comments-area .comment-author .fn a {
    color: #0a2540;
    text-decoration: none;
}

/* Date next to name */
.kingsgroup-comments-area .comment-metadata {
    margin-left: 0.25rem; 
}

.kingsgroup-comments-area .comment-metadata a {
    color: #a0aec0;
    font-size: 0.85rem;
    text-decoration: none;
}

/* Comment Content aligned with Name */
.kingsgroup-comments-area .comment-content {
    color: #2d3748;
    line-height: 1.6;
    font-size: 0.95rem;
    padding-left: calc(40px + 0.75rem);
}

.kingsgroup-comments-area .comment-content p {
    margin: 0 0 0.75rem 0;
}
.kingsgroup-comments-area .comment-content p:last-child {
    margin-bottom: 0;
}

/* Simple Reply Link */
.kingsgroup-comments-area .reply {
    margin-top: 0.5rem;
    padding-left: calc(40px + 0.75rem);
}

.kingsgroup-comments-area .comment-reply-link {
    display: inline-block;
    font-weight: 600;
    color: #718096;
    text-decoration: none;
    font-size: 0.85rem;
    transition: color 0.2s;
}

.kingsgroup-comments-area .comment-reply-link:hover {
    color: #3182ce;
}

/* Nested Comments (Replies) */
.kingsgroup-comments-area .children {
    list-style: none;
    margin: 1.5rem 0 0 2rem;
    padding: 0;
    border-left: 2px solid #edf2f7;
    padding-left: 1.5rem;
}

/* Form Styling */
.kingsgroup-comment-form {
    margin-top: 1rem;
}

.comment-reply-title {
    font-family: var(--font-header, sans-serif);
    font-size: 1.25rem;
    font-weight: 800;
    color: #0a2540;
    margin-bottom: 0.5rem;
    margin-top: 3rem;
    border-top: 1px solid #e2e8f0;
    padding-top: 2rem;
}

.logged-in-as, .comment-notes {
    color: #718096;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

.logged-in-as a {
    color: #3182ce;
    text-decoration: none;
    font-weight: 600;
}

.comment-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

@media (max-width: 600px) {
    .comment-form-grid {
        grid-template-columns: 1fr;
    }
}

.comment-form-field {
    display: flex;
    flex-direction: column;
    margin-bottom: 1.25rem;
}

.comment-form-field label {
    display: none; /* Hide labels in favor of clean placeholders */
}

.comment-form-field input,
.comment-form-field textarea {
    padding: 1rem 1.25rem;
    border: 1px solid transparent;
    border-radius: 12px;
    font-family: inherit;
    font-size: 1rem;
    color: #2d3748;
    background: #f7f9fc;
    transition: all 0.2s;
    width: 100%;
    box-sizing: border-box;
}

.comment-form-field input::placeholder,
.comment-form-field textarea::placeholder {
    color: #a0aec0;
}

.comment-form-field input:focus,
.comment-form-field textarea:focus {
    outline: none;
    background: #ffffff;
    border-color: #3182ce;
    box-shadow: 0 0 0 4px rgba(49, 130, 206, 0.1);
}

.kingsgroup-comment-form .submit-btn {
    background: #0a2540;
    color: #fff;
    border: none;
    padding: 0.85rem 2rem;
    border-radius: 99px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
}

.kingsgroup-comment-form .submit-btn:hover {
    background: #ffd166;
    color: #0a2540;
}
</style>
