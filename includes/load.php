<?php

require_once( TEIK_THEME_DIR . 'includes/acf/acf.php' );
/**
 * Podstawowe funkcje motywu
 */
require_once( TEIK_THEME_DIR .'includes/helpers.php' );
new teik\Theme\SetupTheme();

\teik\Theme\Timber::instance()->hook();
// \teik\Theme\Controllers\WidgetsController::instance()->hook();
// \teik\Theme\Controllers\FormConsentsController::instance()->hook();
// \teik\Theme\Controllers\BlocksController::instance()->hook();


$teikControllers = require_once TEIK_THEME_DIR . 'includes/controllers.php';
foreach ($teikControllers as $key => $controller) {
  $controller->hook();
}

$teikBlocks = require_once TEIK_THEME_DIR . 'includes/blocks.php';
foreach ($teikBlocks as $key => $block) {
  $block->hook();
}



