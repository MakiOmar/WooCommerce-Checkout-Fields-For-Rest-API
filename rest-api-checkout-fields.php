<?php

/*
* As you can't do this stright away because there is no checkout available at that point (and no customer or even WooCommerce session).
* So the solution is to fake session and customer so checkout will be available in the admin area (with default user data).
*/
function get_checkout_fields(){
	
	/*
	 * WooCommerce does not load session class on backend, so we need to do this manually
	 */
	if ( ! class_exists( 'WC_Session' ) ) {
		include_once( WP_PLUGIN_DIR . '/woocommerce/includes/abstracts/abstract-wc-session.php' );
	}
	
    /*
    * First lets start the session. You cant use here WC_Session directly
    * because it's an abstract class. But you can use WC_Session_Handler which
    * extends WC_Session
    */
    WC()->session = new WC_Session_Handler;

    /*
    * Next lets create a customer so we can access checkout fields
    * If you will check a constructor for WC_Customer class you will see
    * that if you will not provide user to create customer it will use some
    * default one. Magic.
    */
    WC()->customer = new WC_Customer;

    /*
    * Done. You can browse all chceckout fields (including custom ones)
    */
    return WC()->checkout->checkout_fields;
   
}


add_action( 'rest_api_init', function () {
  register_rest_route( 'wc/v3', '/checkout_fields', array(
    'methods' => 'GET',
    'callback' => 'get_checkout_fields',
  ) );
} );