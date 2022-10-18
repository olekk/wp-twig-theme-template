<?php
/**
 * Core setup
 * Ustawia stałe dla motywu.
 */
define( 'TEIK_THEME_DIR', trailingslashit(get_template_directory()) );
define( 'TEIK_THEME_URI', trailingslashit(get_template_directory_uri()) );
/**
 * Ustawia stałe do załączania plików *.js i *.css
 *
 * Przykład:
 * wp_enqueue_script( 'teik-main', JS_PATH .'/nazwa.pliku.min.js', array( 'jquery' ),'1.0',true );
 */
define( 'ASSETS_PATH', TEIK_THEME_URI . 'dist/');
define( 'JS_PATH', TEIK_THEME_URI . 'dist/js/');
define( 'CSS_PATH', TEIK_THEME_URI . 'dist/css/');

add_filter('acf/settings/path', 'teik_acf_settings_path');
function teik_acf_settings_path( $path ) {
  $path = TEIK_THEME_DIR . 'includes/acf/';

  return $path;
}

add_filter('acf/settings/dir', 'teik_acf_settings_dir');
function teik_acf_settings_dir( $dir ) {
  $dir = TEIK_THEME_URI . 'includes/acf/';

  return $dir;
}

require_once( TEIK_THEME_DIR . 'vendor/autoload.php' );
new \Timber\Timber;

add_filter('xmlrpc_enabled', '__return_false');


function mailtrap($phpmailer) {
  $phpmailer->isSMTP();
  $phpmailer->Host = 'smtp.mailtrap.io';
  $phpmailer->SMTPAuth = true;
  $phpmailer->Port = 2525;
  $phpmailer->Username = '28d0e0fd46c62b';
  $phpmailer->Password = 'cd62c313219769';
}

add_action('phpmailer_init', 'mailtrap');

// add_filter('script_loader_tag', function($tag, $handle) {
//   $skip = ['jquery-core'];
//   if(is_admin() || in_array($handle, $skip)) {
//     return $tag;
//   }
//   return str_replace(' src', ' defer="defer" src', $tag);
// }, 10, 2);



add_action( 'save_post_form_element', 'teik_save_post', 10, 3 );

function teik_save_post($postID, $post, $update) {
  $form = get_field('wybierz_formularz', $postID);
  error_log(print_r($form,true));
}