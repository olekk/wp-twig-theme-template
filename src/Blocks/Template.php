<?php
namespace teik\Theme\Blocks;

use teik\Theme\Traits\Singleton;
use Timber\Timber;

class Template extends AbstractBlock {
  use Singleton;
  public $name = 'template';    //twig file name
  public $title = 'Template';
  
  public function render($block, $content = '', $is_preview = false, $post_id = 0) {
    $fields = get_fields();

    $collections = Timber::get_terms('collections');
    foreach ($collections as $collection) {
      $collection->guid = get_term_link($collection);
    }
    $options = get_fields('options');

    $context = array_merge(
      $fields ?: [],
      [
      'post_id' => $post_id,
      'is_preview' => $is_preview,
      'col' => $collections,
      'options' => $options,
      ]
    );
    Timber::render('components/blocks/'.$this->name.'.twig', $context);
  }
}

