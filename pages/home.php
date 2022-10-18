<?php
/**
 * Template name: Home
 */
?>
<?php
get_header();

$context = \Timber\Timber::get_context();
$context['post'] = \Timber\Timber::get_post();
\Timber\Timber::render('home.twig', $context);

get_footer();
?>
