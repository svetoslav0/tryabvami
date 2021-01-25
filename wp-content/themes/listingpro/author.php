<?php
/**
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 */

$object_id  =   get_queried_object_id();
$check_listings =   new WP_Query( array( 'post_type' => 'listing', 'posts_per_page' => 1, 'author' => $object_id ) );
$check_listings =   $check_listings->found_posts;


    get_header();
    include(locate_template('templates/author/author-banner.php'));
    include(locate_template('templates/author/author-content.php'));
    get_footer();

?>