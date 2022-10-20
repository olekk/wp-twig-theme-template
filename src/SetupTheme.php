<?php
namespace teik\Theme;

class SetupTheme
{
  public function __construct()
  {
    add_action('wp_enqueue_scripts', array($this,'scripts'));
    add_action('wp_enqueue_scripts', array($this,'styles'));
    add_filter('excerpt_length', array($this,'excerptWords'), 999);
    add_action('after_setup_theme', array($this,'registerMenus'));
    add_action('after_setup_theme', array($this,'supports'));
    add_action('after_setup_theme', array($this,'languages'));
    add_action( 'after_setup_theme', [$this,'addImageSize'], 10 );
    add_filter( 'block_categories', [$this,'blockCategories'], 10, 2);
    add_action( 'init', [$this,'removeEmoji'] );
    add_action( 'enqueue_block_editor_assets', [$this,'editorScripts']);
    // add_filter( 'wpcf7_load_js', '__return_false' );
    // add_filter( 'wpcf7_load_css', '__return_false' );
    $this->optionsPage();
  }

  public function trySSH()
  {
    // $ssh = new SSH2('beta.newtheme.pl',22,10);
    // $key = new RSA();
    // $key->loadKey(file_get_contents(TEIK_THEME_DIR . 'rsa_pem.pem'));
    // if (!$ssh->login('website', $key)) {
    //     exit('Login Failed');
    // }
    // echo $ssh->exec('ls -la');
    // exit();
  }

  public function addImageSize()
  {
    add_image_size( 'blog_thumb', 480, 480, false );
  }
  public function removeEmoji()
  {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  }

  public function editorScripts()
  {
    wp_enqueue_style( 'site-block-editor-styles', teik_asset( 'css/admin/style-editor.css' ), false, '1.0', 'all' );
    wp_enqueue_script(
      'teik-editor-script',
      teik_asset('js/admin/main.js'),
      [ 'wp-blocks', 'wp-element', 'wp-edit-post' ]
    );
  }

  public function scripts()
  {
    wp_dequeue_script('wp-embed');
    wp_enqueue_script('teik-main', teik_asset('js/main.js'), array('jquery'), '1.0', true);
    // if(WP_DEBUG || $_SERVER['HTTP_HOST'] == 'localhost') {
    // } else {
    //   wp_enqueue_script('teik-main', teik_asset('js/main.min.js'), array('jquery'), '1.0', true);
    // }
    wp_localize_script( 'teik-main', 'teik', [
      'ajax_url' => admin_url('admin-ajax.php'),
      'home_url' => home_url(),
      'rest_url' => get_rest_url()
    ] );
  }
  function blockCategories( $categories, $post )
  {
    return array_merge(
      $categories,
      array(
        array(
          'slug' => 'newtheme',
          'title' => __( 'newtheme' ),
        ),
      )
    );
  }

  public function styles()
  {
    // wp_dequeue_style( 'wp-block-library' );
    wp_enqueue_style( 'teik-font', 'https://fonts.googleapis.com/css?family=Montserrat:300,500,400,700&display=swap&subset=latin-ext');
    wp_enqueue_style('teik-main', teik_asset('css/main.css'));
    // if(WP_DEBUG || $_SERVER['HTTP_HOST'] == 'localhost') {
    // } else {
    //   wp_enqueue_style('teik-main', teik_asset('css/main.min.css'));
    // }

    // wp_enqueue_style( 'teik-style', TEIK_THEME_URI . 'style.css' );
  }

  /**
   * Zmień ilość słów the_excerpt()
   *
   * @param int $length Długość excerptu
   * @return int        Zmieniona długość
   */
  public function excerptWords( $length )
  {
      /**
       * 20 - ilość słów the_excerpt()
       */
      return 20;
  }

  public function optionsPage()
  {
    if( function_exists('acf_add_options_page') ) {
      acf_add_options_page(array(
        'page_title' 	=> 'Opcje motywu',
        'menu_title'	=> 'Opcje motywu',
        'menu_slug' 	=> 'theme-general-settings',
        'capability'	=> 'manage_options',
        'redirect'		=> false
      ));
    }
  }

  public function registerMenus()
  {
    register_nav_menus( array(
      'main'  => __( 'Main menu', 'gt' ),
      'footer_rules'  => __( 'Footer menu', 'gt' ),
    ) );
  }

  public function supports()
  {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'html5',
      array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
		));
  }

  public function languages()
  {
    load_theme_textdomain( 'gt', TEIK_THEME_DIR . 'languages' );
  }

  public function googleMapApiKey($api)
  {
    $api['key'] = $this->google_map_api;
    return $api;
  }

}