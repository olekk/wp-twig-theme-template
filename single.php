<?php
  get_header();
  $context = \Timber\Timber::get_context();
  $queried = get_queried_object();

  $controller = teik\Theme\Controllers\BlogController::instance();
  $context['controller'] = $controller;

  $context['post'] = \Timber\Timber::get_post();
  $context['queried_object'] = $queried;
  $context['archive_link'] = get_post_type_archive_link( $queried->post_type );
  $context['widgets']['sidebar'] = $controller->getSidebar();

  \Timber\Timber::render(
    [
      'single/'.$queried->post_type.'.twig',
      'single/post.twig'
    ],
    $context);
  get_footer();
?>
