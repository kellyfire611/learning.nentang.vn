<?php
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists(__DIR__.'/'.$uri)) {
    return false;
}

// Get the url path and trim leading slash
$url_path = trim( $_SERVER[ 'REQUEST_URI' ], '/' );

// If url_path is empty, it is root, so call index.html
if ( ! $url_path ) {
    include( 'index.php' );
    return;
}

// If url_path has no dot, it is a post permalink, so add .html extension
if( ! preg_match( '/[.]/', $url_path ) ) {
    include( $url_path . '.php' );
    return;
}

// In case of css files, add the appropriate header
if( preg_match( '/[.css]/', $url_path ) ) {
    header("Content-type: text/css");
    include( $url_path );
    // You can do the same for other file types as well
}

require_once __DIR__.'/index.php';
