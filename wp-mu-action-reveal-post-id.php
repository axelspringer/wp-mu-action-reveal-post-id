<?php
// @codingStandardsIgnoreFile

function revealid_add_id_column( $columns ) {
    $columns['revealid_id'] = 'ID';

    return $columns;
}

function revealid_id_column_content( $column, $id ) {
    if ( 'revealid_id' == $column ) {
        echo $id;
    }
}

function idsearch( $wp ) {
    global $pagenow;

    // If it's not the post listing return
    if ( 'edit.php' != $pagenow ) {
        return;
    }

    // If it's not a search return
    if ( ! isset( $wp->query_vars['s'] ) ) {
        return;
    }

    // If it's a search but there's no prefix, return
    if ( '#' != substr( $wp->query_vars['s'], 0, 1 ) ) {
        return;
    }

    // Validate the numeric value
    $id = absint( substr( $wp->query_vars['s'], 1 ) );
    if ( ! $id ) {
        return; // Return if no ID, absint returns 0 for invalid values
    }

    // If we reach here, all criteria is fulfilled, unset search and select by ID instead
    unset( $wp->query_vars['s'] );
    $wp->query_vars['p'] = $id;
}

add_filter( 'manage_posts_columns', 'revealid_add_id_column', 5 );
add_action( 'manage_posts_custom_column', 'revealid_id_column_content', 5, 2 );
add_action( 'parse_request', 'idsearch' );
