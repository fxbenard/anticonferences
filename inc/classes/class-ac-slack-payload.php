<?php
/**
 * Slack Payload
 *
 * @since  1.0.0
 *
 * @package AntiConférences
 * @subpackage inc\classes
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

class AC_Slack_Payload {
	/**
	 * The Slack Attachments
	 *
	 * @var array
	 */
	public $attachments = array();

	/**
	 * The Constructor
	 *
	 * @since  1.0.0
	 *
	 * @param WP_Comment $subject The subject Object.
	 */
	public function __construct( WP_Comment $subject ) {
		$festival = get_post_field( 'post_title', $subject->comment_post_ID );

		$title = sprintf(
			__( '[%1$s] Nouveau Sujet posté : %2$s', 'anticonferences' ),
			$festival,
			sprintf( '<%1$s|%2$s>',
				esc_url_raw( add_query_arg( array(
					'action' => 'editcomment',
					'c' => $subject->comment_ID
				), admin_url( 'comment.php' ) ) ),
				esc_html__( 'Modérer', 'anticonferences')
			)
		);

		$this->attachments[] = (object) array(
			'fallback' => $title,
			'pretext'  => $title,
			'color'    => '#006494',
			'fields'   => array(),
		);

		$this->attachments[0]->fields[] = (object) array(
			'title' => sprintf( __( 'Auteur : %s', 'anticonferences' ), esc_html( $subject->comment_author ) ),
			'value' => wp_trim_words( $subject->comment_content, 30 ),
			'short' => false,
		);
	}

	/**
	 * Encodes the Payload in JSON.
	 *
	 * @since  1.0.0
	 *
	 * @return string The payload object encoded in JSON.
	 */
	public function get_json() {
		return json_encode( $this );
	}
}