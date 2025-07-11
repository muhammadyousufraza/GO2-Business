<?php

/**
 * Iframe Player.
 *
 * @link    https://plugins360.com
 * @since   3.5.0
 *
 * @package All_In_One_Video_Gallery
 */

// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * AIOVG_Player_Iframe class.
 *
 * @since 3.5.0
 */
class AIOVG_Player_Iframe extends AIOVG_Player_Base {

	/**
	 * Get things started.
	 *
	 * @since 3.5.0
	 * @param int   $post_id      Post ID.
 	 * @param array $args         Player options.
	 * @param int   $reference_id Player reference ID.
	 */
	public function __construct( $post_id, $args, $reference_id ) {	
		parent::__construct( $post_id, $args, $reference_id );	
	}	

	/**
	 * Get the player HTML.
	 *
	 * @since  3.5.0
 	 * @return string $html Player HTML.
	 */
	public function get_player() {
		$player_settings = $this->get_player_settings();

		$this->embed_url = aiovg_get_player_page_url( $this->post_id, $this->args );

		// Output
		$html = sprintf( 
			'<div class="aiovg-player-container" style="max-width: %s;">',
			( ! empty( $player_settings['width'] ) ? (int) $player_settings['width'] . 'px' : '100%' ) 
		);	

		$html .= sprintf( 
			'<div class="aiovg-player aiovg-player-iframe" style="padding-bottom: %s%%;">', 
			(float) $player_settings['ratio']
		);	

		$html .= sprintf( 
			'<iframe src="%s" title="%s" width="560" height="315" frameborder="0" scrolling="no" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen%s></iframe>', 
			esc_url( $this->embed_url ),
			esc_attr( $this->post_title ),
			( ! empty( $player_settings['lazyloading'] ) ? ' loading="lazy"' : '' )
		);	

		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
	
}
