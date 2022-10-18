<?php
namespace teik\Theme;

use Timber\Twig_Function;
use Timber\Twig_Filter;
use Twig\TwigFilter;
use teik\Theme\Controllers\Helper;
use Twig\TwigTest;

/**
 * Class used to change Timber configuration
 *
 * @see \Timber\Timber
 */
class Timber {
  use \teik\Theme\Traits\Singleton;

  public function hook() {
    add_filter('timber/context', array($this,'context'),10, 1);
    add_filter('timber/twig', array($this,'twig'),10, 1);
  }

  /**
   * Extend Timber context
   */
  public function context($context) {
    $context['request'] = new \Timber\Request();
    $context['menus'] =[
      'main' => new Menu('main'),
      'footer_rules' => new Menu('footer_rules')
    ];
    $context['site']->logo = new \Timber\Image(teik_get_logo());
    $context['options'] = get_fields('options');
    $context['widgets'] = array(
      'footer_1' => \Timber\Timber::get_widgets( 'footer-1' ),
      'footer_2' => \Timber\Timber::get_widgets( 'footer-2' ),
      'footer_3' => \Timber\Timber::get_widgets( 'footer-3' ),
      'sidebar' => \Timber\Timber::get_widgets( 'sidebar' ),
    );
    return $context;
  }

  /**
   * Extend twig
   */
  public function twig($twig) {
    /**
     * Add function to Twig
     */
    $twig->addFunction( new Twig_Function('teik_asset', 'teik_asset'));
    $twig->addFunction( new Twig_Function('teik_svg', 'teik_svg'));
    $twig->addFunction( new Twig_Function('img', 'teik_image_tag'));
    $twig->addFunction( new Twig_Function('block_href', 'teik_get_block_href'));
    $twig->addFunction( new Twig_Function('controller', 'teik_controller'));
    $twig->addFunction( new Twig_Function('menu_depth', 'teik_menu_depth'));
    $twig->addFunction( new Twig_Function('Helper', function(){
      return new Helper();
    }));
    $twig->addFunction( new Twig_Function('teik_icon', 'teik_icon'));
    $twig->addFilter( new TwigFilter('exclamation_mark', 'teik_exclamation_mark'));
    $twig->addTest( new TwigTest('is_number', 'is_numeric'));
    return $twig;
  }
}