<?php

/**
 * Class Es_Admin_Listing_Agent_Added_Email.
 */
class Es_Saved_Search_Email extends Es_Email {

	/**
	 * Generate email content.
	 *
	 * @return string
	 */
	public function get_content() {
		ob_start();
		es_load_template( 'common/emails/saved-search-email.php', $this->_data );

		return ob_get_clean();
	}

	/**
	 * Generate email subject.
	 *
	 * @return string
	 */
	public function get_subject() {
		$subject = ests( 'saved_search_email_subject' );
		return apply_filters( 'es_saved_search_email_subject', $subject );
	}

	public static function get_label() {
		return __( 'Saved search', 'es' );
	}
}
