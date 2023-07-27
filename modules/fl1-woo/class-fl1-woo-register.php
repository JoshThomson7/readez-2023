<?php
/**
 * FL1 WooCommerce Register
 *
 * Class in charge of WooCommerce's Register/Login
 * action and hook overrides
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class FL1_Woo_Register {

    public function __construct() {

        add_action('woocommerce_register_post', array($this, 'extra_register_fields'), 10, 3);

		add_filter('woocommerce_registration_redirect', array($this, 'registration_redirect'), 2);
        add_action('woocommerce_created_customer', array($this, 'created_customer'));
		add_filter('authenticate', array($this, 'authenticate'), 30, 3);
		$this->process_verification_endpoint();

		add_action('wp_login', array($this, 'link_orders_login'), 10, 2);
        add_action('wc_guest_to_customer_linked_orders', array($this, 'maybe_show_linked_order_count'));
        
    }

	/**
     * Check the verification status of the user during login
     *
     * @param WP_User|WP_Error $user     The user object if authentication was successful, or WP_Error otherwise.
     * @param string           $username The username used for authentication.
     * @param string           $password The password used for authentication.
     *
     * @return WP_User|WP_Error The user object or WP_Error based on the verification status.
     */
    public function authenticate($user, $username, $password) {
        
		$_user = new FL1_Woo_User($user->ID);

        if(is_a($user, 'WP_User') && !$_user->is_account_verified() && $_user->get_role() === 'customer') {
            $error = new WP_Error('not_verified', 'Your account has not been verified yet.');
            return $error;
        }

        return $user;
    }

	/**
	 * Prevent automatic login when registering and redirect to login page
	 * 
	 * @param  $redirect 
	 * @return 
	 */
	public function registration_redirect($redirect) {

		$current_user = wp_get_current_user();
        $user_id = $current_user->ID;
		$user = new FL1_Woo_User($user_id);

		if(!$user->is_account_verified()) {
			wp_logout();
			$redirect = add_query_arg('account_status', 'unverified', FL1_Woo_Helpers::get_my_account_url());
		}

		return $redirect;
	}

	/**
     * Send verification email to the newly registered user
     *
     * @param int $user_id The ID of the registered user
     */
    public function created_customer($user_id) {

		$this->save_extra_register_fields($user_id);
        $this->update_new_customer_past_orders($user_id);
        $this->update_user_nicename($user_id);

		$user = new FL1_Woo_User($user_id);

        // Generate unique verification key
        $verification_key = wp_generate_password(32, false);

        // Store the verification key in user meta
        update_user_meta($user_id, 'verification_key', $verification_key);

        // Generate verification URL
        $verification_url = add_query_arg(array(
            'action' => 'verify_account',
            'user_id' => $user_id,
            'key' => $verification_key
        ), FL1_Woo_Helpers::get_my_account_url());

        $email = new FL1_Email();
		$email->To(array($user->get_email()));
		$email->Subject('Verify your account');

		$body = '<p>Please verify your account by clicking on the button below.</p>';
		$body .= '<p><a class="button" href="'.esc_url($verification_url).'" target="_blank">Verify</a></p>';

		$email->Body($body);
		$email->send();

    }

	/**
     * Process the verification endpoint
     */
    public function process_verification_endpoint() {

        if (isset($_GET['action']) && $_GET['action'] === 'verify_account' && isset($_GET['user_id']) && isset($_GET['key'])) {

            $user_id = intval($_GET['user_id']);
            $key = sanitize_text_field($_GET['key']);

            $verification_key = get_user_meta($user_id, 'verification_key', true);

            // Verify the user account
            if ($verification_key === $key) {
                update_user_meta($user_id, 'is_verified', true);

                // Redirect to a success page or the user's profile page
                wp_redirect(FL1_Woo_Helpers::get_my_account_url().'?account_status=verified');
                exit;
            } else {
                // Redirect to an error page or display an error message
                $error = new WP_Error('not_verified', 'Your account has not been verified yet.');
            }

        }

    }

	/**
     * Validate the extra register fields.
     *
     * @param  string $username          Current username.
     * @param  string $email             Current email.
     * @param  object $validation_errors WP_Error object.
     *
     * @return void
     */
    public function extra_register_fields($username, $email, $validation_errors) {

        $fields = array(
            'billing_first_name' => 'First Name',
            'billing_last_name' => 'Last Name'
        );

        foreach ($fields as $key => $field) {
            if(isset($_POST[$key]) && empty($_POST[$key])) {
                $validation_errors->add('validation_error', __($field . ' is required', 'woocommerce'));
            }
        }

    }

    /**
	 * Links previous orders to a new customer upon registration.
	 *
	 * @since 1.0.0
	 * @param int $user_id the ID for the new user
	 */
    private function update_new_customer_past_orders($user_id) {

        // @see https://woocommerce.wp-a2z.org/oik_api/wc_update_new_customer_past_orders/
        $count = wc_update_new_customer_past_orders($user_id);
		update_user_meta($user_id, 'wc_linked_order_count', $count);

    }

    /**
	 * Links previous orders to an existing customer upon logging in.
	 *
	 * @since 1.0.0
	 * @param int $user_id the ID for the new user
	 */
	public function link_orders_login($user_login, $user) {

		$this->update_new_customer_past_orders($user->ID);

	}

    /**
     * Save the extra register fields.
     *
     * @param  int  $customer_id Current customer ID.
     *
     * @return void
     */
    private function save_extra_register_fields($customer_id) {

        if(isset($_POST['billing_first_name'])) {
            $first_name = sanitize_text_field($_POST['billing_first_name']);
            update_user_meta( $customer_id, 'first_name', $first_name); // WordPress default first name field.
            update_user_meta( $customer_id, 'billing_first_name', $first_name); // WooCommerce billing first name.
            update_user_meta( $customer_id, 'display_name', $first_name);
        }
    
        if(isset($_POST['billing_last_name'])) {
            update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
            update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
        }

    }

    /**
	 * Shows the "orders linked" notice upon first account visit if any were linked at registration.
	 *
	 * @since 1.0.0
	 */
	public function maybe_show_linked_order_count() {

		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return;
		}

		$count = get_user_meta( $user_id, 'wc_linked_order_count', true );

		if ( $count && $count > 0 ) {

			$fname = get_user_by( 'id', $user_id )->first_name;

			$message  = $fname ? sprintf( esc_html__( 'Welcome, %s!', 'link-wc-orders' ), $fname ) : esc_html__( 'Welcome!', 'link-wc-orders' );
			$message .= ' ' . esc_html__( sprintf( _n( 'Good news! We have linked a previous order you had made to this account.', 'Good news! We have linked %s previous orders you had made to this account.', $count, 'link-wc-orders' ), $count ) );
			$message .= ' <a class="button" href="' . esc_url( wc_get_endpoint_url( 'orders' ) ) . '">' . esc_html__( 'View Orders', 'link-wc-orders' ) . '</a>';

			wc_print_notice( $message, 'notice' );
			delete_user_meta( $user_id, 'wc_linked_order_count' );

		}

	}

    /**
     * Updates user_nicename and display_name
     * 
     * @param int $user_id
     */
    private function update_user_nicename($user_id) {

        $user = new FL1_Woo_User($user_id);
        $user_full_name = $user->get_full_name();
		$user_slug = $user->get_author_slug();

		update_user_meta($user_id, 'nickname', $user_slug);

        wp_update_user(array(
            'ID'            => $user_id,
            'user_nicename' => sanitize_title($user_full_name),
            'display_name'  => $user_full_name
        ));

    }

}