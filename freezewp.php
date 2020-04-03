<?php
/*
Plugin Name: FreezeWP
Plugin URI: https://github.com/Asif2BD/FreezeWP
Description: FreezeWP is a simple plugin that temporarily puts your WordPress site into a "read only" state. It's most useful when you are migrating a site. or just want to prevent changes.
Author: M Asif Rahman
Author URI: https://asif.im
Text Domain: freezewp
Version: 0.1.0
License: GPLv3
*/

/**
 * LICENSE
 * This file is part of FreezeWP.
 *
 * FreezeWP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @package    freezewp
 * @author     M Asif Rahman <asif2bd@gmail.com>
 * @copyright  Copyright 2020 M Asif Rahman
 * @license    http://www.gnu.org/licenses/gpl.txt GPL 3.0
 * @link       https://asif.im
 */

if ( ! function_exists( 'fwp_custom_login_message' ) ) {
	add_filter( 'login_message' , 'fwp_custom_login_message' );
	load_plugin_textdomain( 'freezewp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	/**
	 * Insert text onto login page
	 *
	 * @return  string Text to insert onto login page
	 */
	function fwp_custom_login_message() {
		$message = '<p class="message" style="padding:10px;border: 2px solid red; margin-bottom: 10px;"><span style="color:red;font-weight:bold; text-align:center;display:block">'.__('FREEZEWP NOTICE', 'freezewp' ).':</span><br/>'.__('FreezeWP is active. This site is currently in Code Freeze Mode. To avoid lost work, please do not make any changes to the site until this message is removed.', 'freezewp' ).'</p>';
		return $message;
	}
}

if ( ! function_exists( 'fwp_effective_notice' ) ) {
	add_action( 'admin_notices', 'fwp_effective_notice' );

	/**
	 * Show notice on site pages when site disabled
	 *
	 * @return  void
	 */
	function fwp_effective_notice() {
		echo '<div class="error"><p><strong>'.__('Warning: FreezeWP is in effect.', 'freezewp').'</strong> '.__('All changes made during this period will be lost.', 'freezewp' ).'</p></div>';
	}
}

if ( ! function_exists( 'fwp_admin_init' ) ) {
	add_action( 'admin_init', 'fwp_admin_init' );
	add_action( 'admin_print_scripts', 'fwp_load_admin_head' );
	add_action( 'plugins_loaded', 'fwp_close_comments' );
	add_action( 'admin_head' , 'fwp_remove_media_buttons' );
	add_filter( 'tiny_mce_before_init', 'fwp_visedit_readonly',10 ,1 );
	add_filter( 'post_row_actions', 'fwp_remove_row_actions', 10, 1 );
	add_filter( 'page_row_actions', 'fwp_remove_row_actions', 10, 1 );
	add_filter( 'user_row_actions', 'fwp_remove_row_actions', 10, 1 );
	add_filter( 'tag_row_actions', 'fwp_remove_row_actions', 10, 1 );
	add_filter( 'media_row_actions', 'fwp_remove_row_actions', 10, 1 );
	add_filter( 'plugin_install_action_links', 'fwp_remove_row_actions', 10, 1 );
	add_filter( 'theme_install_action_links', 'fwp_remove_row_actions', 10, 1 );
	add_filter('plugin_action_links', 'fwp_plugin_action_links', 10, 2);

	/*Prevent Automatic Background Updates */
	add_filter( 'auto_update_core', '__return_false' );
	add_filter( 'auto_update_plugin', '__return_false' );
	add_filter( 'auto_update_translation', '__return_false' );
	add_filter( 'auto_update_theme', '__return_false' );

	/**
	 * Register javascript, disable quickpress widget, remove add/edit menu items
	 *
	 * @return  void
	 */
	function fwp_admin_init() {
		// register js
		wp_register_script( 'freezewp-js', plugins_url('/js/freeze.js', __FILE__), false, '0.1.0', true );

		// make localizable
		load_plugin_textdomain( 'freezewp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// remove QuickPress widget
		remove_meta_box('dashboard_quick_press', 'dashboard', 'normal');

		// remove menu items - doesn't work for all of them in admin_menu
		fwp_modify_menu();
	}

	/**
	 * Load javascript on all admin pages
	 *
	 * @return  void
	 */
	function fwp_load_admin_head() {
		wp_enqueue_script( 'freezewp-js' );
		wp_localize_script( 'freezewp-js', 'fwp', array(
			'wp_version' => get_bloginfo( 'version' )
		) );
	}

	/**
	 * Close comments and trackbacks while activated
	 *
	 * @return  void
	 */
	function fwp_close_comments() {
		add_filter( 'the_posts', 'fwp_set_comment_status' );
		add_filter( 'comments_open', 'fwp_close_the_comments', 10, 2 );
		add_filter( 'pings_open', 'fwp_close_the_comments', 10, 2 );

		/**
		 * Close comments and trackbacks while activated
		 *
		 * @return  array Array of posts with comments closed
		 */
		function fwp_set_comment_status ( $posts ) {
			if ( ! empty( $posts ) && is_singular() ) {
				$posts[0]->comment_status = 'closed';
				$posts[0]->post_status = 'closed';
			}
			return $posts;
		}

		/**
		 * Close comments and trackbacks while activated
		 *
		 * @return  $open
		 */
		function fwp_close_the_comments ( $open, $post_id ) {
			// if not open, than back
			if ( ! $open )
				return $open;
				$post = get_post( $post_id );
			if ( $post -> post_type ) // all post types
				return FALSE;
			return $open;
		}
	}
	/**
	 * Remove media upload button(s)
	 *
	 * @return  void
	 */
	function fwp_remove_media_buttons() {
		remove_action( 'media_buttons', 'media_buttons' );
	}

	/**
	 * Set visual editor as "read only"
	 *
	 * @return  array Array of arguments to send to editor
	 */
	function fwp_visedit_readonly( $args ) {
		// suppress php warning in core when editor is read only
		$args['readonly'] = 1;
		return $args;
	}

	/**
	 * Remove invalid action links
	 *
	 * @return  array Modified array of action links
	 */
	function fwp_remove_row_actions($actions) {
		unset( $actions['trash'] );
		unset( $actions['delete'] );

		// no normal filter action for this (install plugin row)
		foreach ($actions as $k => $v) {
			if (strpos($v, 'class="install-now') ) {
				unset ($actions[$k]);
			}
		}

		return $actions;
	}

	/**
	 * Remove add/edit menu items
	 *
	 * @return  void
	 */
	function fwp_modify_menu() {
		global $submenu;
		unset($submenu['edit.php?post_type=page'][10]); // Page > Add New
		remove_submenu_page('edit.php', 'post-new.php');
		remove_submenu_page('sites.php', 'site-new.php');
		remove_submenu_page('upload.php', 'media-new.php');
		remove_submenu_page('link-manager.php', 'link-add.php');
		remove_submenu_page('themes.php', 'theme-editor.php');
		remove_submenu_page('themes.php', 'customize.php');
		remove_submenu_page('themes.php', 'theme-install.php');
		remove_submenu_page('plugins.php', 'plugin-editor.php');
		remove_submenu_page('plugins.php', 'plugin-install.php');
		remove_submenu_page('users.php', 'user-new.php');
		remove_submenu_page('tools.php', 'import.php');
		remove_submenu_page('update-core.php', 'upgrade.php');
	}

	/**
	 * Remove Activation/Deactivation/Edit links for all plugins but this one
	 *
	 * @return  array Modified array of action links for plugins
	 */
	function fwp_plugin_action_links($links, $file) {
		$this_plugin = plugin_basename(__FILE__);

		unset($links['edit']);

		if ($file !== $this_plugin) {
			return array(); // prevents PHP warning from any plugins that have modified the action links
		}
		return $links;
	}

	/**
	 * Remove topic replies and new topics from bbPress
	 *
	 * @note	props to theZedt
	 * @return  void
	 */
	if ( class_exists( 'bbPress' ) ) {
		add_filter( 'bbp_current_user_can_access_create_reply_form', fwp_close_bbp_comments );
		add_filter( 'bbp_current_user_can_access_create_topic_form', fwp_close_bbp_comments );

		function fwp_close_bbp_comments() {
			return false;
		}
	}
}

/*--------------------------------------------------------- */
/* !Activation/Deactivation  */
/*--------------------------------------------------------- */

register_activation_hook( __FILE__, 'fwp_activation_action');
function fwp_activation_action() {
	add_option( 'fwp-backup-users_can_register', get_option( 'users_can_register'), false, true );
	update_option( 'users_can_register', 0 );
}

register_deactivation_hook( __FILE__, 'fwp_desactivation_action');
function fwp_desactivation_action() {
	update_option( 'users_can_register', get_option( 'fwp-backup-users_can_register') );
	delete_option( 'fwp-backup-users_can_register' );
}
