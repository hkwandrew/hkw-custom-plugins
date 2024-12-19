<?php
/*
Plugin Name: Cookie Spoofer
Description: Set a fake cookie to break out of WPEngine's caching.
Author: HKW
Author URI: https://hkw.io/
Version: 1.0.0
*/

function maybe_set_user_cookie() {
    $cookie_was_set = false;

    if ( false === ( $cookie = has_logged_in_user_cookie() ) ) {
        $expire = time() + 3600; // 1 hour expiration
        set_fake_user_logged_in_cookie( $expire );
        $cookie_was_set = true;
    }

    return $cookie_was_set;
}

function remove_user_cookie() {
    $expired = time() - 3600; // Set the cookie to expire in the past
    set_fake_user_logged_in_cookie( $expired );
}

function set_fake_user_logged_in_cookie( $expire = 0 ) {
    $fake_user = '_fake_user_' . time();
    $cookie = 'wordpress_logged_in_' . md5( $fake_user );
    $value = md5( uniqid( $fake_user, true ) );
    setcookie( $cookie, $value, $expire, '/', '', is_ssl(), true );
}

function has_logged_in_user_cookie() {
    $pattern = 'wordpress_logged_in_';
    foreach ( $_COOKIE as $cookie => $value ) {
        if ( 0 === strpos( $cookie, $pattern ) ) {
            return $cookie;
        }
    }
    return false;
}

// Hook into the WordPress 'init' action
add_action( 'init', 'maybe_set_user_cookie' );