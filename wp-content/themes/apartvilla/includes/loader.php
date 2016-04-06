<?php

if( !class_exists( 'Bunch_Base' ) ) include_once trailingslashit(get_template_directory()) . '/includes/base.php';
add_action( 'init', 'apartvilla_theme_init');


if( !function_exists( 'apartvilla_set' ) ) {
	function apartvilla_set( $var, $key, $def = '' )
	{
		if( !$var ) return false;
	
		if( is_object( $var ) && isset( $var->$key ) ) return $var->$key;
		elseif( is_array( $var ) && isset( $var[$key] ) ) return $var[$key];
		elseif( $def ) return $def;
		else return false;
	}
}


if( !function_exists('apartvilla_printr' ) ) {
	function apartvilla_printr($data)
	{
		echo '<pre>'; print_r($data);exit;
	}
}

if( !function_exists('apartvilla_font_awesome' ) ) {
	function apartvilla_font_awesome( $index )
	{
		$array = array_values($GLOBALS['apartvilla_font_awesome']);
		if( $font = apartvilla_set($array, $index )) return $font;
		else return false;
	}
}

if( !function_exists('apartvilla_load_class' ) ) {
	function apartvilla_load_class($class, $directory = 'libraries', $global = true, $prefix = 'Bunch_')
	{
		$obj = &$GLOBALS['_bunch_base'];
		$obj = is_object( $obj ) ? $obj : new stdClass;
	
		$name = FALSE;
	
		// Is the request a class extension?  If so we load it too
		$path = get_template_directory().'/includes/'.$directory.'/'.$class.'.php';
		if( file_exists($path) )
		{
			$name = $prefix.ucwords( $class );
	
			if (class_exists($name) === FALSE)	require($path);
		}
	
		// Did we find the class?
		if ($name === FALSE) exit('Unable to locate the specified class in theme: '.$class.'.php');
	
		if( $global ) $GLOBALS['_bunch_base']->$class = new $name();
		else new $name();
	}
}


include_once trailingslashit(get_template_directory()) .'/includes/library/form_helper.php';
include_once trailingslashit(get_template_directory()) .'/includes/library/functions.php';
include_once trailingslashit(get_template_directory()) . '/includes/library/widgets.php';

apartvilla_load_class( 'enqueue', 'helpers', false );
apartvilla_load_class( 'seo', 'helpers', false );
apartvilla_load_class( 'bootstrap_walker', 'helpers', false );



if( apartvilla_set( $_GET, 'bunch_shortcode_editor_action' ) ) {
	include_once trailingslashit(get_template_directory()) . '/resource/shortcode_output.php';exit;
}

/**
 * Include Vafpress Framework
 */
//require_once 'vafpress/bootstrap.php';
	

if( is_admin() )
/** Plugin Activation */

get_template_part( 'includes/thirdparty/tgm-plugin-activation/plugins' );


function apartvilla_theme_init()
{
	
	global $pagenow;
	
	return;
	
	/**
	 * Include Custom Data Sources
	 */
	require_once 'vafpress/admin/data_sources.php';
	
	/**
	 * Load options, metaboxes, and shortcode generator array templates.
	 */
	// metaboxes
	$tmpl_mb1  = include BUNCH_ROOT.'includes/vafpress/admin/metabox/meta_boxes.php';
	// * Create instances of Metaboxes
	foreach( $tmpl_mb1 as $tmb )  new VP_Metabox($tmb);
	
	$tmpl_mb1  = include BUNCH_ROOT.'includes/vafpress/admin/taxonomy/taxonomy.php';
	include_once trailingslashit(get_template_directory()) . '/vp_new/taxonomy.php';
	foreach( $tmpl_mb1 as $tmb )  new Bunch_Metabox($tmb);
	
	
	// shortocode generators
	$tmpl_sg1  = BUNCH_ROOT.'includes/vafpress/admin/shortcode_generator/shortcodes1.php';
	$tmpl_sg2  = BUNCH_ROOT.'includes/vafpress/admin/shortcode_generator/shortcodes2.php';

	if( is_admin() ) {
		
		include_once trailingslashit(get_template_directory()) . '/helpers/backup_class.php';
		$backup = new Bunch_Backup_class;
		
		if( apartvilla_set( $_GET, 'page' ) == 'apartvilla'.'_option' ) 
		{
			if( apartvilla_set( $_GET, 'bunch_dummydata_export' ) ){
				
				$backup->export();
			}
			
			/*if( apartvilla_set( $_GET, 'bunch_dummydata_import' ) ){ 
				include_once trailingslashit(get_template_directory()) . '/helpers/backup_class.php';
				$backup = new Bunch_Backup_class;
				$backup->import();
			}*/
			
		}
	}	
	
	if( function_exists( 'apartvilla_vc_map' )) 
	include_once trailingslashit(get_template_directory()) . '/vc_map.php';
	
	// options
	$tmpl_opt  = BUNCH_ROOT.'includes/vafpress/admin/option/option.php';
	
	
	/**
	 * Create instance of Options
	 */
	$theme_options = new VP_Option(array(
		'is_dev_mode'           => false,                                  // dev mode, default to false
		'option_key'            => 'apartvilla'.'_theme_options',                      // options key in db, required
		'page_slug'             => 'apartvilla'.'_option',                      // options page slug, required
		'template'              => $tmpl_opt,                              // template file path or array, required
		'menu_page'             => 'themes.php',                           // parent menu slug or supply `array` (can contains 'icon_url' & 'position') for top level menu
		'use_auto_group_naming' => true,                                   // default to true
		'use_util_menu'         => true,                                   // default to true, shows utility menu
		'minimum_role'          => 'edit_theme_options',                   // default to 'edit_theme_options'
		'layout'                => 'fluid',                                // fluid or fixed, default to fixed
		'page_title'            => esc_html__( 'Theme Options', 'apartvilla' ), // page title
		'menu_label'            => esc_html__( 'Theme Options', 'apartvilla' ), // menu label
	));
	
		_WSH()->user_extra( array('facebook'=>esc_html__('Facebook', 'apartvilla'), 'twitter'=>esc_html__('Twitter', 'apartvilla'), 'dribbble'=>esc_html__('Dribble', 'apartvilla'), 'github'=>esc_html__('Github', 'apartvilla'),
	'flickr'=>esc_html__('Flickr', 'apartvilla'), 'google-plus'=>esc_html__('Google+', 'apartvilla'), 'youtube'=>esc_html__('Youtube', 'apartvilla')) );
	
	$bunch_exlude_hooks = include_once trailingslashit(get_template_directory()) . '/resource/remove_action.php';

	foreach( $bunch_exlude_hooks as $k => $v )
	{
		foreach( $v as $value )
		remove_action( $k, $value[0], $value[1] );
	}

}


//include_once trailingslashit(get_template_directory()) . '/vp_new/loader.php';



// shortocode generators
 /*$tmpl_sg1  = BUNCH_ROOT.'includes/vafpress/admin/shortcode_generator/shortcodes1.php';
 $tmpl_sg = array(
  'name' => 'sg_1',
  'template' => $tmpl_sg1,
  'modal_title' => esc_html__( 'Pretty Shortcodes', 'apartvilla'),
  'button_title' => esc_html__('Pretty', 'apartvilla'),
  'types' => array(  'page' ),
  'main_image' => get_template_directory_uri() . '/includes/vafpress/public/img/vp_shortcode_icon.png',
  'sprite_image' => get_template_directory_uri() . '/includes/vafpress/public/img/vp_shortcode_icon_sprite.png',
  'included_pages' => array( 'appearance_page_vpt_option' ),
 );
 $sg = new VP_ShortcodeGenerator($tmpl_sg);*/
