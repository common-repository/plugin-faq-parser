<?php
/**
 * Plugin FAQ Parser
 *
 * @package    Plugin FAQ Parser
 * @subpackage PluginFaqParser Main Functions
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

$pluginfaqparser = new PluginFaqParser();

/** ==================================================
 * Main Functions
 */
class PluginFaqParser {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.06
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'pluginfaq_block_init' ) );

	}

	/** ==================================================
	 * Attribute block
	 *
	 * @since 2.00
	 */
	public function pluginfaq_block_init() {

		$asset_file = include( plugin_dir_path( __DIR__ ) . 'block/dist/pluginfaq-block.asset.php' );

		wp_register_script(
			'pluginfaq-block',
			plugins_url( 'block/dist/pluginfaq-block.js', dirname( __FILE__ ) ),
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);

		wp_localize_script(
			'pluginfaq-block',
			'pluginfaq_text',
			array(
				'slug'     => __( 'Slug' ),
				'color'    => __( 'Color' ),
				'question' => __( 'Question', 'plugin-faq-parser' ),
				'answer'   => __( 'Answer', 'plugin-faq-parser' ),
				'bdcolor'  => __( 'Border Color', 'plugin-faq-parser' ),
				'bgcolor'  => __( 'Background Color' ),
				'txcolor'  => __( 'Text Color' ),
			)
		);

		register_block_type(
			'plugin-faq-parser/pluginfaq-block',
			array(
				'editor_script'   => 'pluginfaq-block',
				'render_callback' => array( $this, 'pluginfaq_shorcode' ),
				'attributes'      => array(
					'slug'   => array(
						'type'      => 'string',
						'default'   => null,
					),
					'bdline' => array(
						'type'      => 'string',
						'default'   => '#ddd',
					),
					'bdback' => array(
						'type'      => 'string',
						'default'   => '#f4f4f4',
					),
					'bdtext' => array(
						'type'      => 'string',
						'default'   => '#000',
					),
					'back' => array(
						'type'      => 'string',
						'default'   => null,
					),
					'text' => array(
						'type'      => 'string',
						'default'   => null,
					),
				),
			)
		);

		add_shortcode( 'pluginfaq', array( $this, 'pluginfaq_shorcode' ) );

	}

	/** ==================================================
	 * Short code
	 *
	 * @param array  $atts  atts.
	 * @param string $content  content.
	 * @return string $content
	 * @since 1.00
	 */
	public function pluginfaq_shorcode( $atts, $content = null ) {

		$a = shortcode_atts(
			array(
				'slug'   => '',
				'bdline' => '',
				'bdback' => '',
				'bdtext' => '',
				'back'   => '',
				'text'   => '',
			),
			$atts
		);

		return $this->main_func( $a, $content = null );

	}

	/** ==================================================
	 * Short code
	 *
	 * @param array  $a  a.
	 * @param string $content  content.
	 * @return string $content
	 * @since 1.00
	 */
	public function main_func( $a, $content = null ) {

		$slug   = $a['slug'];

		if ( ! empty( $a['bdline'] ) ) {
			$bdline = $a['bdline'];
		} else {
			$bdline = '#ddd';
		}
		if ( ! empty( $a['bdback'] ) ) {
			$bdback = $a['bdback'];
		} else {
			$bdback = '#f4f4f4';
		}
		if ( ! empty( $a['bdtext'] ) ) {
			$bdtext = $a['bdtext'];
		} else {
			$bdtext = '#000';
		}
		if ( ! empty( $a['back'] ) ) {
			$back = ' background: ' . $a['back'] . ';';
		} else {
			$back = null;
		}
		if ( ! empty( $a['text'] ) ) {
			$text = ' color: ' . $a['text'] . ';';
		} else {
			$text = null;
		}

		if ( ! empty( $slug ) ) {
			$faq = null;
			if ( get_transient( 'pluginfaq_datas_' . $slug . '_' . get_locale() ) ) {
				/* Get cache */
				$faq = get_transient( 'pluginfaq_datas_' . $slug . '_' . get_locale() );
			} else {
				/* Call API */
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
				$call_api = plugins_api(
					'plugin_information',
					array(
						'slug' => $slug,
						'fields' => array( 'sections' => true ),
					)
				);
				if ( is_wp_error( $call_api ) ) {
					$dummy = 0; /* skip */
				} else {
					$sections = $call_api->sections;
					if ( array_key_exists( 'faq', $sections ) ) {
						$faq = $sections['faq'];
					} else {
						return '';
					}
					/* Set cache */
					set_transient( 'pluginfaq_datas_' . $slug . '_' . get_locale(), $faq, 86400 );
				}
			}

			$content = '<details>' . $faq . '</details>';
			$content = str_replace( '<h4>', '</details><details><summary style="cursor: pointer; padding: 10px; border: 1px solid ' . $bdline . '; background: ' . $bdback . '; color: ' . $bdtext . ';">', $content );
			$content = str_replace( '</h4>', '</summary>', $content );
			$content = str_replace( '<p>', '<div>', $content );
			$content = str_replace( '</p>', '</div>', $content );
			$content = str_replace( "\n", '', $content );
			$content = str_replace( '<details></details>', '', $content );
			$content = str_replace( '<details>', '<details style="margin-bottom: 5px;' . $back . $text . '">', $content );

			return $content;
		} else {
			if ( is_user_logged_in() ) {
				$content .= '<div style="text-align: center;">';
				$content .= '<div><strong><span class="dashicons dashicons-editor-help" style="position: relative; top: 5px;"></span>Plugin FAQ Parser</strong></div>';
				/* translators: Input Slug */
				$content .= sprintf( __( 'Please input "%1$s".', 'plugin-faq-parser' ), __( 'Slug' ) );
				$content .= '</div>';
				return $content;
			}
		}

	}

}


