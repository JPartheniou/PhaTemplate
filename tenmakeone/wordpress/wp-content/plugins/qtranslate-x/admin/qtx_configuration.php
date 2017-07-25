<?php // encoding: utf-8
/*
	Copyright 2014  qTranslate Team  (email : qTranslateTeam@gmail.com )

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

// Exit if accessed directly
if ( !defined( 'WP_ADMIN' ) ) exit;

require_once(dirname(__FILE__).'/qtx_admin_utils.php');
require_once(dirname(__FILE__).'/qtx_admin_options.php');
require_once(dirname(__FILE__).'/qtx_languages.php');
require_once(dirname(__FILE__).'/qtx_import_export.php');
require_once(dirname(__FILE__).'/qtx_user_options.php');

function qtranxf_collect_translations_deep( $qfields, $sep ) {
	$content = reset($qfields);
	//qtranxf_dbg_log('qtranxf_collect_translations_deep: $content: ',$content);
	if(is_string($content)) return qtranxf_join_texts($qfields,$sep);
	$result = array();
	foreach($content as $f => $r){
		$texts = array();
		foreach($qfields as $lang => &$vals){
			$texts[$lang] = $vals[$f];
		}
		$result[$f] = qtranxf_collect_translations_deep($texts,$sep); // recursive call
	}
	return $result;
}

function qtranxf_collect_translations( &$qfields, &$request, $edit_lang ) {
	if(isset($qfields['qtranslate-separator'])){
		$sep = $qfields['qtranslate-separator'];
		unset($qfields['qtranslate-separator']);
		$qfields[$edit_lang] = $request;
		$request = qtranxf_collect_translations_deep($qfields,$sep);
	}else{
		foreach($qfields as $nm => &$vals){
			qtranxf_collect_translations($vals,$request[$nm],$edit_lang); // recursive call
		}
	}
}

function qtranxf_collect_translations_posted() {
	//qtranxf_dbg_log('qtranxf_collect_translations_posted: REQUEST: ',$_REQUEST);
	if(!isset($_REQUEST['qtranslate-fields'])) return;
	//$edit_lang = isset($_COOKIE['qtrans_edit_language']) ? $_COOKIE['qtrans_edit_language'] : qtranxf_getLanguage();
	$edit_lang = qtranxf_getLanguageEdit();
	foreach($_REQUEST['qtranslate-fields'] as $nm => &$qfields){
		//qtranxf_dbg_log('qtranxf_collect_translations_posted: REQUEST[qtranslate-fields]['.$nm.']: ',$qfields);
		qtranxf_collect_translations($qfields,$_REQUEST[$nm],$edit_lang);
		//qtranxf_dbg_log('qtranxf_collect_translations_posted: collected REQUEST['.$nm.']: ',$_REQUEST[$nm]);
		if(isset($_POST[$nm])){
			//qtranxf_dbg_log('qtranxf_collect_translations_posted: POST['.$nm.']: ',$_POST[$nm]);
			$_POST[$nm] = $_REQUEST[$nm];
		}
		if(isset($_GET[$nm])){
			//qtranxf_dbg_log('qtranxf_collect_translations_posted: GET['.$nm.']: ',$_GET[$nm]);
			$_GET[$nm] = $_REQUEST[$nm];
		}
	}
	unset($_REQUEST['qtranslate-fields']);
	unset($_POST['qtranslate-fields']);
	unset($_GET['qtranslate-fields']);
}
add_action('plugins_loaded', 'qtranxf_collect_translations_posted', 5);

function qtranxf_init_admin()
{
	global $q_config;

	qtranxf_admin_loadConfig();

	$next_thanks = get_option('qtranslate_next_thanks');
	if($next_thanks !== false && $next_thanks < time()){
		$messages = get_option('qtranslate_admin_notices');
		if(isset($messages['next_thanks'])){
			unset($messages['next_thanks']);
			update_option('qtranslate_admin_notices',$messages);
		}
		$next_thanks = false;
	}
	if($next_thanks===false){
		$next_thanks = time() + rand(100,300)*24*60*60;
		update_option('qtranslate_next_thanks', $next_thanks);
	}

	// update Gettext Databases if on back-end
	if($q_config['auto_update_mo']){
		require_once(dirname(__FILE__).'/qtx_update_gettext_db.php');
		qtranxf_updateGettextDatabases();
	}

	// update definitions if necessary
	if(current_user_can('manage_categories')){
		//qtranxf_updateTermLibrary();
		qtranxf_updateTermLibraryJoin();
	}
}
//add_action('qtranslate_init_begin','qtranxf_init_admin');
add_action('admin_init','qtranxf_init_admin');

function qtranxf_get_custom_admin_js ($pages) {
	global $pagenow;
	//qtranxf_dbg_echo('qtranxf_get_custom_admin_js: $pagenow: ',$pagenow);
	//qtranxf_dbg_echo('qtranxf_get_custom_admin_js: $script_name=',$script_name);
	//if(!isset($_SERVER['REQUEST_URI'])) return false;
	//$uri=$_SERVER['REQUEST_URI'];
	$qs = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
	foreach($pages as $page_conf){
		//list($page,$path) = explode(':',$page_path);
		$page_conf_parts = explode(':',$page_conf);
		//qtranxf_dbg_echo('qtranxf_get_custom_admin_js: $page_conf_parts: ',$page_conf_parts);
		$uri = $page_conf_parts[0];
		$uri_parts = explode('?',$uri);
		//qtranxf_dbg_echo('qtranxf_get_custom_admin_js: $uri_parts: ',$uri_parts);
		$page = $uri_parts[0];
		//qtranxf_dbg_echo('qtranxf_get_custom_admin_js: $page: ',$page);
		if( $page !== $pagenow ) continue;
		if(isset($uri_parts[1]) && strpos($qs,$uri_parts[1])===FALSE) continue;
		return isset($page_conf_parts[1]) ? $page_conf_parts[1] : 'admin/js/edit-custom-page';
	}
	/*
		Filter allows to load custom script.
		Return path relative to the location of qTranslate-X plugin folder, when needed.
	*/
	$script=apply_filters('qtranslate_custom_admin_js',null);
	if($script) return $script;
	return false;
}

function qtranxf_select_admin_js ($enqueue_script=false) {
	global $pagenow;
	global $q_config;
	if(!isset($_SERVER['SCRIPT_NAME'])) return false;
	$script_name=$_SERVER['SCRIPT_NAME'];
	if(!preg_match('#/wp-admin/([^/]+)\.php#',$script_name,$matches)) return false;
	$fn=$matches[1];
	switch($fn){
		//case '/wp-admin/post-new.php':
		//case '/wp-admin/post.php':
		case 'post':
		case 'post-new':
			$script='admin/js/edit-post'; break;
		//case '/wp-admin/edit-tags.php':
		case 'edit-tags':
			if(isset($_SERVER['QUERY_STRING']) && strpos($_SERVER['QUERY_STRING'],'action=edit')!==FALSE ){
				$script='admin/js/edit-tag';
			}else{
				$script='admin/js/edit-tags';
			}
			break;
		//case '/wp-admin/nav-menus.php':
		case 'nav-menus':
			if(isset($_SERVER['QUERY_STRING'])){
				$qs=$_SERVER['QUERY_STRING'];
				//qtranxf_dbg_echo('$qs=',$qs);
				if(preg_match('/action=([^&#]+)/',$qs,$matches)){
					//qtranxf_dbg_echo('$matches[1]=',$matches[1]);
					if( $matches[1] != 'edit' ) return false;
				}
				//if(strpos($qs,'action=')!==FALSE && strpos($qs,'action=edit')===FALSE) return false;
			}
			$script='admin/js/edit-nav-menus';
			break;
		//case '/wp-admin/options-general.php':
		case 'options-general':
			if(isset($_SERVER['QUERY_STRING'])){
				$qs=$_SERVER['QUERY_STRING'];
				if(strpos($qs,'page=')!==FALSE) return false;
			}
			$script='admin/js/edit-options-general'; break;
		default:
			$script=qtranxf_get_custom_admin_js($q_config['custom_pages']);
			//qtranxf_dbg_echo('qtranxf_select_admin_js: $script: ',$script);
			if(!$script) return false;
			break;
	}
	$plugin_dir_path=plugin_dir_path(QTRANSLATE_FILE);
	$script_path=$script.'.min.js'; $fn=$plugin_dir_path.$script_path;
	while(!file_exists($fn)){
		$script_path=$script.'.js'; $fn=$plugin_dir_path.$script_path;
		if(file_exists($fn)) break;
		$script_path=$script; $fn=$plugin_dir_path.$script_path;
		if(file_exists($fn)) break;
		return false;
	}
	if($enqueue_script){
		$script_url=plugins_url( $script_path, QTRANSLATE_FILE );
		//wp_register_script( 'qtranslate-admin-edit', $script_url, array('qtranslate-admin-common'), QTX_VERSION );
		wp_register_script( 'qtranslate-admin-edit', $script_url, array(), QTX_VERSION );
		wp_enqueue_script( 'qtranslate-admin-edit' );
	}
	//qtranxf_dbg_echo('qtranxf_select_admin_js: $fn: ',$fn);
	return $fn;
}

/**
 * load field configurations for the current admin page
 */
function qtranxf_load_admin_page_config() {
	global $pagenow;

	$page_config = array();

	$page_configs = array();//will be set to a default in the future

	$page_configs = apply_filters('qtranslate_load_admin_page_config',$page_configs);
	//qtranxf_dbg_log('qtranxf_load_admin_page_config: $page_configs:',json_encode($page_configs,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

	foreach($page_configs as $pgcfg){
		foreach($pgcfg['pages'] as $page => $query){
			//qtranxf_dbg_log('qtranxf_load_admin_page_config: $page='.$page.'; query=',$query);
			if( preg_match('!'.$page.'!',$pagenow) !== 1 ) continue;
			//qtranxf_dbg_log('qtranxf_load_admin_page_config: preg_match($page,$pagenow) ok. $_SERVER[QUERY_STRING]=',$_SERVER['QUERY_STRING']);
			if( !empty($query) && (isset($_SERVER['QUERY_STRING']) && preg_match('!'.$query.'!',$_SERVER['QUERY_STRING']) !== 1 ) ) continue;
			//qtranxf_dbg_log('qtranxf_load_admin_page_config: preg_match($query,$_SERVER[QUERY_STRING] ok');

			if( isset($pgcfg['anchors']) && !empty($pgcfg['anchors']) ){
				if( !isset($page_config['anchors']) ) $page_config['anchors'] = $pgcfg['anchors'];
				else $page_config['anchors'] = array_merge($page_config['anchors'],$pgcfg['anchors']);
			}

			if( isset($pgcfg['forms']) && !empty($pgcfg['forms']) ){
				if( !isset($page_config['forms']) ) $page_config['forms'] = $pgcfg['forms'];
				else $page_config['forms'] = array_merge($page_config['forms'],$pgcfg['forms']);
			}

			if( isset($pgcfg['js-conf']) && !empty($pgcfg['js-conf']) ){
				if( !isset($page_config['js-conf']) ) $page_config['js-conf'] = $pgcfg['js-conf'];
				else $page_config['js-conf'] = array_merge($page_config['js-conf'],$pgcfg['js-conf']);
			}

			if( isset($pgcfg['js-exec']) && !empty($pgcfg['js-exec']) ){
				if( !isset($page_config['js-exec']) ) $page_config['js-exec'] = $pgcfg['js-exec'];
				else $page_config['js-exec'] = array_merge($page_config['js-exec'],$pgcfg['js-exec']);
			}

			break;//each $pgcfg should not have more than one configuration for the same page
		}
	}
	return $page_config;
}

function qtranxf_add_admin_footer_js ( $enqueue_script=false ) {
	global $q_config;
	if( $q_config['editor_mode'] == QTX_EDITOR_MODE_RAW) return;
	//qtranxf_dbg_log('qtranxf_add_admin_footer_js: pagenow: ',$pagenow);
	//qtranxf_dbg_log('qtranxf_add_admin_footer_js: post_type: ',$post_type);
	if(isset($q_config['post_type_excluded']) && !empty($q_config['post_type_excluded'])){
		global $pagenow, $post_type;
		switch($pagenow){
			case 'post.php':
			case 'post-new.php':
				if(in_array($post_type,$q_config['post_type_excluded'])) return;
			default: break;
		}
	}
	$script_file = qtranxf_select_admin_js($enqueue_script);
	$page_config = qtranxf_load_admin_page_config();
	if(!$script_file && empty($page_config))
		return;

	wp_dequeue_script('autosave');
	wp_deregister_script( 'autosave' );//autosave script saves the active language only and messes it up later in a hard way

	if( $enqueue_script ){
		//wp_register_script( 'qtranslate-admin-utils', plugins_url( 'js/utils.min.js', __FILE__ ), array(), QTX_VERSION, true );
		//wp_enqueue_script( 'qtranslate-admin-utils' );
		$deps = array();
		if($script_file) $deps[] = 'qtranslate-admin-edit';
		if(isset($page_config['js-conf'])){
			$cnt=0;
			foreach($page_config['js-conf'] as $js){
				if(!isset($js['src'])) continue;
				$handle = isset($js['handle']) ? $js['handle'] : 'qtranslate-admin-js-conf-'.(++$cnt);
				$ver = isset($js['ver']) ? $js['ver'] : QTX_VERSION;
				wp_register_script( $handle, plugins_url($js['src']), $deps, $ver, true);
				$deps[] = $handle;
				wp_enqueue_script( $handle );
			}
		}
		wp_register_script( 'qtranslate-admin-common', plugins_url( 'js/common.min.js', __FILE__ ), $deps, QTX_VERSION, true);
		wp_enqueue_script( 'qtranslate-admin-common' );
		if(isset($page_config['js-exec'])){
			$deps[] = 'qtranslate-admin-common';
			$cnt=0;
			foreach($page_config['js-exec'] as $js){
				if(!isset($js['src'])) continue;
				$handle = isset($js['handle']) ? $js['handle'] : 'qtranslate-admin-js-exec-'.(++$cnt);
				$ver = isset($js['ver']) ? $js['ver'] : QTX_VERSION;
				wp_register_script( $handle, plugins_url($js['src']), $deps, $ver, true);
				$deps[] = $handle;
				wp_enqueue_script( $handle );
			}
		}
	}

	$config=array();
	// since 3.2.9.9.0 'enabled_languages' is replaced with 'language_config' structure
	$keys=array('default_language', 'language', 'url_mode', 'lsb_style_wrap_class', 'lsb_style_active_class'); // ,'term_name', 'plugin_js_composer_off'
	foreach($keys as $key){
		$config[$key]=$q_config[$key];
	}
	$config['custom_fields'] = apply_filters('qtranslate_custom_fields', $q_config['custom_fields']);
	$config['custom_field_classes'] = apply_filters('qtranslate_custom_field_classes', $q_config['custom_field_classes']);
	if($q_config['url_mode']==QTX_URL_DOMAINS){
		$config['domains']=$q_config['domains'];
	}
	$homeinfo=qtranxf_get_home_info();
	$config['url_info_home']=trailingslashit($homeinfo['path']);//$q_config['url_info']['home'];
	$config['flag_location']=qtranxf_flag_location();
	$config['js']=array();
	//$config['flag']=array();//deprecated since 3.2.9.9.0
	//$config['language_name']=array();//deprecated since 3.2.9.9.0
	$config['language_config']=array();
	foreach($q_config['enabled_languages'] as $lang)
	{
		//$config['flag'][$lang]=$q_config['flag'][$lang];
		//$config['language_name'][$lang]=$q_config['language_name'][$lang];
		$config['language_config'][$lang]=array();
		$config['language_config'][$lang]['flag'] = $q_config['flag'][$lang];
		$config['language_config'][$lang]['name'] = $q_config['language_name'][$lang];
		$config['language_config'][$lang]['locale'] = $q_config['locale'][$lang];
	}
	if(!empty($page_config)) $config['page_config'] = $page_config;

	$config['LSB'] = $q_config['editor_mode'] == QTX_EDITOR_MODE_LSB;
	$config = apply_filters('qtranslate_admin_page_config', $config);
?>
<script type="text/javascript">
// <![CDATA[
<?php
	echo 'var qTranslateConfig='.json_encode($config).';'.PHP_EOL;
	if(!$enqueue_script){
		if($script_file) readfile($script_file);
		if(isset($page_config['js-conf'])){
			$plugins_dir = WP_CONTENT_DIR.'/plugins/';
			foreach($page_config['js-conf'] as $js){
				if(!isset($js['src'])) continue;
				readfile($plugins_dir.$js['src']);
			}
		}
		$plugin_dir_path = plugin_dir_path(__FILE__);
		readfile($plugin_dir_path.'js/common.min.js');
		if(isset($page_config['js-exec'])){
			$plugins_dir = WP_CONTENT_DIR.'/plugins/';
			foreach($page_config['js-exec'] as $js){
				if(!isset($js['src'])) continue;
				readfile($plugins_dir.$js['src']);
			}
		}
	}
	if($q_config['qtrans_compatibility']){
		echo 'qtrans_use = function(lang, text) { var result = qtranxj_split(text); return result[lang]; }'.PHP_EOL;
	}
	do_action('qtranslate_add_admin_footer_js');
?>
//]]>
</script>
<?php
}

function qtranxf_add_admin_head_js ($enqueue_script=true) {
	global $q_config;
/*
	echo '<script type="text/javascript">'.PHP_EOL.'// <![CDATA['.PHP_EOL;
	if($enqueue_script){
		wp_register_script( 'qtranslate-admin-utils', plugins_url( 'js/utils.min.js', __FILE__ ), array(), QTX_VERSION );
		wp_enqueue_script( 'qtranslate-admin-utils' );
	}else{
		$plugin_dir_path=plugin_dir_path(__FILE__);
		readfile($plugin_dir_path.'js/utils.min.js');
	}
	//if($q_config['qtrans_compatibility']){
	//	echo 'qtrans_use = function(lang, text) { var result = qtranxj_split(text); return result[lang]; }'.PHP_EOL;
	//}
*/
	if(strpos($_SERVER['REQUEST_URI'],'page=qtranslate-x') !== FALSE) {
		echo '<script type="text/javascript">'.PHP_EOL.'// <![CDATA['.PHP_EOL;
?>
function qtranxj_getcookie(cname)
{
	var nm = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
		var ce = ca[i];
		var p = ce.indexOf(nm);
		if (p >= 0) return ce.substring(p+nm.length,ce.length);
	}
	return '';
}
function qtranxj_delcookie(cname)
{
	var date = new Date();
	date.setTime(date.getTime()-(24*60*60*1000));
	document.cookie=cname+'=; expires='+date.toGMTString();
}
function qtranxj_readShowHideCookie(id) {
	var e=document.getElementById(id);
	if(!e) return;
	if(qtranxj_getcookie(id)){
		e.style.display='block';
	}else{
		e.style.display='none';
	}
}
function qtranxj_toggleShowHide(id) {
	var e = document.getElementById(id);
	if (e.style.display == 'block'){
		qtranxj_delcookie(id);
		e.style.display = 'none';
	}else{
		document.cookie=id+'=1';
		e.style.display='block';
	}
	return false;
}
<?php
		echo '//]]>'.PHP_EOL.'</script>'.PHP_EOL;
	}
}

function qtranxf_add_admin_lang_icons ()
{
	global $q_config;
	echo '<style type="text/css">'.PHP_EOL;
	echo "#wpadminbar #wp-admin-bar-language>div.ab-item{ background-size: 0;";
	echo "background-image: url(".qtranxf_flag_location().$q_config['flag'][$q_config['language']].");}\n";
	foreach($q_config['enabled_languages'] as $language) 
	{
		echo "#wpadminbar ul li#wp-admin-bar-".$language." {background-size: 0; background-image: url(".qtranxf_flag_location().$q_config['flag'][$language].");}\n";
	}
	echo '</style>'.PHP_EOL;
}

/**
 * Add CSS code to highlight the translatable fields */
function qtranxf_add_admin_highlight_css() {
	global $q_config;
	if ( $q_config['highlight_mode'] == QTX_HIGHLIGHT_MODE_NONE || get_the_author_meta( 'qtranslate_highlight_disabled', get_current_user_id() )) {
		return;
	}
	echo '<style type="text/css">' . PHP_EOL;
	$highlight_mode = $q_config['highlight_mode'];
	switch ( $highlight_mode ) {
		case QTX_HIGHLIGHT_MODE_CUSTOM_CSS: echo $q_config['highlight_mode_custom_css']; break;
		default: echo qtranxf_get_admin_highlight_css($highlight_mode);
	}
	echo '</style>' . PHP_EOL;
}

function qtranxf_get_admin_highlight_css($highlight_mode) {
	global $q_config;
	$current_color_scheme = qtranxf_get_user_admin_color();
	$css = '';
	switch ( $highlight_mode ) {
		case QTX_HIGHLIGHT_MODE_LEFT_BORDER:
			$css .= 'input.qtranxs-translatable, textarea.qtranxs-translatable, div.qtranxs-translatable {' . PHP_EOL;
			$css .= 'box-shadow: -3px 0 ' . $current_color_scheme[2] . ' !important;' . PHP_EOL;
			$css .= '}' . PHP_EOL;
			break;
		case QTX_HIGHLIGHT_MODE_BORDER:
			$css .= 'input.qtranxs-translatable, textarea.qtranxs-translatable, div.qtranxs-translatable {' . PHP_EOL;
			$css .= 'outline: 2px solid ' . $current_color_scheme[2] . ' !important;' . PHP_EOL;
			$css .= '}' . PHP_EOL;
			$css .= 'div.qtranxs-translatable div.mce-panel {' . PHP_EOL;
			$css .= 'margin-top: 2px' . PHP_EOL;
			$css .= '}' . PHP_EOL;
			break;
	}
	return $css;
}

function qtranxf_add_admin_css () {
	global $q_config;
	wp_register_style( 'qtranslate-admin-style', plugins_url('css/qtranslate_configuration.css', __FILE__), array(), QTX_VERSION );
	wp_enqueue_style( 'qtranslate-admin-style' );
	qtranxf_add_admin_lang_icons();
	qtranxf_add_admin_highlight_css();
	echo '<style type="text/css" media="screen">'.PHP_EOL;
	$fn = dirname(__FILE__).'/css/opLSBStyle/'.$q_config['lsb_style'];
	if(file_exists($fn)) readfile($fn);
/*
	echo ".qtranxs_title_input { border:0pt none; font-size:1.7em; outline-color:invert; outline-style:none; outline-width:medium; padding:0pt; width:100%; }\n";
	echo ".qtranxs_title_wrap { border-color:#CCCCCC; border-style:solid; border-width:1px; padding:2px 3px; }\n";
	echo "#qtranxs_textarea_content { padding:6px; border:0 none; line-height:150%; outline: none; margin:0pt; width:100%; -moz-box-sizing: border-box;";
	echo	"-webkit-box-sizing: border-box; -khtml-box-sizing: border-box; box-sizing: border-box; }\n";
	echo ".qtranxs_title { -moz-border-radius: 6px 6px 0 0;";
	echo	"-webkit-border-top-right-radius: 6px; -webkit-border-top-left-radius: 6px; -khtml-border-top-right-radius: 6px; -khtml-border-top-left-radius: 6px;";
	echo	"border-top-right-radius: 6px; border-top-left-radius: 6px; }\n";
	echo ".hide-if-no-js.wp-switch-editor.switch-tmce { margin-left:6px !important;}";
	echo "#postexcerpt textarea { height:4em; margin:0; width:98% }";
	echo ".qtranxs_lang_div { float:right; height:12px; width:18px; padding:6px 5px 8px 5px; cursor:pointer }";
	echo ".qtranxs_lang_div.active { background: #DFDFDF; border-left:1px solid #D0D0D0; border-right: 1px solid #F7F7F7; padding:6px 4px 8px 4px }";
*/
	//echo "#qtranxs_debug { width:100%; height:200px }";
	do_action('qtranslate_admin_css');
	do_action('qtranslate_css');//should not be used
	echo '</style>'.PHP_EOL;
}

function qtranxf_admin_head() {
	//qtranxf_add_css();//Since 3.2.5 no longer needed
	qtranxf_add_admin_css();
	qtranxf_add_admin_head_js();
	//Since 3.2.7 qtranxf_optionFilter('disable');//why this is here?
}
add_action('admin_head', 'qtranxf_admin_head');

function qtranxf_admin_footer() {
	$enqueue_script = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG);
	//$enqueue_script = false;
	qtranxf_add_admin_footer_js( $enqueue_script );
}
add_action('admin_footer', 'qtranxf_admin_footer',999);

/* qTranslate-X Management Interface */
function qtranxf_adminMenu() {
	global $menu, $submenu, $q_config;
	// Configuration Page
	add_options_page(__('Language Management', 'qtranslate'), __('Languages', 'qtranslate'), 'manage_options', 'qtranslate-x', 'qtranxf_conf');
}

function qtranxf_language_form($lang = '', $language_code = '', $language_name = '', $language_locale = '', $language_date_format = '', $language_time_format = '', $language_flag ='', $language_na_message = '', $language_default = '', $original_lang='') {
	global $q_config;
?>
<input type="hidden" name="original_lang" value="<?php echo $original_lang; ?>" />
<div class="form-field">
	<label for="language_code"><?php _e('Language Code', 'qtranslate') ?></label>
	<input name="language_code" id="language_code" type="text" value="<?php echo $language_code; ?>" size="2" maxlength="2"/>
	<p><?php echo __('2-Letter <a href="http://www.w3.org/WAI/ER/IG/ert/iso639.htm#2letter">ISO Language Code</a> for the Language you want to insert. (Example: en)', 'qtranslate').'<br/>'.__('The language code is used in language tags and in URLs. It is case sensitive. Use of lower case for the language code is preferable, but not required. The code may be arbitrary chosen by site owner, although it is preferable to use already commonly accepted code if available. Once a language code is created and entries for this language are made, it is difficult to change it, please make a careful decision.', 'qtranslate'); ?></p>
</div>
<div class="form-field">
	<label for="language_flag"><?php _e('Flag', 'qtranslate') ?></label>
	<?php 
	$files = array();
	$flag_dir = trailingslashit(WP_CONTENT_DIR).$q_config['flag_location'];
	if($dir_handle = @opendir($flag_dir)) {
		while (false !== ($file = readdir($dir_handle))) {
			if(preg_match("/\.(jpeg|jpg|gif|png|svg)$/i",$file)) {
				$files[] = $file;
			}
		}
		sort($files);
	}
	if(sizeof($files)>0){
	?>
	<select name="language_flag" id="language_flag" onchange="switch_flag(this.value);"  onclick="switch_flag(this.value);" onkeypress="switch_flag(this.value);">
	<?php
		foreach ($files as $file) {
	?>
		<option value="<?php echo $file; ?>" <?php echo ($language_flag==$file)?'selected="selected"':''?>><?php echo $file; ?></option>
	<?php
		}
	?>
	</select>
	<img src="" alt="<?php _e('Flag', 'qtranslate'); ?>" id="preview_flag" style="vertical-align:middle; display:none"/>
	<?php
	} else {
		_e('Incorrect Flag Image Path! Please correct it!', 'qtranslate');
	}
	?>
	<p><?php _e('Choose the corresponding country flag for language. (Example: gb.png)', 'qtranslate'); ?></p>
</div>
<script type="text/javascript">
//<![CDATA[
	function switch_flag(url) {
		document.getElementById('preview_flag').style.display = "inline";
		document.getElementById('preview_flag').src = "<?php echo qtranxf_flag_location();?>" + url;
	}
	switch_flag(document.getElementById('language_flag').value);
//]]>
</script>
<div class="form-field">
	<label for="language_name"><?php _e('Name', 'qtranslate') ?></label>
	<input name="language_name" id="language_name" type="text" value="<?php echo $language_name; ?>"/>
	<p><?php _e('The Name of the language, which will be displayed on the site. (Example: English)', 'qtranslate'); ?></p>
</div>
<div class="form-field">
	<label for="language_locale"><?php _e('Locale', 'qtranslate') ?></label>
	<input name="language_locale" id="language_locale" type="text" value="<?php echo $language_locale; ?>"  size="5" maxlength="5"/>
	<p>
	<?php _e('PHP and Wordpress Locale for the language. (Example: en_US)', 'qtranslate'); ?><br/>
	<?php _e('You will need to install the .mo file for this language.', 'qtranslate'); ?>
	</p>
</div>
<div class="form-field">
	<label for="language_date_format"><?php _e('Date Format', 'qtranslate') ?></label>
	<input name="language_date_format" id="language_date_format" type="text" value="<?php echo $language_date_format; ?>"/>
	<p><?php _e('Depending on your Date / Time Conversion Mode, you can either enter a <a href="http://www.php.net/manual/function.strftime.php">strftime</a> (use %q for day suffix (st,nd,rd,th)) or <a href="http://www.php.net/manual/function.date.php">date</a> format. This field is optional. (Example: %A %B %e%q, %Y)', 'qtranslate'); ?></p>
</div>
<div class="form-field">
	<label for="language_time_format"><?php _e('Time Format', 'qtranslate') ?></label>
	<input name="language_time_format" id="language_time_format" type="text" value="<?php echo $language_time_format; ?>"/>
	<p><?php _e('Depending on your Date / Time Conversion Mode, you can either enter a <a href="http://www.php.net/manual/function.strftime.php">strftime</a> or <a href="http://www.php.net/manual/function.date.php">date</a> format. This field is optional. (Example: %I:%M %p)', 'qtranslate'); ?></p>
</div>
<div class="form-field">
	<label for="language_na_message"><?php _e('Not Available Message', 'qtranslate') ?></label>
	<input name="language_na_message" id="language_na_message" type="text" value="<?php echo $language_na_message; ?>"/>
	<p>
	<?php _e('Message to display if post is not available in the requested language. (Example: Sorry, this entry is only available in %LANG:, : and %.)', 'qtranslate'); ?><br/>
	<?php _e('%LANG:&lt;normal_separator&gt;:&lt;last_separator&gt;% generates a list of languages separated by &lt;normal_separator&gt; except for the last one, where &lt;last_separator&gt; will be used instead.', 'qtranslate'); ?><br/>
	</p>
</div>
<?php
}

function qtranxf_admin_section_start($section, $nm) {
	echo '<h3>'.$section.'<span id="qtranxs-show-'.$nm.'"> ( <a name="qtranslate_'.$nm.'_settings" href="#" onclick="return qtranxj_toggleShowHide(\'qtranslate-admin-'.$nm.'\');">'.__('Show', 'qtranslate').' / '.__('Hide', 'qtranslate').'</a> )</span></h3>'.PHP_EOL;
	echo '<div id="qtranslate-admin-'.$nm.'" style="display: none">'.PHP_EOL;
}

function qtranxf_admin_section_end($nm) {
?>
<p class="submit">
	<input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes', 'qtranslate') ?>" />
</p>
</div>
<script type="text/javascript">
//<![CDATA[
	qtranxj_readShowHideCookie('qtranslate-admin-<?php echo $nm; ?>');
// ]]>
</script>
<?php
}

function qtranxf_conf() {
	global $q_config, $wpdb;
	//qtranxf_dbg_log('qtranxf_conf: POST: ',$_POST);

	// do redirection for dashboard
	if(isset($_GET['godashboard'])) {
		echo '<h2>'.__('Switching Language', 'qtranslate').'</h2>'.sprintf(__('Switching language to %1$s... If the Dashboard isn\'t loading, use this <a href="%2$s" title="Dashboard">link</a>.','qtranslate'),$q_config['language_name'][qtranxf_getLanguage()],admin_url()).'<script type="text/javascript">document.location="'.admin_url().'";</script>';
		exit();
	}

	// init some needed variables
	$error = '';
	$original_lang = '';
	$language_code = '';
	$language_name = '';
	$language_locale = '';
	$language_date_format = '';
	$language_time_format = '';
	$language_na_message = '';
	$language_flag = '';
	$language_default = '';
	$altered_table = false;

	$message = apply_filters('qtranslate_configuration_pre',array());

	// check for action
	if(isset($_POST['qtranslate_reset']) && isset($_POST['qtranslate_reset2'])) {
		$message[] = __('qTranslate has been reset.', 'qtranslate');
	} elseif(isset($_POST['default_language'])) {

		qtranxf_updateSettings();

		//execute actions

		if ( isset( $_POST['update_mo_now'] ) && $_POST['update_mo_now'] == '1' ) {
			$result = qtranxf_updateGettextDatabases( true );
			if ( $result === true ) {
				$message[] = __( 'Gettext databases updated.', 'qtranslate' );
			} elseif ( is_wp_error( $result ) ) {
				$message[] = __( 'Gettext databases <strong>not</strong> updated:', 'qtranslate' ) . ' ' . $result->get_error_message();
			}
		}

		foreach($_POST as $key => $value){
			if(!is_string($value)) continue;
			if(!qtranxf_endsWith($key,'-migration')) continue;
			$plugin = substr($key,0,-strlen('-migration'));
			if($value == 'import'){
				$nm = '<span style="color:blue"><strong>'.qtranxf_get_plugin_name($plugin).'</strong></span>';
				$message[] = sprintf(__('Applicable options and taxonomy names from plugin %s have been imported. Note that the multilingual content of posts, pages and other objects has not been altered during this operation. There is no additional operation needed to import content, since its format is compatible with %s.', 'qtranslate'), $nm, 'qTranslate&#8209;X').' '.sprintf(__('It might be a good idea to review %smigration instructions%s, if you have not yet done so.', 'qtranslate'),'<a href="https://qtranslatexteam.wordpress.com/2015/02/24/migration-from-other-multilingual-plugins/" target="_blank">','</a>');
				$message[] = sprintf(__('%sImportant%s: Before you start making edits to post and pages, please, make sure that both, your front site and admin back-end, work under this configuration. It may help to review "%s" and see if any of conflicting plugins mentioned there are used here. While the current content, coming from %s, is compatible with this plugin, the newly modified posts and pages will be saved with a new square-bracket-only encoding, which has a number of advantages comparing to former %s encoding. However, the new encoding is not straightforwardly compatible with %s and you will need an additional step available under "%s" option if you ever decide to go back to %s. Even with this additional conversion step, the 3rd-party plugins custom-stored data will not be auto-converted, but manual editing will still work. That is why it is advisable to create a test-copy of your site before making any further changes. In case you encounter a problem, please give us a chance to improve %s, send the login information to the test-copy of your site to %s along with a detailed step-by-step description of what is not working, and continue using your main site with %s meanwhile. It would also help, if you share a success story as well, either on %sthe forum%s, or via the same e-mail as mentioned above. Thank you very much for trying %s.', 'qtranslate'), '<span style="color:red">', '</span>', '<a href="https://wordpress.org/plugins/qtranslate-x/other_notes/" target="_blank">'.'Known Issues'.'</a>', $nm, 'qTranslate', $nm, '<span style="color:magenta">'.__('Convert Database', 'qtranslate').'</span>', $nm, 'qTranslate&#8209;X', '<a href="mailto:qtranslateteam@gmail.com">qtranslateteam@gmail.com</a>', $nm, '<a href="https://wordpress.org/support/plugin/qtranslate-x">', '</a>', 'qTranslate&#8209;X').'<br/><small>'.__('This is a one-time message, which you will not see again, unless the same import is repeated.', 'qtranslate').'</small>';
				if ($plugin == 'mqtranslate'){
					$message[] = sprintf(__('Option "%s" has also been turned on, as the most common case for importing configuration from %s. You may turn it off manually if your setup does not require it. Refer to %sFAQ%s for more information.', 'qtranslate'), '<span style="color:magenta">'.__('Compatibility Functions', 'qtranslate').'</span>', $nm, '<a href="https://wordpress.org/plugins/qtranslate-x/faq/" target="_blank">', '</a>');
				}
			}elseif($value == 'export'){
				$nm = '<span style="color:blue"><strong>'.qtranxf_get_plugin_name($plugin).'</strong></span>';
				$message[] = sprintf(__('Applicable options have been exported to plugin %s. If you have done some post or page updates after migrating from %s, then "%s" operation is also required to convert the content to "dual language tag" style in order for plugin %s to function.', 'qtranslate'), $nm, $nm, '<span style="color:magenta">'.__('Convert Database', 'qtranslate').'</span>', $nm);
			}
		}

		if(isset($_POST['convert_database'])){
			$msg = qtranxf_convert_database($_POST['convert_database']);
			if($msg) $message[] = $msg;
		}
	}

	if(isset($_POST['original_lang'])) {
		// validate form input
		$lang = sanitize_text_field($_POST['language_code']);
		if($_POST['language_na_message']=='') $error = __('The Language must have a Not-Available Message!', 'qtranslate');
		if(strlen($_POST['language_locale'])<2) $error = __('The Language must have a Locale!', 'qtranslate');
		if($_POST['language_name']=='') $error = __('The Language must have a name!', 'qtranslate');
		if(strlen($lang)!=2) $error = __('Language Code has to be 2 characters long!', 'qtranslate');
		//$lang = strtolower($lang);
		//$language_names = qtranxf_language_configured('language_name');
		$langs=array(); qtranxf_load_languages($langs);
		$language_names = $langs['language_name'];
		if($_POST['original_lang']==''&&$error=='') {
			// new language
			if(isset($language_names[$lang])) {
				$error = __('There is already a language with the same Language Code!', 'qtranslate');
			} 
		} 
		if($_POST['original_lang']!=''&&$error=='') {
			// language update
			if($lang!=$_POST['original_lang']&&isset($language_names[$lang])) {
				$error = __('There is already a language with the same Language Code!', 'qtranslate');
			} else {
				if($lang!=$_POST['original_lang']){
					// remove old language
					qtranxf_unsetLanguage($langs,$_POST['original_lang']);
					qtranxf_unsetLanguage($q_config,$_POST['original_lang']);
				}
				if(in_array($_POST['original_lang'],$q_config['enabled_languages'])) {
					// was enabled, so set modified one to enabled too
					for($i = 0; $i < sizeof($q_config['enabled_languages']); $i++) {
						if($q_config['enabled_languages'][$i] == $_POST['original_lang']) {
							$q_config['enabled_languages'][$i] = $lang;
						}
					}
				}
				if($_POST['original_lang']==$q_config['default_language']){
					// was default, so set modified the default
					$q_config['default_language'] = $lang;
				}
			}
		}

		/**
			@since 3.2.9.5
			In earlier versions the 'if' below used to work correctly, but magic_quotes has been removed from PHP for a while, and 'if(get_magic_quotes_gpc())' is now always 'false'.
			However, WP adds magic quotes anyway via call to add_magic_quotes() in
			./wp-includes/load.php:function wp_magic_quotes()
			called from
			./wp-settings.php: wp_magic_quotes()
			Then it looks like we have to always 'stripslashes' now, although it is dangerous, since applying 'stripslashes' twice messes it up.
			This problem reveals when, for example, '\a' format is in use.
			Possible test for '\' character, instead of 'get_magic_quotes_gpc()' can be 'strpos($_POST['language_date_format'],'\\\\')' for this particular case.
			If Wordpress ever decides to remove calls to wp_magic_quotes, then this place will be in trouble again.
			Discussions:
			http://wordpress.stackexchange.com/questions/21693/wordpress-and-magic-quotes
		*/
		//if(get_magic_quotes_gpc()) {
			//qtranxf_dbg_log('get_magic_quotes_gpc: before REQUEST[language_date_format]=',$_REQUEST['language_date_format']);
			//qtranxf_dbg_log('get_magic_quotes_gpc: before POST[language_date_format]=',$_POST['language_date_format']);
			//qtranxf_dbg_log('pos=',strpos($_POST['language_date_format'],'\\\\'));//shows a number
			if(isset($_POST['language_date_format'])) $_POST['language_date_format'] = stripslashes($_POST['language_date_format']);
			if(isset($_POST['language_time_format'])) $_POST['language_time_format'] = stripslashes($_POST['language_time_format']);
			//qtranxf_dbg_log('pos=',strpos($_POST['language_date_format'],'\\\\'));//shows false
			//qtranxf_dbg_log('get_magic_quotes_gpc: after REQUEST[language_date_format]=',$_REQUEST['language_date_format']);
			//qtranxf_dbg_log('get_magic_quotes_gpc: after POST[language_date_format]=',$_POST['language_date_format']);
		//}
		if($error=='') {
			// everything is fine, insert language
			$q_config['language_name'][$lang] = sanitize_text_field($_POST['language_name']);
			$q_config['flag'][$lang] = sanitize_text_field($_POST['language_flag']);
			$q_config['locale'][$lang] = sanitize_text_field($_POST['language_locale']);
			$q_config['date_format'][$lang] = sanitize_text_field($_POST['language_date_format']);
			$q_config['time_format'][$lang] = sanitize_text_field($_POST['language_time_format']);
			$q_config['not_available'][$lang] = wp_kses_data($_POST['language_na_message']);
			qtranxf_copyLanguage($langs, $q_config, $lang);
			qtranxf_save_languages($langs);
		}
		if($error!=''||isset($_GET['edit'])) {
			// get old values in the form
			$original_lang = sanitize_text_field($_POST['original_lang']);
			$language_code = $lang;
			$language_name = sanitize_text_field($_POST['language_name']);
			$language_locale = sanitize_text_field($_POST['language_locale']);
			$language_date_format = sanitize_text_field($_POST['language_date_format']);
			$language_time_format = sanitize_text_field($_POST['language_time_format']);
			$language_na_message = wp_kses_data($_POST['language_na_message']);
			$language_flag = sanitize_text_field($_POST['language_flag']);
			$language_default = isset($_POST['language_default']) ? sanitize_text_field($_POST['language_default']) : $q_config['default_language'];
		}
	} elseif(isset($_GET['convert'])){
		// update language tags
		global $wpdb;
		$wpdb->show_errors();
		$cnt = 0;
		//this will not work correctly if set of languages is different
		foreach($q_config['enabled_languages'] as $lang) {
			$cnt +=
			$wpdb->query('UPDATE '.$wpdb->posts.' set post_title = REPLACE(post_title, "[lang_'.$lang.']","[:'.$lang.']"),  post_content = REPLACE(post_content, "[lang_'.$lang.']","[:'.$lang.']")');
			$wpdb->query('UPDATE '.$wpdb->posts.' set post_title = REPLACE(post_title, "[/lang_'.$lang.']","[:]"),  post_content = REPLACE(post_content, "[/lang_'.$lang.']","[:]")');
		}
		if($cnt > 0){
			$message[] = sprintf(__('%d database entries have been converted.', 'qtranslate'), $cnt);
		}else{
			$message[] = __('No database entry has been affected while processing the conversion request.', 'qtranslate');
		}
	} elseif(isset($_GET['markdefault'])){
		// update language tags
		global $wpdb;
		$wpdb->show_errors();
		$result = $wpdb->get_results('SELECT ID, post_content, post_title, post_excerpt, post_type FROM '.$wpdb->posts.' WHERE post_status = \'publish\' AND  (post_type = \'post\' OR post_type = \'page\') AND NOT (post_content LIKE \'%<!--:-->%\' OR post_title LIKE \'%<!--:-->%\' OR post_content LIKE \'%![:!]%\' ESCAPE \'!\' OR post_title LIKE \'%![:!]%\' ESCAPE \'!\')');
		if(is_array($result)){
			$cnt_page = 0;
			$cnt_post = 0;
			foreach($result as $post) {
				$title=qtranxf_mark_default($post->post_title);
				$content=qtranxf_mark_default($post->post_content);
				$excerpt=qtranxf_mark_default($post->post_excerpt);
				if( $title==$post->post_title && $content==$post->post_content && $excerpt==$post->post_excerpt ) continue;
				switch($post->post_type){
					case 'post': ++$cnt_post; break;
					case 'page': ++$cnt_page; break;
				}
				//qtranxf_dbg_log('markdefault:'. PHP_EOL .'title old: '.$post->post_title. PHP_EOL .'title new: '.$title. PHP_EOL .'content old: '.$post->post_content. PHP_EOL .'content new: '.$content); continue;
				$wpdb->query($wpdb->prepare('UPDATE '.$wpdb->posts.' set post_content = %s, post_title = %s, post_excerpt = %s WHERE ID = %d', $content, $title, $excerpt, $post->ID));
			}

			if($cnt_page > 0) $message[] = sprintf(__('%d pages have been processed to set the default language.', 'qtranslate'), $cnt_page);
			else $message[] = __('No initially untranslated pages found to set the default language', 'qtranslate');

			if($cnt_post > 0) $message[] = sprintf(__('%d posts have been processed to set the default language.', 'qtranslate'), $cnt_post);
			else $message[] = __('No initially untranslated posts found to set the default language.', 'qtranslate');

			$message[] = sprintf(__('Post types other than "post" or "page", as well as unpublished entries, will have to be adjusted manually as needed, since there is no common way to automate setting the default language otherwise. It can be done with a custom script though. You may request a %spaid support%s for this.', 'qtranslate'), '<a href="https://qtranslatexteam.wordpress.com/contact-us/">', '</a>');
		}
	} elseif(isset($_GET['edit'])){
		$lang = $_GET['edit'];
		$original_lang = $lang;
		$language_code = $lang;
		//$langs = $q_config;
		$langs = array(); qtranxf_languages_configured($langs);
		$language_name = isset($langs['language_name'][$lang])?$langs['language_name'][$lang]:'';
		$language_locale = isset($langs['locale'][$lang])?$langs['locale'][$lang]:'';
		$language_date_format = isset($langs['date_format'][$lang])?$langs['date_format'][$lang]:'';
		$language_time_format = isset($langs['time_format'][$lang])?$langs['time_format'][$lang]:'';
		$language_na_message = isset($langs['not_available'][$lang])?$langs['not_available'][$lang]:'';
		$language_flag = isset($langs['flag'][$lang])?$langs['flag'][$lang]:'';
	} elseif(isset($_GET['delete'])) {
		$lang = $_GET['delete'];
		// validate delete (protect code)
		//if($q_config['default_language']==$lang) $error = 'Cannot delete Default Language!';
		//if(!isset($q_config['language_name'][$lang])||strtolower($lang)=='code') $error = __('No such language!', 'qtranslate');
		if(empty($error)) {
			// everything seems fine, delete language
			$error = qtranxf_deleteLanguage($lang);
		}
	} elseif(isset($_GET['enable'])) {
		$lang = $_GET['enable'];
		// enable validate
		if(!qtranxf_enableLanguage($lang)) {
			$error = __('Language is already enabled or invalid!', 'qtranslate');
		}
	} elseif(isset($_GET['disable'])) {
		$lang = $_GET['disable'];
		// enable validate
		if($lang==$q_config['default_language'])
			$error = __('Cannot disable Default Language!', 'qtranslate');
		if(!qtranxf_isEnabled($lang))
			if(!isset($q_config['language_name'][$lang]))
				$error = __('No such language!', 'qtranslate');
		// everything seems fine, disable language
		if($error=='' && !qtranxf_disableLanguage($lang)) {
			$error = __('Language is already disabled!', 'qtranslate');
		}
	} elseif(isset($_GET['moveup'])) {
		$languages = qtranxf_getSortedLanguages();
		$msg = __('No such language!', 'qtranslate');
		foreach($languages as $key => $language) {
			if($language!=$_GET['moveup']) continue;
			if($key==0) {
				$msg = __('Language is already first!', 'qtranslate');
				break;
			}
			$languages[$key] = $languages[$key-1];
			$languages[$key-1] = $language;
			$q_config['enabled_languages'] = $languages;
			$msg = __('New order saved.', 'qtranslate');
			break;
		}
		$message[] = $msg;
	} elseif(isset($_GET['movedown'])) {
		$languages = qtranxf_getSortedLanguages();
		$msg = __('No such language!', 'qtranslate');
		foreach($languages as $key => $language) {
			if($language!=$_GET['movedown']) continue;
			if($key==sizeof($languages)-1) {
				$msg = __('Language is already last!', 'qtranslate');
				break;
			}
			$languages[$key] = $languages[$key+1];
			$languages[$key+1] = $language;
			$q_config['enabled_languages'] = $languages;
			$msg = __('New order saved.', 'qtranslate');
			break;
		}
		$message[] = $msg;
	}

	$everything_fine = ((isset($_POST['submit'])||isset($_GET['delete'])||isset($_GET['enable'])||isset($_GET['disable'])||isset($_GET['moveup'])||isset($_GET['movedown']))&&$error=='');
	if($everything_fine) {
		// settings might have changed, so save
		qtranxf_saveConfig();
		if(empty($message)) {
			$message[] = __('Options saved.', 'qtranslate');
		}
	}
	if($q_config['auto_update_mo']) {
		if(!is_dir(WP_LANG_DIR) || !$ll = @fopen(trailingslashit(WP_LANG_DIR).'qtranslate.test','a')) {
			$error = sprintf(__('Could not write to "%s", Gettext Databases could not be downloaded!', 'qtranslate'), WP_LANG_DIR);
		} else {
			@fclose($ll);
			@unlink(trailingslashit(WP_LANG_DIR).'qtranslate.test');
		}
	}
	// don't accidentally delete/enable/disable twice
	$clean_uri = preg_replace("/&(delete|enable|disable|convert|markdefault|moveup|movedown)=[^&#]*/i","",$_SERVER['REQUEST_URI']);
	$clean_uri = apply_filters('qtranslate_clean_uri', $clean_uri);

// Generate XHTML
	$plugindir = dirname(plugin_basename(QTRANSLATE_FILE));
	$pluginurl=WP_PLUGIN_URL.'/'.$plugindir;
?>
<?php
	if (!empty($message)) :
		foreach($message as $msg){
?>
<div id="message" class="updated fade"><p><strong><?php echo $msg; ?></strong></p></div>
<?php } endif; ?>
<?php if ($error!='') : ?>
<div id="message" class="error fade"><p><strong><?php echo $error; ?></strong></p></div>
<?php endif; ?>

<?php if(isset($_GET['edit'])) { ?>
<div class="wrap">
<h2><?php _e('Edit Language', 'qtranslate'); ?></h2>
<form action="" method="post" id="qtranxs-edit-language">
<?php qtranxf_language_form($language_code, $language_code, $language_name, $language_locale, $language_date_format, $language_time_format, $language_flag, $language_na_message, $language_default, $original_lang); ?>
<p class="submit"><input type="submit" name="submit" value="<?php _e('Save Changes &raquo;', 'qtranslate'); ?>" /></p>
</form>
<p><small><a href="<?php echo admin_url('options-general.php?page=qtranslate-x'); ?>"><?php _e('back to configuration page', 'qtranslate'); ?></a></small></p>
</div>
<?php } else { ?>
<div class="wrap">
<h2><?php _e('Language Management (qTranslate Configuration)', 'qtranslate'); ?></h2>
<small><?php printf(__('For help on how to configure qTranslate correctly, take a look at the <a href="%1$s">qTranslate FAQ</a> and the <a href="%2$s">Support Forum</a>.', 'qtranslate')
, 'https://qtranslatexteam.wordpress.com/faq/'
//, 'http://wordpress.org/plugins/qtranslate-x/faq/'
, 'https://wordpress.org/support/plugin/qtranslate-x'); ?></small>
	<form action="<?php echo $clean_uri;?>" method="post">
	<?php  qtranxf_admin_section_start(__('General Settings', 'qtranslate'),'general'); //id="qtranslate-admin-general" ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('Default Language / Order', 'qtranslate') ?></th>
				<td>
					<fieldset id="qtranxs-languages-menu"><legend class="hidden"><?php _e('Default Language', 'qtranslate') ?></legend>
				<?php
					$flag_location = qtranxf_flag_location();
					foreach ( qtranxf_getSortedLanguages() as $key => $language ) {
						echo '<label title="' . $q_config['language_name'][$language] . '"><input type="radio" name="default_language" value="' . $language . '"';
						checked($language,$q_config['default_language']);
						echo ' />';
						echo ' <a href="'.add_query_arg('moveup', $language, $clean_uri).'"><img src="'.$pluginurl.'/arrowup.png" alt="up" /></a>';
						echo ' <a href="'.add_query_arg('movedown', $language, $clean_uri).'"><img src="'.$pluginurl.'/arrowdown.png" alt="down" /></a>';
						echo ' <img src="' . $flag_location.$q_config['flag'][$language] . '" alt="' . $q_config['language_name'][$language] . '" /> ';
						echo ' '.$q_config['language_name'][$language] . '</label><br/>'.PHP_EOL;
					}
				?>
					<small><?php printf(__('Choose the default language of your blog. This is the language which will be shown on %s. You can also change the order the languages by clicking on the arrows above.', 'qtranslate'), get_bloginfo('url')); ?></small>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Untranslated Content', 'qtranslate');?></th>
				<td>
					<p><?php printf(__('The choices below define how to handle untranslated content at front-end of the site. A content of a page or a post is considered untranslated if the main text (%s) is empty for a given language, regardless of other fields like title, excerpt, etc. All three options are independent of each other.', 'qtranslate'), 'post_content') ?></p>
					<br/>
					<label for="hide_untranslated"><input type="checkbox" name="hide_untranslated" id="hide_untranslated" value="1"<?php checked($q_config['hide_untranslated']); ?>/> <?php _e('Hide Content which is not available for the selected language.', 'qtranslate') ?></label>
					<br/>
					<small><?php _e('When checked, posts will be hidden if the content is not available for the selected language. If unchecked, a message will appear showing all the languages the content is available in.', 'qtranslate'); ?>
					<?php _e('The message about available languages for the content of a post or a page may also appear if a single post display with an untranslated content if viewed directly.', 'qtranslate') ?>
					<?php printf(__('This function will not work correctly if you installed %s on a blog with existing entries. In this case you will need to take a look at option "%s" under "%s" section.', 'qtranslate'), 'qTranslate', __('Convert Database','qtranslate'), __('Import', 'qtranslate').'/'.__('Export', 'qtranslate')); ?></small>
					<br/><br/>
					<label for="show_displayed_language_prefix"><input type="checkbox" name="show_displayed_language_prefix" id="show_displayed_language_prefix" value="1"<?php checked($q_config['show_displayed_language_prefix']); ?>/> <?php _e('Show displayed language prefix when content is not available for the selected language.', 'qtranslate'); ?></label>
					<br/>
					<small><?php _e('This is relevant to all fields other than the main content of posts and pages. Such untranslated fields are always shown in an alternative available language, and will be prefixed with the language name in parentheses, if this option is on.', 'qtranslate'); ?></small>
					<br/><br/>
					<label for="show_alternative_content"><input type="checkbox" name="show_alternative_content" id="show_alternative_content" value="1"<?php checked($q_config['show_alternative_content']); ?>/> <?php _e('Show content in an alternative language when translation is not available for the selected language.', 'qtranslate'); ?></label>
					<br/>
					<small><?php printf(__('When a page or a post with an untranslated content is viewed, a message with a list of other available languages is displayed, in which languages are ordered as defined by option "%s". If this option is on, then the content in default language will also be shown, instead of the expected language, for the sake of user convenience. If default language is not available for the content, then the content in the first available language is shown.', 'qtranslate'), __('Default Language / Order', 'qtranslate')); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Detect Browser Language', 'qtranslate');?></th>
				<td>
					<label for="detect_browser_language"><input type="checkbox" name="detect_browser_language" id="detect_browser_language" value="1"<?php checked($q_config['detect_browser_language']); ?>/> <?php _e('Detect the language of the browser and redirect accordingly.', 'qtranslate'); ?></label>
					<br/>
					<small><?php _e('When the frontpage is visited via bookmark/external link/type-in, the visitor will be forwarded to the correct URL for the language specified by his browser.', 'qtranslate'); ?></small>
				</td>
			</tr>
		</table>
	<?php qtranxf_admin_section_end('general'); ?>
	<?php qtranxf_admin_section_start(__('Advanced Settings', 'qtranslate'),'advanced'); //id="qtranslate-admin-advanced"
		$permalink_is_query = qtranxf_is_permalink_structure_query();
		//qtranxf_dbg_echo('$permalink_is_query: ',$permalink_is_query);
		$url_mode = $q_config['url_mode'];
	?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('URL Modification Mode', 'qtranslate') ?></th>
				<td>
					<fieldset><legend class="hidden"><?php _e('URL Modification Mode', 'qtranslate') ?></legend>
						<label title="Query Mode"><input type="radio" name="url_mode" value="<?php echo QTX_URL_QUERY; ?>" <?php checked($url_mode,QTX_URL_QUERY); ?> /> <?php echo __('Use Query Mode (?lang=en)', 'qtranslate').'. '.__('Most SEO unfriendly, not recommended.', 'qtranslate'); ?></label><br/>
					<?php /*
							if($permalink_is_query) {
								echo '<br/>'.PHP_EOL;
								printf(__('No other URL Modification Modes are available if permalink structure is set to "Default" on configuration page %sPermalink Setting%s. It is SEO advantageous to use some other permalink mode, which will enable more URL Modification Modes here as well.', 'qtranslate'),'<a href="'.admin_url('options-permalink.php').'">', '</a>');
								echo PHP_EOL.'<br/><br/>'.PHP_EOL;
							}else{ */ ?>
						<label title="Pre-Path Mode"><input type="radio" name="url_mode" value="<?php echo QTX_URL_PATH; ?>" <?php checked($url_mode,QTX_URL_PATH); disabled($permalink_is_query); ?> /> <?php echo __('Use Pre-Path Mode (Default, puts /en/ in front of URL)', 'qtranslate').'. '.__('SEO friendly.', 'qtranslate'); ?></label><br/>
						<label title="Pre-Domain Mode"><input type="radio" name="url_mode" value="<?php echo QTX_URL_DOMAIN; ?>" <?php checked($url_mode,QTX_URL_DOMAIN); ?> /> <?php echo __('Use Pre-Domain Mode (uses http://en.yoursite.com)', 'qtranslate').'. '.__('You will need to configure DNS sub-domains on your site.', 'qtranslate'); ?></label><br/>
					<?php /*
						<small><?php _e('Pre-Path and Pre-Domain mode will only work with mod_rewrite/pretty permalinks. Additional Configuration is needed for Pre-Domain mode or Per-Domain mode.', 'qtranslate'); ?></small><br/><br/>
							} */
					?>
						<label for="hide_default_language"><input type="checkbox" name="hide_default_language" id="hide_default_language" value="1"<?php checked($q_config['hide_default_language']); ?>/> <?php _e('Hide URL language information for default language.', 'qtranslate'); ?></label><br/>
						<small><?php _e('This is only applicable to Pre-Path and Pre-Domain mode.', 'qtranslate'); ?></small><br/><br/>
					<?php
						//if(!$permalink_is_query) {
							do_action('qtranslate_url_mode_choices',$permalink_is_query);
					?>
						<label title="Per-Domain Mode"><input type="radio" name="url_mode" value="<?php echo QTX_URL_DOMAINS; ?>" <?php checked($url_mode,QTX_URL_DOMAINS); ?> /> <?php echo __('Use Per-Domain mode: specify separate user-defined domain for each language.', 'qtranslate'); ?></label>
					<?php //} ?>
					</fieldset>
				</td>
			</tr>
	<?php
/*
			<tr valign="top">
				<td style="text-align: right"><?php echo __('Hide Default Language', 'qtranslate').':'; ?></td>
				<td>
				</td>
			</tr>
*/
		if($url_mode==QTX_URL_DOMAINS){
			$homeinfo = qtranxf_get_home_info();
			$home_host = $homeinfo['host']; //parse_url(get_option('home'),PHP_URL_HOST);
			foreach($q_config['enabled_languages'] as $lang){
				$id='language_domain_'.$lang;
				$domain = isset($q_config['domains'][$lang]) ? $q_config['domains'][$lang] : $lang.'.'.$home_host;
				echo '<tr><td style="text-align: right">'.__('Domain for', 'qtranslate').' <a href="'.$clean_uri.'&edit='.$lang.'">'.$q_config['language_name'][$lang].'</a>&nbsp;('.$lang.'):</td><td><input type="text" name="'.$id.'" id="'.$id.'" value="'.$domain.'" style="width:100%"/></td></tr>'.PHP_EOL;
			}
		}
	?>
			<tr valign="top">
				<th scope="row"><?php _e('Post Types', 'qtranslate');?></th>
				<td>
					<label for="post_types"><?php _e('Post types enabled for translation:', 'qtranslate'); ?></label><p>
					<?php
						$post_types = get_post_types(); 
						foreach ( $post_types as $post_type ) {
							if(!qtranxf_post_type_optional($post_type)) continue;
							$post_type_off = isset($q_config['post_type_excluded']) && in_array($post_type,$q_config['post_type_excluded']);
					?>
					<span style="margin-right: 12pt"><input type="checkbox" name="post_types[<?php echo $post_type ?>]" id="post_type_<?php echo $post_type ?>" value="1"<?php checked(!$post_type_off); ?> />&nbsp;<?php echo $post_type ?></span>
					<?php
						}
					?>
					</p><p><small><?php _e('If a post type unchecked, no fields in a post of that type are treated as translatable on editing pages. However, the manual raw multilingual entries with language tags may still get translated in a usual way at front-end.', 'qtranslate'); ?></small></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Flag Image Path', 'qtranslate');?></th>
				<td>
					<?php echo trailingslashit(WP_CONTENT_URL); ?><input type="text" name="flag_location" id="flag_location" value="<?php echo $q_config['flag_location']; ?>" style="width:100%"/>
					<br/>
					<small><?php printf(__('Path to the flag images under wp-content, with trailing slash. (Default: %s, clear the value above to reset it to the default)', 'qtranslate'), qtranxf_flag_location_default()); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Ignore Links', 'qtranslate');?></th>
				<td>
					<input type="text" name="ignore_file_types" id="ignore_file_types" value="<?php echo implode(',',array_diff($q_config['ignore_file_types'],explode(',',QTX_IGNORE_FILE_TYPES))); ?>" style="width:100%"/>
					<br/>
					<small><?php printf(__('Don\'t convert links to files of the given file types. (Always included: %s)', 'qtranslate'),implode(', ',explode(',',QTX_IGNORE_FILE_TYPES))); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Head inline CSS', 'qtranslate'); ?></th>
				<td>
					<label for="header_css_on"><input type="checkbox" name="header_css_on" id="header_css_on" value="1"<?php checked($q_config['header_css_on']); ?> />&nbsp;<?php _e('CSS code added by plugin in the head of front-end pages:', 'qtranslate'); ?></label>
					<br />
					<textarea id="header_css" name="header_css" style="width:100%"><?php echo esc_textarea($q_config['header_css']); ?></textarea>
					<br />
					<small><?php echo __('To reset to default, clear the text.', 'qtranslate').' '.__('To disable this inline CSS, clear the check box.', 'qtranslate'); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Cookie Settings', 'qtranslate'); ?></th>
				<td>
					<label for="disable_client_cookies"><input type="checkbox" name="disable_client_cookies" id="disable_client_cookies" value="1"<?php checked($q_config['disable_client_cookies']); disabled( $url_mode==QTX_URL_DOMAIN || $url_mode==QTX_URL_DOMAINS); ?> /> <?php printf(__('Disable language client cookie "%s" (not recommended).', 'qtranslate'),QTX_COOKIE_NAME_FRONT); ?></label>
					<br />
					<small><?php echo sprintf(__('Language cookie is auto-disabled for "%s" "Pre-Domain" and "Per-Domain", as language is always unambiguously defined by a url in those modes.','qtranslate'), __('URL Modification Mode', 'qtranslate')).' '.sprintf(__('Otherwise, use this option with a caution, for simple enough sites only. If checked, the user choice of browsing language will not be saved between sessions and some AJAX calls may deliver unexpected language, as well as some undesired language switching during browsing may occur under certain themes (%sRead More%s).', 'qtranslate'),'<a href="https://qtranslatexteam.wordpress.com/2015/02/26/browser-redirection-based-on-language/" target="_blank">','</a>'); ?></small>
					<br /><br />
					<label for="use_secure_cookie"><input type="checkbox" name="use_secure_cookie" id="use_secure_cookie" value="1"<?php checked($q_config['use_secure_cookie']); ?> /><?php printf(__('Make %s cookies available only through HTTPS connections.', 'qtranslate'),'qTranslate&#8209;X'); ?></label>
					<br />
					<small><?php _e("Don't check this if you don't know what you're doing!", 'qtranslate') ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Update Gettext Databases', 'qtranslate');?></th>
				<td>
					<label for="auto_update_mo"><input type="checkbox" name="auto_update_mo" id="auto_update_mo" value="1"<?php checked($q_config['auto_update_mo']); ?>/> <?php _e('Automatically check for .mo-Database Updates of installed languages.', 'qtranslate'); ?></label>
					<br/>
					<label for="update_mo_now"><input type="checkbox" name="update_mo_now" id="update_mo_now" value="1" /> <?php _e('Update Gettext databases now.', 'qtranslate'); ?></label>
					<br/>
					<small><?php _e('qTranslate will query the Wordpress Localisation Repository every week and download the latest Gettext Databases (.mo Files).', 'qtranslate'); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Date / Time Conversion', 'qtranslate');?></th>
				<td>
					<label><input type="radio" name="use_strftime" value="<?php echo QTX_DATE; ?>" <?php checked($q_config['use_strftime'],QTX_DATE); ?>/> <?php _e('Use emulated date function.', 'qtranslate'); ?></label><br/>
					<label><input type="radio" name="use_strftime" value="<?php echo QTX_DATE_OVERRIDE; ?>" <?php checked($q_config['use_strftime'],QTX_DATE_OVERRIDE); ?>/> <?php _e('Use emulated date function and replace formats with the predefined formats for each language.', 'qtranslate'); ?></label><br/>
					<label><input type="radio" name="use_strftime" value="<?php echo QTX_STRFTIME; ?>" <?php checked($q_config['use_strftime'],QTX_STRFTIME); ?>/> <?php _e('Use strftime instead of date.', 'qtranslate'); ?></label><br/>
					<label><input type="radio" name="use_strftime" value="<?php echo QTX_STRFTIME_OVERRIDE; ?>" <?php checked($q_config['use_strftime'],QTX_STRFTIME_OVERRIDE); ?>/> <?php _e('Use strftime instead of date and replace formats with the predefined formats for each language.', 'qtranslate'); ?></label><br/>
					<small><?php _e('Depending on the mode selected, additional customizations of the theme may be needed.', 'qtranslate'); ?></small>
					<?php /*
					<br/><br/>
					<label><?php _e('If one of the above options "... replace formats with the predefined formats for each language" is in use, then exclude the following formats from being overridden:', 'qtranslate'); ?></label><br/>
					<input type="text" name="ex_date_formats" id="qtranxs_ex_date_formats" value="<?php echo isset($q_config['ex_date_formats']) ? implode(' ',$q_config['ex_date_formats']) : QTX_EX_DATE_FORMATS_DEFAULT; ?>" style="width:100%"><br/>
					*/ ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Translation of options', 'qtranslate'); ?></th>
				<td>
					<label for="filter_options_mode_all"><input type="radio" name="filter_options_mode" id="filter_options_mode_all" value=<?php echo '"'.QTX_FILTER_OPTIONS_ALL.'"'; checked($q_config['filter_options_mode'],QTX_FILTER_OPTIONS_ALL); ?> /> <?php _e('Filter all WordPress options for translation at front-end. It may hurt performance of the site, but ensures that all options are translated.', 'qtranslate'); ?> <?php _e('Starting from version 3.2.5, only options with multilingual content get filtered, which should help on performance issues.', 'qtranslate'); ?></label>
					<br />
					<label for="filter_options_mode_list"><input type="radio" name="filter_options_mode" id="filter_options_mode_list" value=<?php echo '"'.QTX_FILTER_OPTIONS_LIST.'"'; checked($q_config['filter_options_mode'],QTX_FILTER_OPTIONS_LIST); ?> /> <?php _e('Translate only options listed below (for experts only):', 'qtranslate'); ?> </label>
					<br />
					<input type="text" name="filter_options" id="qtranxs_filter_options" value="<?php echo isset($q_config['filter_options']) ? implode(' ',$q_config['filter_options']) : QTX_FILTER_OPTIONS_DEFAULT; ?>" style="width:100%"><br/>
					<small><?php printf(__('By default, all options are filtered to be translated at front-end for the sake of simplicity of configuration. However, for a developed site, this may cause a considerable performance degradation. Normally, there are very few options, which actually need a translation. You may simply list them above to minimize the performance impact, while still getting translations needed. Options names must match the field "%s" of table "%s" of WordPress database. A minimum common set of option, normally needed a translation, is already entered in the list above as a default example. Option names in the list may contain wildcard with symbol "%s".', 'qtranslate'), 'option_name', 'options', '%'); ?></small>
				</td>
			</tr>
			<tr valign="top" id="option_editor_mode">
				<th scope="row"><?php _e('Editor Mode', 'qtranslate'); ?></th>
				<td>
					<label for="qtranxs_editor_mode_lsb"><input type="radio" name="editor_mode" id="qtranxs_editor_mode_lsb" value="<?php echo QTX_EDITOR_MODE_LSB; ?>"<?php checked($q_config['editor_mode'], QTX_EDITOR_MODE_LSB); ?>/>&nbsp;<?php _e('Use Language Switching Buttons (LSB).', 'qtranslate'); ?></label><br/>
					<small><?php echo __('This is the default mode.', 'qtranslate').' '.__('Pages with translatable fields have Language Switching Buttons, which control what language is being edited, while admin language stays the same.', 'qtranslate'); ?></small><br/><br/>
					<label for="qtranxs_editor_mode_raw"><input type="radio" name="editor_mode" id="qtranxs_editor_mode_raw" value="<?php echo QTX_EDITOR_MODE_RAW; ?>"<?php checked($q_config['editor_mode'], QTX_EDITOR_MODE_RAW); ?>/>&nbsp;<?php _e('Editor Raw Mode', 'qtranslate'); ?>. <?php _e('Do not use Language Switching Buttons to edit multi-language text entries.', 'qtranslate'); ?></label><br/>
					<small><?php _e('Some people prefer to edit the raw entries containing all languages together separated by language defining tags, as they are stored in database.', 'qtranslate'); ?></small><br/><br/>
					<label for="qtranxs_editor_mode_single"><input type="radio" name="editor_mode" id="qtranxs_editor_mode_single" value="<?php echo QTX_EDITOR_MODE_SINGLGE; ?>"<?php checked($q_config['editor_mode'], QTX_EDITOR_MODE_SINGLGE); ?>/>&nbsp;<?php echo __('Single Language Mode.', 'qtranslate').' '.__('The language edited is the same as admin language.', 'qtranslate'); ?></label><br/>
					<small><?php echo __('Edit language cannot be switched without page re-loading. Try this mode, if some of the advanced translatable fields do not properly respond to the Language Switching Buttons due to incompatibility with a plugin, which severely alters the default WP behaviour. This mode is the most compatible with other themes and plugins.', 'qtranslate').' '.__('One may find convenient to use the default Editor Mode, while remembering not to switch edit languages on custom advanced translatable fields, where LSB do not work.', 'qtranslate'); ?></small>
				</td>
			</tr>
			<?php
				$options=qtranxf_fetch_file_selection(dirname(__FILE__).'/css/opLSBStyle');
				if($options){
			?>
			<tr valign="top" id="option_lsb_style">
				<th scope="row"><?php _e('LSB Style', 'qtranslate'); ?></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e('LSB Style', 'qtranslate') ?></legend>
						<label><?php printf(__('Choose CSS style for how Language Switching Buttons are rendered:', 'qtranslate')); ?></label>
						<br/><?php printf(__('LSB %s-wrap classes:', 'qtranslate'), 'ul'); ?>&nbsp;<input type="text" name="lsb_style_wrap_class" id="lsb_style_wrap_class" value="<?php echo $q_config['lsb_style_wrap_class']; ?>" size="50" >
						<br/><?php _e('Active button class:', 'qtranslate'); ?>&nbsp;<input type="text" name="lsb_style_active_class" id="lsb_style_active_class" value="<?php echo $q_config['lsb_style_active_class']; ?>" size="40" >
						<br/><small><?php _e('The above is reset to an appropriate default, if the below is changed.', 'qtranslate'); ?></small>
						<br/><?php _e('CSS set:', 'qtranslate'); ?>&nbsp;<select name="lsb_style" id="lsb_style"><?php
							foreach($options as $nm => $val){
								echo '<option value="'.$val.'"'.selected($val,$q_config['lsb_style']).'>'.$nm.'</option>';
							}
							echo '<option value="custom"'.selected('custom',$q_config['lsb_style']).'>'.__('Use custom CSS', 'qtranslate').'</option>';
						?></select>
						<br/><small><?php printf(__('Choice "%s" disables this option and allows one to use its own custom CSS provided by other means.', 'qtranslate'),__('Use custom CSS', 'qtranslate')) ?></small>
					</fieldset>
				</td>
			</tr>
			<?php
				}
			?>
			<tr valign="top" id="option_highlight_mode">
				<?php
				$highlight_mode = $q_config['highlight_mode'];
				// reset default custom CSS when the field is empty, or when the "custom" option is not checked
				if(empty($q_config['highlight_mode_custom_css']) || $highlight_mode != QTX_HIGHLIGHT_MODE_CUSTOM_CSS) {
					$highlight_mode_custom_css = qtranxf_get_admin_highlight_css($highlight_mode);
				} else {
					$highlight_mode_custom_css = $q_config['highlight_mode_custom_css'];
				}
				?>
				<th scope="row"><?php _e('Highlight Style', 'qtranslate'); ?></th>
				<td>
					<p><?php _e('When there are many integrated or customized translatable fields, it may become confusing to know which field has multilingual value. The highlighting of translatable fields may come handy then:', 'qtranslate'); ?></p>
					<fieldset>
						<legend class="hidden"><?php _e('Highlight Style', 'qtranslate') ?></legend>
						<label title="<?php _e('Do not highlight the translatable fields.', 'qtranslate') ?>">
							<input type="radio" name="highlight_mode" value="<?php echo QTX_HIGHLIGHT_MODE_NONE; ?>" <?php checked($highlight_mode, QTX_HIGHLIGHT_MODE_NONE); ?> />
							<?php _e('Do not highlight the translatable fields.', 'qtranslate') ?>
						</label><br/>
						<label title="<?php _e('Show a line on the left border of translatable fields.', 'qtranslate') ?>">
							<input type="radio" name="highlight_mode" value="<?php echo QTX_HIGHLIGHT_MODE_LEFT_BORDER; ?>" <?php checked($highlight_mode, QTX_HIGHLIGHT_MODE_LEFT_BORDER); ?> />
							<?php _e('Show a line on the left border of translatable fields.', 'qtranslate') ?>
						</label><br/>
						<label title="<?php _e('Draw a border around translatable fields.', 'qtranslate') ?>">
							<input type="radio" name="highlight_mode" value="<?php echo QTX_HIGHLIGHT_MODE_BORDER; ?>" <?php checked($highlight_mode, QTX_HIGHLIGHT_MODE_BORDER); ?> />
							<?php _e('Draw a border around translatable fields.', 'qtranslate') ?>
						</label><br/>
						<label title="<?php _e('Use custom CSS', 'qtranslate') ?>">
							<input type="radio" name="highlight_mode" value="<?php echo QTX_HIGHLIGHT_MODE_CUSTOM_CSS; ?>" <?php checked($highlight_mode, QTX_HIGHLIGHT_MODE_CUSTOM_CSS); ?>/>
							<?php echo __('Use custom CSS', 'qtranslate').':' ?>
						</label><br/>
					</fieldset><br />
					<textarea id="highlight_mode_custom_css" name="highlight_mode_custom_css" style="width:100%"><?php echo esc_textarea($highlight_mode_custom_css); ?></textarea>
					<br />
					<small><?php echo __('To reset to default, clear the text.', 'qtranslate').' '; printf(__('The color in use is taken from your profile option %s, the third color.', 'qtranslate'), '"<a href="'.admin_url('/profile.php').'">'.qtranxf_translate_wp('Admin Color Scheme').'</a>"') ?></small>
				</td>
			</tr>
<?php /*
			<tr>
				<th scope="row"><?php _e('Debugging Information', 'qtranslate');?></th>
				<td>
					<p><?php printf(__('If you encounter any problems and you are unable to solve them yourself, you can visit the <a href="%s">Support Forum</a>. Posting the following Content will help other detect any misconfigurations.', 'qtranslate'), 'https://wordpress.org/support/plugin/qtranslate-x'); ?></p>
					<textarea readonly="readonly" id="qtranxs_debug"><?php
						$q_config_copy = $q_config;
						// remove information to keep data anonymous and other not needed things
						unset($q_config_copy['url_info']);
						unset($q_config_copy['js']);
						unset($q_config_copy['windows_locale']);
						//unset($q_config_copy['pre_domain']);
						unset($q_config_copy['term_name']);
						echo htmlspecialchars(print_r($q_config_copy, true));
					?></textarea>
				</td>
			</tr>
*/ ?>
		</table>
	<?php qtranxf_admin_section_end('advanced'); ?>
	<?php qtranxf_admin_section_start(__('Custom Integration', 'qtranslate'),'integration'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php echo __('Custom Fields', 'qtranslate');?></th>
				<td>
					<?php printf(__('Enter "%s" or "%s" attribute of text fields from your theme, which you wish to translate. This applies to post, page and media editors (%s). To lookup "%s" or "%s", right-click on the field in the post or the page editor and choose "%s". Look for an attribute of the field named "%s" or "%s". Enter it below, as many as you need, space- or comma-separated. After saving configuration, these fields will start responding to the language switching buttons, and you can enter different text for each language. The input fields of type %s will be parsed using %s syntax, while single line text fields will use %s syntax. If you need to override this behaviour, prepend prefix %s or %s to the name of the field to specify which syntax to use. For more information, read %sFAQ%s.', 'qtranslate'),'id','class','/wp-admin/post*','id','class',_x('Inspect Element','browser option','qtranslate'),'id','class','\'textarea\'',esc_html('<!--:-->'),'[:]','\'<\'','\'[\'','<a href="https://wordpress.org/plugins/qtranslate-x/faq/">','</a>'); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="text-align: right">id</th>
				<td>
					<input type="text" name="custom_fields" id="qtranxs_custom_fields" value="<?php echo implode(' ',$q_config['custom_fields']); ?>" style="width:100%"><br/>
					<small><?php _e('The value of "id" attribute is normally unique within one page, otherwise the first field found, having an id specified, is picked up.', 'qtranslate'); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="text-align: right">class</th>
				<td>
					<input type="text" name="custom_field_classes" id="qtranxs_custom_field_classes" value="<?php echo implode(' ',$q_config['custom_field_classes']); ?>" style="width:100%"><br/>
					<small><?php printf(__('All the fields of specified classes will respond to Language Switching Buttons. Be careful not to include a class, which would affect language-neutral fields. If you cannot uniquely identify a field needed neither by %s, nor by %s attribute, report the issue on %sSupport Forum%s', 'qtranslate'),'"id"', '"class"', '<a href="https://wordpress.org/support/plugin/qtranslate-x">','</a>'); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo __('Custom Filters', 'qtranslate');?></th>
				<td>
					<input type="text" name="text_field_filters" id="qtranxs_text_field_filters" value="<?php echo implode(' ',$q_config['text_field_filters']); ?>" style="width:100%"><br/>
					<small><?php printf(__('Names of filters (which are enabled on theme or other plugins via %s function) to add translation to. For more information, read %sFAQ%s.', 'qtranslate'),'apply_filters()','<a href="https://wordpress.org/plugins/qtranslate-x/faq/">','</a>'); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo __('Custom Admin Pages', 'qtranslate');?></th>
				<td><input type="text" name="custom_pages" id="qtranxs_custom_pages" value="<?php echo implode(' ',$q_config['custom_pages']); ?>" style="width:100%"><br/>
					<small><?php printf(__('List the custom admin page paths for which you wish Language Switching Buttons to show up. The Buttons will then control fields configured in "Custom Fields" section. You may only include part of the full URL after %s, including a distinctive query string if needed. As many as desired pages can be listed space/comma separated. For more information, read %sFAQ%s.', 'qtranslate'),'/wp-admin/','<a href="https://wordpress.org/plugins/qtranslate-x/faq/">','</a>'); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Compatibility Functions', 'qtranslate');?></th>
				<td>
					<label for="qtranxs_qtrans_compatibility"><input type="checkbox" name="qtrans_compatibility" id="qtranxs_qtrans_compatibility" value="1"<?php checked($q_config['qtrans_compatibility']); ?>/>&nbsp;<?php printf(__('Enable function name compatibility (%s).', 'qtranslate'), 'qtrans_convertURL, qtrans_generateLanguageSelectCode, qtrans_getLanguage, qtrans_getLanguageName, qtrans_getSortedLanguages, qtrans_join, qtrans_split, qtrans_use, qtrans_useCurrentLanguageIfNotFoundShowAvailable, qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage, qtrans_useDefaultLanguage, qtrans_useTermLib'); ?></label><br/>
					<small><?php printf(__('Some plugins and themes use direct calls to the functions listed, which are defined in former %s plugin and some of its forks. Turning this flag on will enable those function to exists, which will make the dependent plugins and themes to work. WordPress policy prohibits to define functions with the same names as in other plugins, since it generates user-unfriendly fatal errors, when two conflicting plugins are activated simultaneously. Before turning this option on, you have to make sure that there are no other plugins active, which define those functions.', 'qtranslate'), '<a href="https://wordpress.org/plugins/qtranslate/" target="_blank">qTranslate</a>'); ?></small>
				</td>
			</tr>
			<?php /* if ( defined( 'WPB_VC_VERSION' ) ) { ?>
			<tr valign="top">
				<th scope="row"><?php _e('3rd-party plugins', 'qtranslate') ?></th>
				<td><?php _e('Below is a list of plugins which have a way to auto-integrate with qTranslate-X.', 'qtranslate') ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="text-align: right"><a href="http://vc.wpbakery.com/" target="_blank">WPBakery Visual Composer</a></th>
				<td>
					<label for="plugin_js_composer"><input type="checkbox" name="plugin_js_composer" id="plugin_js_composer" value="1"<?php checked(!(isset($q_config['plugin_js_composer_off']) && $q_config['plugin_js_composer_off'])); ?>/>&nbsp;<?php printf(__('Enable built-in integration for "%s".', 'qtranslate'), 'WPBakery Visual Composer'); echo ' '.__('Experimental feature.', 'qtranslate'); ?></label>
					<p><small><?php printf(__('If %s implements its own integration, this will need to be turned off.', 'qtranslate'), 'WPBakery'); ?></small></p>
				</td>
			</tr>
			<?php } */ ?>
		</table>
	<?php qtranxf_admin_section_end('integration'); ?>
<?php do_action('qtranslate_configuration', $clean_uri); ?>
	</form>

</div>
<div class="wrap">

<?php qtranxf_admin_section_start(__('Languages', 'qtranslate'),'languages'); //id="qtranslate-admin-languages" ?>
<div id="col-container">

<div id="col-right">
<div class="col-wrap">
<h3><?php _e('List of Configured Languages','qtranslate'); ?></h3>
<p><small><?php
	$language_names = qtranxf_language_configured('language_name');
	$flags = qtranxf_language_configured('flag');
	//$windows_locales = qtranxf_language_configured('windows_locale');
	printf(__('Only enabled languages are loaded at front-end, while all %d configured languages are listed here.','qtranslate'),count($language_names));
	echo ' '; _e('The table below contains both pre-defined and manually added or modified languages.','qtranslate');
	echo ' '; printf(__('You may %s or %s a language, or %s manually added language, or %s previous modifications of a pre-defined language.', 'qtranslate'), '"'.__('Enable', 'qtranslate').'"', '"'.__('Disable', 'qtranslate').'"', '"'.__('Delete', 'qtranslate').'"', '"'.__('Reset', 'qtranslate').'"');
	echo ' '; printf(__('Click %s to modify language properties.', 'qtranslate'), '"'.__('Edit', 'qtranslate').'"');
?></small></p>
<table class="widefat">
	<thead>
	<tr>
<?php print_column_headers('language'); ?>
	</tr>
	</thead>

	<tfoot>
	<tr>
<?php print_column_headers('language', false); ?>
	</tr>
	</tfoot>

	<tbody id="the-list" class="qtranxs-language-list" class="list:cat">
<?php
	$languages_stored = get_option('qtranslate_language_names',array());
	$languages_predef = qtranxf_default_language_name();
	$flag_location_url = qtranxf_flag_location();
	$flag_location_dir = trailingslashit(WP_CONTENT_DIR).$q_config['flag_location'];
	$flag_location_dir_def = dirname(QTRANSLATE_FILE).'/flags/';
	$flag_location_url_def = trailingslashit(WP_CONTENT_URL).'/plugins/'.basename(dirname(QTRANSLATE_FILE)).'/flags/';
	foreach($language_names as $lang => $language){ if($lang=='code') continue;
		$flag = $flags[$lang];
		if(file_exists($flag_location_dir.$flag)){
			$flag_url = $flag_location_url.$flag;
		}else{
			$flag_url = $flag_location_url_def.$flag;
		}
?>
	<tr>
		<td><?php echo $lang; ?></td>
		<td><img src="<?php echo $flag_url; ?>" alt="<?php echo sprintf(__('%s Flag', 'qtranslate'), $language) ?>"></td>
		<td><?php echo $language; ?></td>
		<td><?php if(in_array($lang,$q_config['enabled_languages'])) { if($q_config['default_language']==$lang){ _e('Default', 'qtranslate'); } else{ ?><a class="edit" href="<?php echo $clean_uri; ?>&disable=<?php echo $lang; ?>"><?php _e('Disable', 'qtranslate'); ?></a><?php } } else { ?><a class="edit" href="<?php echo $clean_uri; ?>&enable=<?php echo $lang; ?>"><?php _e('Enable', 'qtranslate'); ?></a><?php } ?></td>
		<td><a class="edit" href="<?php echo $clean_uri; ?>&edit=<?php echo $lang; ?>"><?php _e('Edit', 'qtranslate'); ?></a></td>
		<td><?php if(!isset($languages_stored[$lang])){ _e('Pre-Defined', 'qtranslate'); } else { ?><a class="delete" href="<?php echo $clean_uri; ?>&delete=<?php echo $lang; ?>"><?php if(isset($languages_predef[$lang])) _e('Reset', 'qtranslate'); else _e('Delete', 'qtranslate'); ?></a><?php } ?></td>
	</tr>
<?php }
/*
<td><?php if($q_config['default_language']==$lang){ _e('Default', 'qtranslate'); } else { ?><a class="delete" href="<?php echo $clean_uri; ?>&delete=<?php echo $lang; ?>"><?php _e('Delete', 'qtranslate'); ?></a><?php } ?></td>
*/
?>
	</tbody>
</table>
<p><?php _e('Enabling a language will cause qTranslate to update the Gettext-Database for the language, which can take a while depending on your server\'s connection speed.', 'qtranslate');?></p>
</div>
</div><!-- /col-right -->

<div id="col-left">
<div class="col-wrap">
<div class="form-wrap">
<h3><?php _e('Add Language', 'qtranslate'); ?></h3>
<form name="addcat" id="addcat" method="post" class="add:the-list: validate">
<?php qtranxf_language_form($language_code, $language_code, $language_name, $language_locale, $language_date_format, $language_time_format, $language_flag, $language_na_message, $language_default); ?>
<p class="submit"><input type="submit" name="submit" value="<?php _e('Add Language &raquo;', 'qtranslate'); ?>" /></p>
</form></div>
</div>
</div><!-- /col-left -->

</div><!-- /col-container -->
</div><!-- /qtranslate-admin-languages in qtranxf_admin_section_start -->
<script type="text/javascript">
//<![CDATA[
	qtranxj_readShowHideCookie('qtranslate-admin-languages');
// ]]>
</script>
</div><!-- /wrap -->
<?php
}
}

/* Add a metabox in admin menu page */
function qtranxf_nav_menu_metabox( $object )
{
	global $nav_menu_selected_id; 
	$nm = __('Language Menu', 'qtranslate');
	$elems = array( '#qtransLangSwLM#' => $nm );

	class qtranxcLangSwItems {
		public $db_id = 0;
		public $object = 'qtranslangsw';
		public $object_id;
		public $menu_item_parent = 0;
		public $type = 'custom';
		public $title;// = 'Language';
		public $url;
		public $target = '';
		public $attr_title = '';
		public $classes = array();
		public $xfn = '';
	}

	$elems_obj = array();
	foreach ( $elems as $value => $title ) {
		$elems_obj[$title] = new qtranxcLangSwItems();
		$obj = &$elems_obj[$title];
		$obj->object_id = esc_attr( $value );
		if(empty($obj->title)) $obj->title = esc_attr( $title );
		$obj->label = esc_attr( $title );
		$obj->url = esc_attr( $value );
	}

	$walker = new Walker_Nav_Menu_Checklist();
/* Language menu items - not used anymore
.qtranxs-lang-menu
{
	//background-position: top left;
	background-position-y: 8px;
	padding-left: 22px;
}

.qtranxs-lang-menu-item
{
	background-position: center left;
	//background-position-x: 5px;
	//background-position-y:50%;
	padding-left: 22px;
}
*/
?>
<div id="qtranxs-langsw" class="qtranxslangswdiv">
	<div id="tabs-panel-qtranxs-langsw-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
		<ul id="qtranxs-langswchecklist" class="list:qtranxs-langsw categorychecklist form-no-clear">
			<?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $elems_obj ), 0, (object)array( 'walker' => $walker ) ); ?>
		</ul>
	</div>
	<span class="list-controls hide-if-no-js">
		<a href="javascript:void(0);" class="help" onclick="jQuery( '#help-login-links' ).toggle();"><?php _e( 'Help', 'qtranslate'); ?></a>
		<span class="hide-if-js" id="help-login-links"><p><a name="help-login-links"></a>
		<?php 
		echo __('Menu item added is replaced with a drop-down menu of available languages, when menu is rendered.', 'qtranslate');
		echo ' ';
		printf(__('The rendered menu items have CSS classes %s and %s ("%s" is a language code), which can be defined in theme style, if desired. The label of language menu can also be customized via field "%s" in the menu configuration.', 'qtranslate'), '.qtranxs-lang-menu, .qtranxs-lang-menu-xx, .qtranxs-lang-menu-item', '.qtranxs-lang-menu-item-xx', 'xx', qtranxf_translate_wp('Navigation Label'));
		echo ' ';
		printf(__('The field "%s" of inserted menu item allows additional configuration described in %sFAQ%s.', 'qtranslate'), qtranxf_translate_wp('URL'), '<a href="https://qtranslatexteam.wordpress.com/faq/#LanguageSwitcherMenuConfig" target="blank">','</a>'); //https://wordpress.org/plugins/qtranslate-x/faq ?></p>
		</span>
	</span>
	<p class="button-controls">
		<span class="add-to-menu">
			<input type="submit"<?php disabled( $nav_menu_selected_id, 0 ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu', 'qtranslate'); ?>" name="add-qtranxs-langsw-menu-item" id="submit-qtranxs-langsw" />
			<span class="spinner"></span>
		</span>
	</p>
</div>
<?php
}

function qtranxf_add_nav_menu_metabox()
{
	add_meta_box( 'add-qtranxs-language-switcher', __( 'Language Switcher', 'qtranslate'), 'qtranxf_nav_menu_metabox', 'nav-menus', 'side', 'default' );
}

function qtranxf_add_language_menu( $wp_admin_bar )
{
	global $q_config;
	if ( !is_admin() || !is_admin_bar_showing() )
		return;

	if(wp_is_mobile()){
		$title = '';
	}else{
		$title = $q_config['language_name'][$q_config['language']];
	}
	
	$wp_admin_bar->add_menu( array(
			'id'   => 'language',
			'parent' => 'top-secondary',
			//'href' => 'http://example.com',
			//'meta' => array('class'),
			'title' => $title
		)
	);

	foreach($q_config['enabled_languages'] as $language)
	{
		$wp_admin_bar->add_menu( 
			array
			(
				'id'	 => $language,
				'parent' => 'language',
				'title'  => $q_config['language_name'][$language],
				'href'   => add_query_arg('lang', $language)
			)
		);
	}
}

function qtranxf_links($links, $file){ // copied from Sociable Plugin
	//Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if (!$this_plugin){
		$this_plugin = plugin_basename(QTRANSLATE_FILE);
	}
	if ($file == $this_plugin){
		$settings_link = '<a href="options-general.php?page=qtranslate-x">' . __('Settings', 'qtranslate') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}
add_filter('plugin_action_links', 'qtranxf_links', 10, 2);

add_action('admin_head-nav-menus.php', 'qtranxf_add_nav_menu_metabox');
add_action('admin_menu', 'qtranxf_adminMenu');
add_action('admin_bar_menu', 'qtranxf_add_language_menu', 999);
add_action('wp_before_admin_bar_render', 'qtranxf_fixAdminBar');
