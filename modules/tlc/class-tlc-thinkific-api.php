<?php
/**
 * TLC_Thinkific_Import_Course
 *
 * Class in charge of importing Thinkific courses
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

final class TLC_Thinkific_API {

    private $headers = array();
    private $method = 'GET';
    private $params = array();
    private $body = array();
    private $args = array();

    public function __construct() {

        $this->headers = array(
            'X-Auth-API-Key' => get_field('thinkific_api_key', 'option'),
            'X-Auth-Subdomain' => get_field('thinkific_subdomain', 'option'),
            'Content-Type' => 'application/json'
        );

        $this->args = array(
            'method' => $this->method,
            'headers' => $this->headers,
            'body' => $this->body
        );

    }
    
    public function get_method() {
        return $this->args['method'];
    }

    public function get_headers() {
        return $this->args['headers'];
    }

    public function get_params() {
        return $this->params;
    }

    public function get_body() {
        return $this->args['body'];
    }

    public function get_args() {
        return $this->args;
    }

    public function set_method($method) {
        $this->args['method'] = $method;
    }

    public function set_headers($headers = array()) {
        $this->args['headers'] = wp_parse_args($headers, $this->args['headers']);
    }

    public function set_params($params = array()) {
        $this->params = $params;
    }

    public function set_body($body = array()) {
        $this->args['body'] = json_encode(wp_parse_args($body, $this->args['body']));
    }

    public function set_args($args = array()) {
        $this->args = wp_parse_args($args, $this->args);
    }

    /**
     * Fetch data from Thinkific API
     * 
     * @param string $endpoint
     */
    public function crud($endpoint) {

        if(!empty($this->params) && is_array($this->params)){
            $endpoint .= '?'.http_build_query($this->params);
        }
        
        $response = wp_remote_request(TLC_THINKIFIC_API_URL.$endpoint, $this->args);
        
        if(!is_wp_error($response)) {
            $body = wp_remote_retrieve_body($response);
            $body = json_decode($body);
            return $body;
        }

        return $response;

    }

    /**
     * Check if user exists in Thinkific
     * 
     * @info use set_params() to user data
     * @return bool
     */
    public function user_exists($email) {    
        $this->set_method('GET');
        $this->set_params(array(
            'limit' => 1,
            'query' => array(
                'email' => $email,
                'role' => 'student'
            )
        ));
        $user = $this->crud('users');

        if($user->items && !empty($user->items) && is_array($user->items) && isset($user->items[0]) && isset($user->items[0]->id) && is_numeric($user->items[0]->id)) {
            return $user->items[0]->id;
        }

        return false;
    }

    /**
     * Create user in Thinkific
     * 
     * @param int $user_id
     */
    public function create_user($user_id) {

        $user = new TLC_User($user_id);
        $this->set_method('POST');
        $this->set_params(array()); // Reset params
        $this->set_body(array(
            'first_name' => $user->get_first_name(),
            'last_name' => $user->get_last_name(),
            'email' => $user->get_email(),
            'password' => wp_generate_password(),
            'roles' => array(
                'student'
            ),
            'bio' => '',
            'company' => $user->get_billing_company(),
            'headline' => '',
            'custom_profile_fields' => array(
                array(
                    'value' => $user->get_school(),
                    'custom_profile_field_definition_id' => 59860 // School
                ),
                array(
                    'value' => $user->get_position(),
                    'custom_profile_field_definition_id' => 59861 // Position
                )
            ),
            'skip_custom_fields_validation' => false,
            'send_welcome_email' => false,
            'external_id' => 'theliteracycompanycouk_'.$user_id,
            'provider' => 'SSO'
        ));

        return $this->crud('users');

    }

    /**
     * Updates user in Thinkific
     * 
     * @param int $user_id
     */
    public function update_user($user_id) {

        $user = new TLC_User($user_id);
        $this->set_method('PUT');
        $this->set_params(array()); // Reset params
        $this->set_body(array(
            'first_name' => $user->get_first_name(),
            'last_name' => $user->get_last_name(),
            'email' => $user->get_email(),
            'roles' => array(
                'student'
            ),
            'company' => $user->get_billing_company(),
            'custom_profile_fields' => array(
                array(
                    'value' => $user->get_school(),
                    'custom_profile_field_definition_id' => 59860 // School
                ),
                array(
                    'value' => $user->get_position(),
                    'custom_profile_field_definition_id' => 59861 // Position
                )
            )
        ));

        return $this->crud('users/'.$user->get_thinkific_user_id());

    }

}