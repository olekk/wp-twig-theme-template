<?php
namespace teik\Theme\Controllers;

use teik\Theme\Traits\Singleton;

class YoutubeEmbedController extends Controller
{
  use Singleton;

  public function hook()
  {
    add_action('wp_ajax_nopriv_teik_ytembed', [$this, 'ajaxEmbed']);
    add_action('wp_ajax_teik_ytembed', [$this, 'ajaxEmbed']);
  }

  public function ajaxEmbed()
  {
    if(isset($_REQUEST['url']) && !empty($_REQUEST['url'])) {
      wp_send_json_success( [
        'iframe' => $this->getEmbed($_REQUEST['url'])
      ], 200 );
    }
  }

  public function getEmbed($url) {
    return wp_oembed_get($url,['data-src' => $url]);
  }
}