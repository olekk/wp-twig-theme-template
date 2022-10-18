<?php
namespace teik\Theme\Controllers;

class Helper extends \Timber\Helper {
  public static function linkHtml($acfLink, $classes = []) {
    if(!is_array($acfLink)) {
      throw new \Exception('Passed link is not valid ACF Link field');
    }
    extract($acfLink);
    $a = '<a href="'. $url .'"'.($target ? 'target="'.$target.'"' : '').(!empty($classes) ? ' class="'. implode(' ', $classes) .'"' : '').'>'
        . $title
        .'</a>';
    return $a;
  }
}