<?php
/**
 * Interview Scheduler Notifications
 * Sends styled HTML email confirmations to applicants and assigned interviewers.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function kg_send_interview_invitation_email( $post_id ) {
    require_once get_template_directory() . '/inc/email-templates.php';

    $fullname = get_the_title( $post_id );
    $fname    = explode( ' ', $fullname )[0];
    $app_email= get_post_meta( $post_id, 'kg_app_email', true );
    $role     = get_post_meta( $post_id, 'kg_app_role', true ) ?: 'the position';

    $date     = get_post_meta( $post_id, 'kg_interview_date', true );
    $time     = get_post_meta( $post_id, 'kg_interview_time', true );
    $format   = get_post_meta( $post_id, 'kg_interview_format', true );
    $details  = get_post_meta( $post_id, 'kg_interview_details', true );
    $er_id    = get_post_meta( $post_id, 'kg_interviewer_id', true );

    if ( empty( $app_email ) || empty( $date ) ) return;

    $formatted_date = date_i18n( get_option('date_format'), strtotime($date) );
    $formatted_time = date_i18n( get_option('time_format'), strtotime($date . ' ' . $time) );

    $format_lbl = ( $format === 'online' ) ? 'Online Meeting (Zoom/Teams)' : 'Face-to-Face Office Interview';
    $details_lbl= ( $format === 'online' ) ? 'Join Link' : 'Office Address';

    $interviewer_name = 'Recruitment Officer';
    $interviewer_email = '';
    if ( $er_id ) {
        $interviewer = get_userdata( $er_id );
        if ( $interviewer ) {
            $interviewer_name = $interviewer->display_name ?: $interviewer->user_login;
            $interviewer_email = $interviewer->user_email;
        }
    }

    $details_html = '<div style="border:1px solid #e8ecf0;border-radius:8px;padding:20px;margin-bottom:24px;background:#ffffff;">'
        . kg_email_row( 'Date', $formatted_date )
        . kg_email_row( 'Time', $formatted_time )
        . kg_email_row( 'Format', $format_lbl )
        . kg_email_row( $details_lbl, $details )
        . kg_email_row( 'Interviewer', $interviewer_name )
        . '</div>';

    // 1. Email to Applicant
    if ( $format === 'online' ) {
        $parsed = kg_get_parsed_email( 'interviewing_online', array(
            '{fname}'             => esc_html( $fname ),
            '{fullname}'          => esc_html( $fullname ),
            '{role}'              => esc_html( $role ),
            '{interview_details}' => $details_html,
        ) );
    } else {
        $parsed = kg_get_parsed_email( 'interviewing_face_to_face', array(
            '{fname}'             => esc_html( $fname ),
            '{fullname}'          => esc_html( $fullname ),
            '{role}'              => esc_html( $role ),
            '{interview_details}' => $details_html,
        ) );
    }

    if ( ! empty( $parsed ) ) {
        $subject = $parsed['subject'];
        $body_applicant = kg_email_heading( $parsed['heading'] ) . $parsed['body'];

        if ( ! empty( $parsed['banner'] ) ) {
            $body_applicant .= kg_email_banner( $parsed['banner'] );
        }
        if ( ! empty( $parsed['btn_text'] ) && ! empty( $parsed['btn_link'] ) ) {
            $body_applicant .= kg_email_button( $parsed['btn_text'], $parsed['btn_link'] );
        }

        wp_mail(
            $app_email,
            $subject,
            kg_email_wrap( $subject, $body_applicant, $fullname, '', date_i18n( get_option( 'date_format' ) ) ),
            array( 'Content-Type: text/html; charset=UTF-8' )
        );
    }

    // 2. Email to Interviewer (if recruiter email exists)
    if ( ! empty( $interviewer_email ) ) {
        $subject_interviewer = 'New Interview Assignment: ' . $fullname . ' — Kings Manpower';
        $edit_url = get_edit_post_link( $post_id );

        $body_interviewer = kg_email_heading( 'New Interview Assigned' )
            . kg_email_para( 'Hi ' . esc_html($interviewer_name) . ',' )
            . kg_email_para( 'You have been assigned to conduct an interview for applicant <strong>' . esc_html( $fullname ) . '</strong>. Details below:' )
            . '<div style="border:1px solid #e8ecf0;border-radius:8px;padding:20px;margin-bottom:24px;background:#ffffff;">'
            . kg_email_row( 'Applicant', $fullname )
            . kg_email_row( 'Applied Role', $role )
            . kg_email_row( 'Date', $formatted_date )
            . kg_email_row( 'Time', $formatted_time )
            . kg_email_row( 'Format', $format_lbl )
            . kg_email_row( $details_lbl, $details )
            . '</div>'
            . kg_email_banner( 'Review the applicant profile and CV inside WP Admin before starting.' )
            . kg_email_button( 'View Applicant Profile', $edit_url );

        wp_mail(
            $interviewer_email,
            $subject_interviewer,
            kg_email_wrap( $subject_interviewer, $body_interviewer, $interviewer_name, '', date_i18n( get_option( 'date_format' ) ) ),
            array( 'Content-Type: text/html; charset=UTF-8' )
        );
    }
}

function kg_send_processing_email( $post_id ) {
    require_once get_template_directory() . '/inc/email-templates.php';

    $fullname = get_the_title( $post_id );
    $fname    = explode( ' ', $fullname )[0];
    $app_email= get_post_meta( $post_id, 'kg_app_email', true );
    $role     = get_post_meta( $post_id, 'kg_app_role', true ) ?: 'the position';

    $target_date = get_post_meta( $post_id, 'kg_app_target_deploy_date', true );
    $submission_date = get_post_meta( $post_id, 'kg_app_submission_date', true );

    if ( empty( $app_email ) ) return;

    $formatted_target = $target_date ? date_i18n( get_option('date_format'), strtotime($target_date) ) : 'TBA';
    $formatted_submission = $submission_date ? date_i18n( get_option('date_format'), strtotime($submission_date) ) : 'TBA';

    $details_html = '<div style="border:1px solid #e8ecf0;border-radius:8px;padding:20px;margin-bottom:24px;background:#ffffff;">'
        . kg_email_row( 'Applied Role', $role )
        . kg_email_row( 'Requirements Deadline', $formatted_submission )
        . kg_email_row( 'Target Deployment', $formatted_target )
        . '</div>';

    $parsed = kg_get_parsed_email( 'processing', array(
        '{fname}'              => esc_html( $fname ),
        '{fullname}'           => esc_html( $fullname ),
        '{role}'               => esc_html( $role ),
        '{processing_details}' => $details_html,
    ) );

    if ( ! empty( $parsed ) ) {
        $subject = $parsed['subject'];
        $body_applicant = kg_email_heading( $parsed['heading'] ) . $parsed['body'];

        if ( ! empty( $parsed['banner'] ) ) {
            $body_applicant .= kg_email_banner( $parsed['banner'] );
        }
        if ( ! empty( $parsed['btn_text'] ) && ! empty( $parsed['btn_link'] ) ) {
            $body_applicant .= kg_email_button( $parsed['btn_text'], $parsed['btn_link'] );
        }

        wp_mail(
            $app_email,
            $subject,
            kg_email_wrap( $subject, $body_applicant, $fullname, '', date_i18n( get_option( 'date_format' ) ) ),
            array( 'Content-Type: text/html; charset=UTF-8' )
        );
    }
}
