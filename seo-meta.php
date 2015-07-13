<?php
/*
Plugin Name: SEO Meta
Plugin URI: http://amauri.champeaux.fr/promotion/reseaux-sociaux/meta-open-graph-twitter-card/
Description: Optimisation SEO en ajoutant automatiquement les balises meta OG et Twitter Cards.
Version: 0.1
Author: Amauri CHAMPEAUX
Author URI: http://amauri.champeaux.fr/a-propos
*/

/**
 * Generate meta tag for SEO and social sharing.
 */
add_action('wp_head', 'seo_meta_tags');
function seo_meta_tags() {
    if (!is_single()) {
        return;
    }
    
    $ID            = get_the_ID();
    $titre         = seo_meta_get_title();
	$excerpt       = get_the_excerpt();
    $url           = seo_meta_current_url();
    $sitename      = esc_attr(get_bloginfo('name'));
    $image         = seo_meta_getImage( $ID );
    
    echo '<meta property="og:type" content="article" />
    <meta property="og:title" content="'.$titre.'" />
	<meta property="og:description" content="'.$excerpt.'" />
    <meta property="og:url" content="'.$url.'" />
    <meta property="og:site_name" content="'.$sitename.'" />
    <meta property="og:image" content="'.$image.'" />
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:domain" content="'.$sitename.'"/>
    <meta name="twitter:image:src" content="'.$image.'"/>';
}

/**
 * Construct the current url.
 * HTTPS supported.
 */
function seo_meta_current_url() {
    $host = 'http://';
    if (isset($_SERVER['HTTPS'])) {$host = 'https://';}

    return $host . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Return an image for the post
 *
 * - featured if available
 * - one image on the post
 */
function seo_meta_getImage($id) {
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
	if ($image[0] != '') {
		return $image[0];
	}
	
	$images = get_attached_media('image', $id);
	foreach($images as $img) {
		$i = wp_get_attachment_image_src($img->ID, 'large');
		if ($i[0] != '') {
			return $i[0];
		}
	}
	
	return '';
}

/**
 * Get and format the title for be sharing friendly.
 */
function seo_meta_get_title() {
	$title = wp_title('|', false, 'right');
	if ($title == '') {
		$title = esc_textarea(get_bloginfo('name'));
	} else {
		$title_arr = explode('|', $title);
		$title = trim($title_arr[0]);
	}
	
	return $title;
}
