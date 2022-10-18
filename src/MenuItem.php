<?php
namespace teik\Theme;

class MenuItem extends \Timber\MenuItem {
  public function onHome() {
		return get_the_ID() == get_option('page_on_front');
	}

	public function scrollEnabled() {
		return $this->meta('is_scrollable');
	}

  public function link() {
		$this->url = parent::link();
		if($this->scrollEnabled()) {
			$this->url = '#'.$this->meta('section');
			if(!$this->onHome()) {
				$this->url = get_permalink(get_option('page_on_front')).$this->url;
			}
		}
		return $this->url;
	}

	public function linkClass()	{
		$classes = array();
		if($this->onHome() && $this->scrollEnabled()) {
			$classes[] = 'js-anchor';
		}
		return trim(implode(' ', $classes));
	}
}