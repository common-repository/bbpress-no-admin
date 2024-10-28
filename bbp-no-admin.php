<?php

/**
 * bbPress - No Admin
 *
 * Limit new bbPress content within wp-admin to super-admins
 *
 * @package bbPressNoAdmin
 * @subpackage Main
 */

/**
 * Plugin Name: bbPress - No Admin
 * Plugin URI:  http://wordpress.org/extend/plugins/bbpress-no-admin/
 * Description: Limit new bbPress content within wp-admin to super-admins
 * Version:     1.2.0
 * Author:      johnjamesjacoby
 * Author URI:  http://johnjamesjacoby.com
 * Tags:        bbpress, admin, forums, topics, replies, create, edit, spam
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class BBP_No_Admin {

	/**
	 * Add action to bbp_init
	 *
	 * @since 1.0
	 * @uses add_action()
	 */
	public function __construct() {
		add_action( 'bbp_init', array( $this, 'setup_hooks' ), 0 );
	}

	/**
	 * Prevent posting bbPress forums/topics/replies
	 *
	 * @since 1.0
	 * @uses is_super_admin()
	 * @uses remove_action()
	 * @uses add_filter()
	 */
	function setup_hooks() {

		// Only take action in admin if not super-admin
		if ( is_multisite() && is_super_admin() ) {
			return;
		} elseif ( ! is_multisite() && current_user_can( 'manage_options' ) ) {
			return;
		}

		// Remove menu order and separator hooks
		remove_action( 'admin_menu',        'bbp_admin_separator'         );
		remove_action( 'custom_menu_order', 'bbp_admin_custom_menu_order' );
		remove_action( 'menu_order',        'bbp_admin_menu_order'        );

		// Turn UI off in admin
		add_filter( 'bbp_register_forum_post_type', array( $this, 'filter_post_type' ), 4 );
		add_filter( 'bbp_register_topic_post_type', array( $this, 'filter_post_type' ), 4 );
		add_filter( 'bbp_register_reply_post_type', array( $this, 'filter_post_type' ), 4 );
	}

	/**
	 * Set some CPT arguments to false
	 *
	 * @since 1.0
	 * @param array $args The bbPress CPT arguments
	 * @return array()
	 */
	function filter_post_type( $args ) {
		$args['show_in_nav_menus'] = $args['show_ui'] = $args['can_export'] = false;

		return $args;
	}
}
new BBP_No_Admin();
