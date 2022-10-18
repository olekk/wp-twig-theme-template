<?php
  get_header();
  $context = \Timber\Timber::get_context();
  $queried = get_queried_object();
  if(is_tax() || is_category()) {
    $taxonomy = get_taxonomy( $queried->taxonomy );
    $title = $taxonomy->labels->singular_name. ': '. $queried->name;
  } else {
    $title = $queried->label;
  }
  $context['posts'] = new \Timber\PostQuery;
  $context['title'] = $title;
  $context['queried_object'] = $queried;

  \Timber\Timber::render(
    [
      'archives/'.$queried->name.'.twig',
      'index.twig'
    ],
    $context);
  get_footer();
?>
