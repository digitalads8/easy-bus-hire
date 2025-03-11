<?php

/**
 * Class Es_Authentication_Shortcode.
 */
class Es_Authentication_Shortcode extends Es_Shortcode {

    /**
     * @return string
     */
    public function get_content() {
        if ( $auth_item = filter_input( INPUT_GET, 'auth_item' ) ) {
            $this->_attributes['auth_item'] = $auth_item;
        }
        ob_start();
        es_load_template( 'front/shortcodes/authentication/authentication.php', $this->_attributes );
        return ob_get_clean();
    }

    /**
     * Return default attributes.
     *
     * @return array
     */
    public function get_default_attributes() {
        $default = parent::get_default_attributes();

        return es_parse_args( $default, array(
            'auth_item' => 'login-buttons',
            'enable_facebook' => ests( 'is_login_facebook_enabled' ),
            'enable_google' => ests( 'is_login_google_enabled' ),
            'enable_login_form' => ests( 'is_login_form_enabled' ),
            'login_title' =>  __(ests( 'login_title' ), 'es' ),
            'login_subtitle' => __(ests( 'login_subtitle' ), 'es' ),
            'enable_buyers_register' => ests( 'is_buyers_register_enabled' ),
            'buyer_register_title' => __(ests( 'buyer_register_title' ), 'es' ),
            'buyer_register_subtitle' => __(ests( 'buyer_register_subtitle' ), 'es' ),

            'enable_agents_register' => ests( 'is_agents_register_enabled' ),
            'enable_agents_automatic_publication' => ests( 'is_automatic_approval_of_agents_publication' ),
            'agent_register_title' => __(ests( 'agent_register_title' ), 'es' ),
            'agent_register_subtitle' => __(ests( 'agent_register_subtitle' ), 'es' ),
        ) );
    }

    /**
     * Return shortcode name.
     *
     * @return string
     */
    public static function get_shortcode_name() {
        return 'es_authentication';
    }
}
