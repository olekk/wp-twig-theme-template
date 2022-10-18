<?php

/**
 * Plik zawiera podstawowe funkcje, które będą używane w każdym motywie
 */

function teik_get_logo() {
	$logo = get_theme_mod( 'custom_logo' );
	return $logo;
}

/**
 * Returns URI to given asset
 */
function teik_asset($path) {
	return ASSETS_PATH . $path;
}

function teik_svg($path) {
	if(file_exists($path)) {
		return file_get_contents($path);
	} else {
		$path = TEIK_THEME_DIR .'dist/images/'.$path;
		return file_get_contents($path);
	}
}

function format_phone( $string, $html = true ) {
	$string = str_replace(' ', '', $string);
	$string = str_replace('-', '', $string);
	$string = str_replace('+', '00', $string);
  return esc_html($string);
}
/**
 * @param Timber\Image image object
 */
function teik_image_tag($image, $size = 'medium') {
	if(empty($size)) {
		$size = 'medium';
	}
	if(!$image) {
		return;
	}
	if(!$image->ID) {
		$image = get_field('default_images','option');
		$image = $image['featured_image'];
		$image = new \Timber\Image($image);
	} else {
		// return '<img src="'. ASSETS_PATH . 'images/default-blog.jpg' .'" alt="Default post image">';
	}
	return get_image_tag($image->ID, $image->alt, $image->title, 'none', $size);
}

function teik_controller($name = '') {
	$c = '\teik\Theme\Controllers\\'.$name.'Controller';
	return $c::instance();
}

function teik_get_block_href($blockName = '', $link = '', $page = '') {
	if(empty($page)) {
		$page = get_post(get_option('page_on_front'));
	}
	if(empty($link)) {
		$link = get_permalink($page);
	}
	$blocks = parse_blocks( $page->post_content );
	foreach ($blocks as $key => $block) {
		if(empty($block['blockName'])) continue;

		if($block['blockName'] == $blockName) {
			$href = (isset($block['attrs']['anchor']) && !empty($block['attrs']['anchor']))
				? $block['attrs']['anchor'] : $block['attrs']['id'];
			break;
		}
	}
	return $link.'#'.$href;
}

function teik_menu_depth($item, &$depth = 1) {
	if($item->children) {
		$depth++;
		foreach ($item->children as $key => $item) {
			teik_menu_depth($item,$depth);
		}
	}
	return $depth;
}

function teik_find_blocks($name, $blocks) {
	foreach ($blocks as $key => $block) {
		if($name == $block['blockName']) {
			yield $block;
		}
	}
	return false;
}

function teik_icon($image, $size = ''){
	$extension = pathinfo($image->file_loc, PATHINFO_EXTENSION);
	$html = '';
	if($extension == 'svg') {
		$html = teik_svg($image->file_loc);
	} else {
		$html = teik_image_tag($image, $size);
	}
	return $html;
}


function teik_exclamation_mark($content) {
	$content = str_replace('#!#', '<span class="exclamation-mark"><span class="exclamation-mark__icon">!</span></span>', $content);
	return $content;
}