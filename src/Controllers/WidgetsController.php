<?php
namespace teik\Theme\Controllers;

class WidgetsController extends Controller {
  use \teik\Theme\Traits\Singleton;

  public function hook() {
    add_action('widgets_init', [$this, 'registerAreas']);
    // add_action('widgets_init', [$this, 'registerWidgets']);
    add_filter('dynamic_sidebar_params', [$this, 'widget_html'], 10, 1);
  }

  public function registerWidgets()
  {
    $namespace = '\teik\Theme\Widgets\\';
  }
  public function registerAreas() {
    register_sidebar( array(
      'name' => __( 'Sidebar' ),
      'id' => 'sidebar',
      'description' => __( '' ),
      'before_widget' => '<aside id="%1$s" class="widget widget--sidebar %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<header class="heading heading--widget heading--sidebar"><h3>',
      'after_title'   => '</h3></header>',
    ) );
    register_sidebar( array(
      'name' => __( 'Blog sidebar' ),
      'id' => 'blog-sidebar',
      'description' => __( '' ),
      'before_widget' => '<aside id="%1$s" class="blog-widget %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<header class="heading heading--separated heading--small heading--no-offset heading--colored"><h3>',
      'after_title'   => '</h3></header>',
    ) );
    register_sidebar( array(
      'name' => __( 'Footer 1' ),
      'id' => 'footer-1',
      'description' => __( 'Widgets in this area will be shown in footer' ),
      'before_widget' => '<aside id="%1$s" class="widget widget--footer %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<header class="heading heading--widget"><h3>',
      'after_title'   => '</h3></header>',
    ) );
    register_sidebar( array(
      'name' => __( 'Footer 2' ),
      'id' => 'footer-2',
      'description' => __( 'Widgets in this area will be shown in footer' ),
      'before_widget' => '<aside id="%1$s" class="widget widget--footer %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<header class="heading heading--widget"><h3>',
      'after_title'   => '</h3></header>',
    ) );
    register_sidebar( array(
      'name' => __( 'Footer 3' ),
      'id' => 'footer-3',
      'description' => __( 'Widgets in this area will be shown in footer' ),
      'before_widget' => '<aside id="%1$s" class="widget widget--footer %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<header class="heading heading--widget"><h3>',
      'after_title'   => '</h3></header>',
    ) );
  }


  public function widget_html( $params )
  {
    if(strpos($params[0]['widget_id'],'text') === false) {
      return $params;
    }
    $widget_id = $params[0]['widget_id'];
    $icon = get_field('icon', 'widget_'.$widget_id);
    $color_variant = get_field('color_variant', 'widget_'.$widget_id);
    if($color_variant) {
      $color_variant = $color_variant ? 'widget--'. $color_variant : '';
      $params[0]['before_widget'] = str_replace('class="widget', 'class="widget '. $color_variant, $params[0]['before_widget'] );
    }
    $params[0]['before_widget'] .= '<div class="widget__body">';
    if($icon) {
      $icon = new \Timber\Image($icon);
      $icon = '<figure class="widget__icon">'.teik_icon($icon).'</figure>';
      $params[0]['before_widget'] .= $icon;
    }
    $params[0]['after_widget'] = '</div>'.$params[0]['after_widget'];
    return $params;
  }
}