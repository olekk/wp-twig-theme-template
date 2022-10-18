<?php
namespace teik\Theme\Controllers;

use Timber\Timber;

class BlocksController extends Controller
{
  use \teik\Theme\Traits\Singleton;


  public function hook()
  {
    add_action('init', [$this,'registerBlocks']);
  }

  public function registerBlocks()
  {
    wp_register_script(
      'teik-custom-blocks',
      teik_asset('js/admin/blocks.js'),
      ['wp-blocks', 'wp-element']
    );
    $this->registerNestedRows();
    $this->registerContainer();
    $this->registerCoreGroup();
  }

  private function registerCoreGroup()
  {
    register_block_type( 'core/group', [
      'render_callback' => [$this, 'renderCoreGroup']
    ] );
  }

  public function renderCoreGroup($attributes, $content)
  {
    $context = [
      'InnerBlocks' => $content,
      'post_id' => get_the_ID(),
      'attributes' => $attributes,
    ];
    return Timber::compile('components/blocks/wp-group.twig', $context);
  }

  private function registerContainer()
  {
    // wp_register_script(
    //   'teik-container',
    //   teik_asset('js/admin/blocks.js'),
    //   ['wp-blocks', 'wp-element']
    // );

    register_block_type( 'newtheme/container', [
      'editor_script' => 'teik-custom-blocks',
      'render_callback' => [$this, 'renderContainer']
    ] );
  }

  public function renderContainer($attributes, $content)
  {
    return '<div class="container">'.
            '<div class="container__shrink">'.
            $content
            .'</div>'
           .'</div>';
  }

  private function registerNestedRows()
  {
    // wp_register_script(
    //   'teik-nested-rows',
    //   teik_asset('js/admin/nested-rows.js'),
    //   ['wp-blocks', 'wp-element']
    // );

    register_block_type( 'newtheme/nested-rows', [
      'editor_script' => 'teik-custom-blocks',
      'render_callback' => [$this, 'renderNestedRows']
    ] );
  }

  public function renderNestedRows($attributes, $content)
  {
    $context = [
      'InnerBlocks' => $content,
      'post_id' => get_the_ID(),
      'attributes' => $attributes,
    ];
    return Timber::compile('components/blocks/nested-rows.twig', $context);
  }
}
