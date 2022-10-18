<?php
/**
 * Template name: Strona z sidebarem
 */
?>
<?php
get_header();

$context = \Timber\Timber::get_context();
$context['post'] = \Timber\Timber::get_post();
\Timber\Timber::render('page-with-sidebar.twig', $context);

get_footer();
?>