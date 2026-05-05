<?php
if (!defined('ABSPATH')) { exit; }

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('html5', ['style', 'script']);
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_editor_style(['assets/css/tokens.css', 'assets/css/base.css']);
});

add_action('wp_enqueue_scripts', function () {
    $uri = get_stylesheet_directory_uri();
    $dir = get_stylesheet_directory();

    wp_enqueue_style(
        'adonis-fonts',
        'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500&family=Inter+Tight:wght@400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        [],
        null
    );

    wp_enqueue_style('adonis-tokens', $uri . '/assets/css/tokens.css', [], filemtime($dir . '/assets/css/tokens.css'));
    wp_enqueue_style('adonis-base',   $uri . '/assets/css/base.css',   ['adonis-tokens'], filemtime($dir . '/assets/css/base.css'));

    wp_enqueue_script(
        'adonis-gender-toggle',
        $uri . '/assets/js/gender-toggle.js',
        [],
        filemtime($dir . '/assets/js/gender-toggle.js'),
        ['in_footer' => false, 'strategy' => 'defer']
    );
});

add_action('init', function () {
    register_block_type(__DIR__ . '/blocks/button');
    register_block_type(__DIR__ . '/blocks/card');
});

/**
 * Apply persisted gender as a body class server-side via cookie, so the
 * first paint already has the right palette. The JS reconciles localStorage
 * → cookie afterwards.
 */
add_filter('body_class', function ($classes) {
    $gender = isset($_COOKIE['adonis_gender']) ? sanitize_key($_COOKIE['adonis_gender']) : 'men';
    if (!in_array($gender, ['men', 'women'], true)) {
        $gender = 'men';
    }
    $classes[] = $gender;
    return $classes;
});
