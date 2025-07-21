<?php 



define('ROOT', get_template_directory_uri());
define('CSS', ROOT . '/dist/css' );
define('IMAGE', ROOT . '/dist/img' );
define('JS', ROOT . '/dist/js');
define('WOOCOMMERCE', ROOT . '/dist/js/woocommerce');
define('LIB', ROOT . '/lib');
define('BLOCK', ROOT . '/blocks');
define('IMAGE_DEFAULT', ROOT . '/dist/img/productos/default.png');
require_once ('class/class-tgm-plugin-activation.php');
require_once ('class/requerid-plugins.php');
require_once ('class/class-wp-walker.php');
require_once ('inc/register-theme.php');
require_once ('inc/register-text-editor.php');
require_once ('inc/register-scripts-style.php');
require_once ('inc/custom-func.php');
require_once ('inc/register-menus.php');
require_once ('inc/register-blocks.php');
require_once ('inc/register-theme-options.php');
require_once ('inc/register-newsletter.php');
require_once ('inc/register-forms.php');
require_once ('inc/woocommerce/register-hooks.php');
require_once ('inc/woocommerce/register-woocommerce.php');
require_once ('inc/woocommerce/register-seo-title.php');
require_once ('inc/woocommerce/register-pagination.php');
require_once ('inc/woocommerce/register-envialoSimple.php');
require_once ('inc/editor-tablas/pdv-tabla.php');
require_once ('inc/register-sync-acf.php');
require_once ('inc/register-column-panel-wp.php');


function permitir_query_personalizado($vars) {
    $vars[] = 'paged'; // Permite el parámetro "query"
    return $vars;
}
add_filter('query_vars', 'permitir_query_personalizado');