<?php

/**
 * @package WeLoveCustomers
 */
/*
Plugin Name: Referral program / Parrainage clients
Plugin URI: http://www.welovecustomers.fr/
Description FR: Intégrez un programme de parrainage clients à votre site Internet
Description: Integrate referral marketing within your website
Version: 2.8.2
Author: WeLoveCustomers
Author URI: http://www.welovecustomers.fr
License: GPLv2 or later
Text Domain: referral-program-parrainage-clients
Domain Path: /languages
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define('WLC_VERSION', '2.8.2');
define('WLC_TRANSLATOR', 'referral-program-parrainage-clients');
define('WLC_MINIMUM_WP_VERSION', '3.2');
define('WLC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WLC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WLC_HOST', 'dj8z0bra0q3sp.cloudfront.net');
define('WLC_IMG_DIR', WLC_PLUGIN_URL . 'img/');


/*****************************/

/*
register_activation_hook( __FILE__, 'plugin_activation' );
register_deactivation_hook( __FILE__, 'plugin_deactivation' );
*/

require_once(WLC_PLUGIN_DIR . 'class.wlc-referral.php');
Wlc_Referral::init();

// ajout du hook pour l'admin
if (is_admin()) {
	require_once(WLC_PLUGIN_DIR . 'class.wlc-referral-admin.php');
	add_action('init', array('Wlc_Referral_Admin', 'init'));
	register_uninstall_hook(__FILE__, array('Wlc_Referral_Admin', 'uninstall'));
}
