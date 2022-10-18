<?php
namespace teik\Theme\Controllers;

class SearchController extends Controller
{
  use \teik\Theme\Traits\Singleton;


  public function hook()
  {
    add_action('wp_ajax_nopriv_search', [$this,'search']);
    add_action('wp_ajax_search', [$this,'search']);
  }

  public function search()
  {

  }
}