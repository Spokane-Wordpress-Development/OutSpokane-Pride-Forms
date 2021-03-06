<?php

/**
 * Plugin Name: OutSpokane Pride Forms
 * Plugin URI:
 * Description: Custom plugin for all the OutSpokane forms
 * Author: Tony DeStefano
 * Author URI:
 * Version: 1.7.0
 * Text Domain: out-spokane-pride-forms
 *
 * Copyright 2016 Tony DeStefano
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package OutSpokanePrideForms
 * @author Tony DeStefano
 * @version 1.7.0
 */

require_once ( 'classes/Controller.php' );
require_once ( 'classes/EntryTable.php' );
require_once ( 'classes/Entry.php' );
require_once ( 'classes/CruiseEntry.php' );
require_once ( 'classes/FestivalEntry.php' );
require_once ( 'classes/ParadeEntry.php' );
require_once ( 'classes/MurderMysteryEntry.php' );
require_once ( 'classes/Donation.php' );
require_once ( 'classes/FlagHandle.php' );
require_once ( 'classes/Sponsorship.php' );
require_once ( 'classes/BowlingEntry.php' );
require_once ( 'classes/FoodTruck.php' );
require_once ( 'classes/Stripe/init.php' );

/* controller object  */
$controller = new \OutSpokane\Controller;

/* activate */
register_activation_hook( __FILE__, array( $controller, 'activate' ) );

/* uninstall */
register_uninstall_hook( __FILE__, array( '\OutSpokane\Controller', 'uninstall' ) );

/* initialize any variables that the plugin needs */
add_action( 'init', array( $controller, 'init' ) );

/* capture form post */
add_action ( 'init', array( $controller, 'formCapture' ) );

/* register query vars */
add_filter( 'query_vars', array( $controller, 'queryVars') );

/* register shortcode */
add_shortcode ( 'pride_forms', array( $controller, 'shortCode') );

/* capture ajax */
add_action( 'wp_ajax_pride_entry', array( $controller, 'handleNewAjaxEntry') );
add_action( 'wp_ajax_nopriv_pride_entry', array( $controller, 'handleNewAjaxEntry') );

/* Only run these hooks if logged into the admin screen */
if ( is_admin() )
{
	/* register settings */
	add_action( 'admin_init', array( $controller, 'registerSettings' ) );

	/* Add main menu and sub-menus */
	add_action( 'admin_menu', array( $controller, 'addMenus') );

	/* enqueue admin scripts */
	add_action( 'admin_enqueue_scripts', array( $controller, 'enqueueAdminScripts' ) );

	/* update entry notes ajax */
	add_action( 'wp_ajax_pride_forms_update_notes', array( $controller, 'updateEntryNotes' ) );

	/* update entry details ajax */
	add_action( 'wp_ajax_pride_forms_update_details', array( $controller, 'updateEntryDetails' ) );

	/* update entry payment ajax */
	add_action( 'wp_ajax_pride_forms_update_payment', array( $controller, 'updateEntryPayment' ) );

	/* delete entry ajax */
	add_action( 'wp_ajax_pride_forms_delete_entry', array( $controller, 'deleteEntry' ) );
}