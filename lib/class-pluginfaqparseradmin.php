<?php
/**
 * Plugin FAQ Parser
 *
 * @package    Plugin FAQ Parser
 * @subpackage PluginFaqParserAdmin Management screen
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

$pluginfaqparseradmin = new PluginFaqParserAdmin();

/** ==================================================
 * Management screen
 */
class PluginFaqParserAdmin {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.06
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );

	}

	/** ==================================================
	 * Add a "Settings" link to the plugins page
	 *
	 * @param  array  $links  links array.
	 * @param  string $file   file.
	 * @return array  $links  links array.
	 * @since 1.00
	 */
	public function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) {
			$this_plugin = 'plugin-faq-parser/pluginfaqparser.php';
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=PluginFaqParser' ) . '">' . __( 'Settings' ) . '</a>';
		}
		return $links;
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_menu() {
		add_options_page( 'PluginFaqParser Options', 'Plugin FAQ Parser', 'manage_options', 'PluginFaqParser', array( $this, 'plugin_options' ) );
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_options() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$this->options_updated();

		$scriptname = admin_url( 'options-general.php?page=PluginFaqParser' );

		?>
		<div class="wrap">
			<h2>Plugin FAQ Parser</h2>

			<details>
			<summary><strong><?php esc_html_e( 'Various links of this plugin', 'plugin-faq-parser' ); ?></strong></summary>
			<?php $this->credit(); ?>
			</details>

			<h3><?php esc_html_e( 'How to use', 'plugin-faq-parser' ); ?></h3>
			<div style="margin: 0px 10px;"><?php esc_html_e( 'Please add new Page. Please insert a block or write a shortcode.', 'plugin-faq-parser' ); ?></div>

			<div style="margin: 0px 10px;">
				<h3><?php esc_html_e( 'Example of short code', 'plugin-faq-parser' ); ?></h3>

				<div style="padding: 10px 0px;"><code>[pluginfaq slug="media-from-ftp"]</code></div>
				<div style="padding: 10px 0px;"><code>[pluginfaq slug="media-from-ftp" bdback="#ffffff"]</code></div>

				<h3><?php esc_html_e( 'Description of each attribute', 'plugin-faq-parser' ); ?></h3>

				<ul style="margin:10px;">
					<li type="disc"><code>slug</code> <?php esc_html_e( 'Specifies the plugin slug.', 'plugin-faq-parser' ); ?></li>
					<li type="disc"><?php esc_html_e( 'Question', 'plugin-faq-parser' ); ?></li>
					<ul style="margin:10px;">
						<li type="circle"><code>bdline</code> <?php esc_html_e( 'Border Color', 'plugin-faq-parser' ); ?></li>
						<li type="circle"><code>bdback</code> <?php esc_html_e( 'Background Color' ); ?></li>
						<li type="circle"><code>bdtext</code> <?php esc_html_e( 'Text Color' ); ?></li>
					</ul>
					<li type="disc"><?php esc_html_e( 'Answer', 'plugin-faq-parser' ); ?></li>
					<ul style="margin:10px;">
						<li type="circle"><code>back</code> <?php esc_html_e( 'Background Color' ); ?></li>
						<li type="circle"><code>text</code> <?php esc_html_e( 'Text Color' ); ?></li>
					</ul>
				</ul>

				<h3><?php esc_html_e( 'It will create a cache in one-day intervals for speedup. Please delete the cache if you want to display the most recent data.', 'plugin-faq-parser' ); ?></h3>

				<form style="padding:10px;" method="post" action="<?php echo esc_url( $scriptname ); ?>" />
					<?php wp_nonce_field( 'pfq_settings', 'pluginfaqparser_settings' ); ?>
					<?php submit_button( __( 'Remove Cache', 'plugin-faq-parser' ), 'large', 'pluginfaq_clear_cash', false ); ?>
				</form>
			</div>
		</div>
		<?php
	}

	/** ==================================================
	 * Credit
	 *
	 * @since 1.00
	 */
	private function credit() {

		$plugin_name    = null;
		$plugin_ver_num = null;
		$plugin_path    = plugin_dir_path( __DIR__ );
		$plugin_dir     = untrailingslashit( wp_normalize_path( $plugin_path ) );
		$slugs          = explode( '/', $plugin_dir );
		$slug           = end( $slugs );
		$files          = scandir( $plugin_dir );
		foreach ( $files as $file ) {
			if ( '.' === $file || '..' === $file || is_dir( $plugin_path . $file ) ) {
				continue;
			} else {
				$exts = explode( '.', $file );
				$ext  = strtolower( end( $exts ) );
				if ( 'php' === $ext ) {
					$plugin_datas = get_file_data(
						$plugin_path . $file,
						array(
							'name'    => 'Plugin Name',
							'version' => 'Version',
						)
					);
					if ( array_key_exists( 'name', $plugin_datas ) && ! empty( $plugin_datas['name'] ) && array_key_exists( 'version', $plugin_datas ) && ! empty( $plugin_datas['version'] ) ) {
						$plugin_name    = $plugin_datas['name'];
						$plugin_ver_num = $plugin_datas['version'];
						break;
					}
				}
			}
		}
		$plugin_version = __( 'Version:' ) . ' ' . $plugin_ver_num;
		/* translators: FAQ Link & Slug */
		$faq       = sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'plugin-faq-parser' ), $slug );
		$support   = 'https://wordpress.org/support/plugin/' . $slug;
		$review    = 'https://wordpress.org/support/view/plugin-reviews/' . $slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/' . $slug;
		$facebook  = 'https://www.facebook.com/katsushikawamori/';
		$twitter   = 'https://twitter.com/dodesyo312';
		$youtube   = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate    = __( 'https://shop.riverforest-wp.info/donate/', 'plugin-faq-parser' );

		?>
		<span style="font-weight: bold;">
		<div>
		<?php echo esc_html( $plugin_version ); ?> | 
		<a style="text-decoration: none;" href="<?php echo esc_url( $faq ); ?>" target="_blank" rel="noopener noreferrer">FAQ</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $support ); ?>" target="_blank" rel="noopener noreferrer">Support Forums</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $review ); ?>" target="_blank" rel="noopener noreferrer">Reviews</a>
		</div>
		<div>
		<a style="text-decoration: none;" href="<?php echo esc_url( $translate ); ?>" target="_blank" rel="noopener noreferrer">
		<?php
		/* translators: Plugin translation link */
		echo esc_html( sprintf( __( 'Translations for %s' ), $plugin_name ) );
		?>
		</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-twitter"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-video-alt3"></span></a>
		</div>
		</span>

		<div style="width: 250px; height: 180px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
		<h3><?php esc_html_e( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'plugin-faq-parser' ); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo esc_url( $donate ); ?>')"><?php esc_html_e( 'Donate to this plugin &#187;' ); ?></button>
		</div>

		<?php

	}

	/** ==================================================
	 * Update wp_options table.
	 *
	 * @since 1.00
	 */
	private function options_updated() {

		if ( isset( $_POST['pluginfaq_clear_cash'] ) && ! empty( $_POST['pluginfaq_clear_cash'] ) ) {
			if ( check_admin_referer( 'pfq_settings', 'pluginfaqparser_settings' ) ) {
				$del_cash_count = $this->delete_all_cash();
				if ( 0 < $del_cash_count ) {
					echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html__( 'Removed the cache.', 'plugin-faq-parser' ) . '</li></ul></div>';
				} else {
					echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html__( 'No Cache', 'plugin-faq-parser' ) . '</li></ul></div>';
				}
			}
		}

	}

	/** ==================================================
	 * Delete all cache
	 *
	 * @return int $del_cash_count(int)
	 * @since 1.00
	 */
	private function delete_all_cash() {

		global $wpdb;
		$search_transients = '%pluginfaq_datas_%';
		$del_transients = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT	option_name
				FROM	$wpdb->options
				WHERE	option_name LIKE %s
				",
				$search_transients
			)
		);

		$del_cash_count = 0;
		foreach ( $del_transients as $del_transient ) {
			$transient = str_replace( '_transient_', '', $del_transient->option_name );
			$value_del_cash = get_transient( $transient );
			if ( false <> $value_del_cash ) {
				delete_transient( $transient );
				++$del_cash_count;
			}
		}

		return $del_cash_count;

	}

}


