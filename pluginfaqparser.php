<?php
/**
 * Plugin Name: Plugin FAQ Parser
 * Plugin URI:  https://wordpress.org/plugins/plugin-faq-parser/
 * Description: Parse the Plugin's FAQ in Plugin Directory.
 * Version:     2.05
 * Author:      Katsushi Kawamori
 * Author URI:  https://riverforest-wp.info/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: plugin-faq-parser
 *
 * @package Plugin FAQ Parser
 */

/*
	Copyright (c) 2016- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

if ( ! class_exists( 'PluginFaqParserAdmin' ) ) {
	require_once( dirname( __FILE__ ) . '/lib/class-pluginfaqparseradmin.php' );
}
if ( ! class_exists( 'PluginFaqParser' ) ) {
	require_once( dirname( __FILE__ ) . '/lib/class-pluginfaqparser.php' );
}


