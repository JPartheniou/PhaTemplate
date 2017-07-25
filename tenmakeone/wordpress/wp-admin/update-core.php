<?php
/**
 * Update Core administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

wp_enqueue_style( 'plugin-install' );
wp_enqueue_script( 'plugin-install' );
wp_enqueue_script( 'updates' );
add_thickbox();

if ( is_multisite() && ! is_network_admin() ) {
	wp_redirect( network_admin_url( 'update-core.php' ) );
	exit();
}

if ( ! current_user_can( 'update_core' ) && ! current_user_can( 'update_themes' ) && ! current_user_can( 'update_plugins' ) )
	wp_die( __( 'You do not have sufficient permissions to update this site.' ) );

function list_core_update( $update ) {
 	global $wp_local_package, $wpdb, $wp_version;
  	static $first_pass = true;

 	if ( 'en_US' == $update->locale && 'en_US' == get_locale() )
 		$version_string = $update->current;
 	// If the only available update is a partial builds, it doesn't need a language-specific version string.
 	elseif ( 'en_US' == $update->locale && $update->packages->partial && $wp_version == $update->partial_version && ( $updates = get_core_updates() ) && 1 == count( $updates ) )
 		$version_string = $update->current;
 	else
 		$version_string = sprintf( "%s&ndash;<strong>%s</strong>", $update->current, $update->locale );

	$current = false;
	if ( !isset($update->response) || 'latest' == $update->response )
		$current = true;
	$submit = __('Update Now');
	$form_action = 'update-core.php?action=do-core-upgrade';
	$php_version    = phpversion();
	$mysql_version  = $wpdb->db_version();
	$show_buttons = true;
	if ( 'development' == $update->response ) {
		$message = __('You are using a development version of WordPress. You can update to the latest nightly build automatically or download the nightly build and install it manually:');
		$download = __('Download nightly build');
	} else {
		if ( $current ) {
			$message = sprintf( __( 'If you need to re-install version %s, you can do so here or download the package and re-install manually:' ), $version_string );
			$submit = __('Re-install Now');
			$form_action = 'update-core.php?action=do-core-reinstall';
		} else {
			$php_compat     = version_compare( $php_version, $update->php_version, '>=' );
			if ( file_exists( WP_CONTENT_DIR . '/db.php' ) && empty( $wpdb->is_mysql ) )
				$mysql_compat = true;
			else
				$mysql_compat = version_compare( $mysql_version, $update->mysql_version, '>=' );

			if ( !$mysql_compat && !$php_compat )
				$message = sprintf( __('You cannot update because <a href="https://codex.wordpress.org/Version_%1$s">WordPress %1$s</a> requires PHP version %2$s or higher and MySQL version %3$s or higher. You are running PHP version %4$s and MySQL version %5$s.'), $update->current, $update->php_version, $update->mysql_version, $php_version, $mysql_version );
			elseif ( !$php_compat )
				$message = sprintf( __('You cannot update because <a href="https://codex.wordpress.org/Version_%1$s">WordPress %1$s</a> requires PHP version %2$s or higher. You are running version %3$s.'), $update->current, $update->php_version, $php_version );
			elseif ( !$mysql_compat )
				$message = sprintf( __('You cannot update because <a href="https://codex.wordpress.org/Version_%1$s">WordPress %1$s</a> requires MySQL version %2$s or higher. You are running version %3$s.'), $update->current, $update->mysql_version, $mysql_version );
			else
				$message = 	sprintf(__('You can update to <a href="https://codex.wordpress.org/Version_%1$s">WordPress %2$s</a> automatically or download the package and install it manually:'), $update->current, $version_string);
			if ( !$mysql_compat || !$php_compat )
				$show_buttons = false;
		}
		$download = sprintf(__('Download %s'), $version_string);
	}

	echo '<p>';
	echo $message;
	echo '</p>';
	echo '<form method="post" action="' . $form_action . '" name="upgrade" class="upgrade">';
	wp_nonce_field('upgrade-core');
	echo '<p>';
	echo '<input name="version" value="'. esc_attr($update->current) .'" type="hidden"/>';
	echo '<input name="locale" value="'. esc_attr($update->locale) .'" type="hidden"/>';
	if ( $show_buttons ) {
		if ( $first_pass ) {
			submit_button( $submit, $current ? 'button' : 'primary regular', 'upgrade', false );
			$first_pass = false;
		} else {
			submit_button( $submit, 'button', 'upgrade', false );
		}
		echo '&nbsp;<a href="' . esc_url( $update->download ) . '" class="button">' . $download . '</a>&nbsp;';
	}
	if ( 'en_US' != $update->locale )
		if ( !isset( $update->dismissed ) || !$update->dismissed )
			submit_button( __('Hide this update'), 'button', 'dismiss', false );
		else
			submit_button( __('Bring back this update'), 'button', 'undismiss', false );
	echo '</p>';
	if ( 'en_US' != $update->locale && ( !isset($wp_local_package) || $wp_local_package != $update->locale ) )
	    echo '<p class="hint">'.__('This localized version contains both the translation and various other localization fixes. You can skip upgrading if you want to keep your current translation.').'</p>';
	// Partial builds don't need language-specific warnings.
	elseif ( 'en_US' == $update->locale && get_locale() != 'en_US' && ( ! $update->packages->partial && $wp_version == $update->partial_version ) ) {
	    echo '<p class="hint">'.sprintf( __('You are about to install WordPress %s <strong>in English (US).</strong> There is a chance this update will break your translation. You may prefer to wait for the localized version to be released.'), $update->response != 'development' ? $update->current : '' ).'</p>';
	}
	echo 