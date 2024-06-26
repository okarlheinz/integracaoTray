<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * WooCommerce API Client Library
 *
 * @author Boian Georgiev
 * @since 2015.03.25
 * @copyright Boian Georgiev
 * @version 1.0
 * @license GPL 3 or later http://www.gnu.org/licenses/gpl.html
 *
 * based on the WooCommerce API Client Class
 * from Gerhard Potgieter
 * https://github.com/helpforfitness/WooCommerce-REST-API-Client-Library-v2.git
 */

class WooCommerce {

	/**
	 * API base endpoint
	 */
	const API_ENDPOINT = 'wc-api/v2/';
//	const API_ENDPOINT = 'wp-json/wc/v2/';

	/**
	 * The HASH alorithm to use for oAuth signature, SHA256 or SHA1
	 */
	const HASH_ALGORITHM = 'SHA256';

	/**
	 * The API URL
	 * @var string
	 */
	private $_api_url;

	/**
	 * The WooCommerce Consumer Key
	 * @var string
	 */
	private $_consumer_key;

	/**
	 * The WooCommerce Consumer Secret
	 * @var string
	 */
	private $_consumer_secret;

	/**
	 * If the URL is secure, used to decide if oAuth or Basic Auth must be used
	 * @var boolean
	 */
	private $_is_ssl;

	/**
	 * Return the API data as an Object, set to false to keep it in JSON string format
	 * @var boolean
	 */
	private $_return_as_object = true;

	/**
	 * Default contructor
	 * @param array   $params, containing the $consumer_key, the $consumer_secret, the $store_url and the optional $is_ssl parameters required by the api() call
	 */
	public function __construct( $params = array() ) {
	
	//$store_url = 'https://webhook.site/9239c606-306a-41a2-9164-b306e4dd597c/';
		$store_url = 'https://www.anapaulabreccijoias.com.br/';
		$consumer_key = 'ck_0f4c743432dcafd2301c51cd3aa187fc460027b1';
		$consumer_secret = 'cs_1a6c312d01fab6ccce68b3cd7fc35b966d6f6ae2';
		
	//	$store_url = 'http://otmwocommerce.gagarin1703.hospedagemdesites.ws/';
		//$consumer_key = 'ck_7101cc777f8273d7af90045801d069cc6f66d17c';
		//$consumer_secret = 'cs_9447cd4c16372bc2bb7954d47e4a68b371348d5b';
		$is_ssl = true;
		if ($store_url && $consumer_key && $consumer_secret) {
			$this->api( $consumer_key, $consumer_secret, $store_url, $is_ssl );
		}
	}

	/**
	 * Setup the API parameters
	 * @param string  $consumer_key    The consumer key
	 * @param string  $consumer_secret The consumer secret
	 * @param string  $store_url       The URL to the WooCommerce store
	 * @param boolean $is_ssl          If the URL is secure or not, optional
	 */
	public function api( $consumer_key, $consumer_secret, $store_url, $is_ssl = false ) {
		if ( ! empty( $consumer_key ) && ! empty( $consumer_secret ) && ! empty( $store_url ) ) {
			$this->_api_url = (  rtrim($store_url,'/' ) . '/' ) . self::API_ENDPOINT;
			$this->set_consumer_key( $consumer_key );
			$this->set_consumer_secret( $consumer_secret );
			$this->set_is_ssl( $is_ssl );
		} else if ( ! isset( $consumer_key ) && ! isset( $consumer_secret ) ) {
			throw new Exception( 'Error: api() - Consumer Key / Consumer Secret em branco.' );
		} else {
			throw new Exception( 'Error: api() - URL em branco.' );
		}
	}

	/**
	 * Get API Index
	 * @return mixed|json string
	 */
	public function get_index() {
		return $this->_make_api_call( '' );
	}

	/**
	 * Get all orders
	 * @param  array  $params
	 * @return mixed|jason string
	 */
	public function get_orders( $params = array() ) {
		return $this->_make_api_call( 'orders', $params );
	}

	/**
	 * Get a single order
	 * @param  integer $order_id
	 * @return mixed|json string
	 */
	public function get_order( $order_id ) {
		return $this->_make_api_call( 'orders/' . $order_id , array('filter[meta]' => 'true'));
	}

	/**
	 * Get the total order count
	 * @return mixed|json string
	 */
	public function get_orders_count() {
		return $this->_make_api_call( 'orders/count' );
	}

	/**
	 * Get orders notes for an order
	 * @param  integer $order_id
	 * @return mixed|json string
	 */
	public function get_order_notes( $order_id ) {
		return $this->_make_api_call( 'orders/' . $order_id . '/notes' );
	}

	/**
	 * Update the order, currently only status update suported by API
	 * @param  integer $order_id
	 * @param  array  $data
	 * @return mixed|json string
	 */
	public function update_order( $order_id, $data = array() ) {
		// comply with the API specifications
		if ( count($data) != 1 || ! isset($data['order']) || ! is_array($data['order'])  ) {
			$data = array( 'order' => $data );
		}
//		return $this->_make_api_call( 'orders/' . $order_id, $data, 'POST' );
		return $this->_make_api_call( 'orders/' . $order_id, $data, 'PUT' );
	}





	/**
	 * post category
	 * @param  array  $data
	 * @return mixed|json string
	 */
	public function create_categories( $data = array() ) {
		return $this->_make_api_call( 'products/categories', $data, 'POST' );
	}





	/**
	 * Delete the order, not suported in WC 2.1, scheduled for 2.2
	 * @param  integer $order_id
	 * @return mixed|json string
	 */
	public function delete_order( $order_id ) {
		return $this->_make_api_call( 'orders/' . $order_id, $data = array(), 'DELETE' );
	}

	/**
	 * Get all coupons
	 * @param  array  $params
	 * @return mixed|json string
	 */
	public function get_coupons( $params = array() ) {
		return $this->_make_api_call( 'coupons', $params );
	}

	/**
	 * Get a single coupon
	 * @param  integer $coupon_id
	 * @return mixed|json string
	 */
	public function get_coupon( $coupon_id ) {
		return $this->_make_api_call( 'coupons/' . $coupon_id );
	}

	/**
	 * Get the total coupon count
	 * @return mixed|json string
	 */
	public function get_coupons_count() {
		return $this->_make_api_call( 'coupons/count' );
	}

	/**
	 * Get a coupon by the coupon code
	 * @param  string $coupon_code
	 * @return mixed|json string
	 */
	public function get_coupon_by_code( $coupon_code ) {
		return $this->_make_api_call( 'coupons/code/' . rawurlencode( rawurldecode( $coupon_code ) ) );
	}

	/**
	 * Get all customers
	 * @param  array  $params
	 * @return mixed|json string
	 */
	public function get_customers( $params = array() ) {
		return $this->_make_api_call( 'customers', $params );
	}

	/**
	 * Get a single customer
	 * @param  integer $customer_id
	 * @return mixed|json string
	 */
	public function get_customer( $customer_id ) {
		return $this->_make_api_call( 'customers/' . $customer_id, array('filter[meta]' => 'true') );
	}

	/**
	 * Get a single customer by email
	 * @param  string $email
	 * @return mixed|json string
	 */
	public function get_customer_by_email( $email ) {
		return $this->_make_api_call( 'customers/email/' . $email );
	}

	/**
	 * Get the total customer count
	 * @return mixed|json string
	 */
	public function get_customers_count() {
		return $this->_make_api_call( 'customers/count' );
	}

	/**
	 * Get the customer's orders
	 * @param  integer $customer_id
	 * @return mixed|json string
	 */
	public function get_customer_orders( $customer_id ) {
		return $this->_make_api_call( 'customers/' . $customer_id . '/orders' );
	}

	/**
	 * Get all the products
	 * @param  array  $params
	 * @return mixed|json string
	 */
	public function get_products( $sku, $params = array() ) {
		return $this->_make_api_call( 'products/sku/'.$sku, $params );
	}

	/**
	 * Get a single product
	 * @param  integer $product_id
	 * @return mixed|json string
	 */
	public function get_product( $product_id ) {
		return $this->_make_api_call( 'products/' . $product_id );
	}



	public function mudar_estoque_produto( $product_id, $params = array(),$method = 'PUT'  ) {
		return $this->_make_api_call( 'products/' . $product_id, $params,$method );
	}

	/**
	 * Get the total product count
	 * @return mixed|json string
	 */
	public function get_products_count() {
		return $this->_make_api_call( 'products' );
	}

	/**
	 * Get reviews for a product
	 * @param  integer $product_id
	 * @return mixed|json string
	 */
	public function get_product_reviews( $product_id ) {
		return $this->_make_api_call( 'products/' . $product_id . '/reviews' );
	}

	/**
	 * Get reports
	 * @param  array  $params
	 * @return mixed|json string
	 */
	public function get_reports( $params = array() ) {
		return $this->_make_api_call( 'reports', $params );
	}

	/**
	 * Get the sales report
	 * @param  array  $params
	 * @return mixed|json string
	 */
	public function get_sales_report( $params = array() ) {
		return $this->_make_api_call( 'reports/sales', $params );
	}

	/**
	 * Get the top sellers report
	 * @param  array  $params
	 * @return mixed|json string
	 */
	public function get_top_sellers_report( $params = array() ) {
		return $this->_make_api_call( 'reports/sales/top_sellers', $params );
	}

	/**
	 * Run a custom endpoint call, for when you extended the API with your own endpoints
	 * @param  string $endpoint
	 * @param  array  $params
	 * @param  string $method
	 * @return mixed|json string
	 */
	public function make_custom_endpoint_call( $endpoint, $params = array(), $method = 'GET' ) {
		return $this->_make_api_call( $endpoint, $params, $method );
	}

	/**
	 * Set the consumer key
	 * @param string $consumer_key
	 */
	public function set_consumer_key( $consumer_key ) {
		$this->_consumer_key = $consumer_key;
	}

	/**
	 * Set the consumer secret
	 * @param string $consumer_secret
	 */
	public function set_consumer_secret( $consumer_secret ) {
		$this->_consumer_secret = $consumer_secret;
	}

	/**
	 * Set SSL variable
	 * @param boolean $is_ssl
	 */
	public function set_is_ssl( $is_ssl ) {
		if ( $is_ssl == '' ) {
			if ( strtolower( substr( $this->_api_url, 0, 5 ) ) == 'https' ) {
				$this->_is_ssl = true;
			} else $this->_is_ssl = false;
		} else $this->_is_ssl = $is_ssl;
	}

	/**
	 * Set the return data as object
	 * @param boolean $is_object
	 */
	public function set_return_as_object( $is_object = true ) {
		$this->_return_as_object = $is_object;
	}

	/**
	 * Make the call to the API
	 * @param  string $endpoint
	 * @param  array  $params
	 * @param  string $method
	 * @return mixed|json string
	 */
	private function _make_api_call( $endpoint, $params = array(), $method = 'GET' ) {
		$ch = curl_init();


    	curl_setopt( $ch, CURLOPT_USERPWD, $this->_consumer_key . ":" . $this->_consumer_secret );
		
		if ( isset( $params ) && is_array( $params ) ) {
			$paramString = '?' ;
		} else {
			$paramString = null;
		}



$wandson = 'consumer_key='.$this->_consumer_key . '&consumer_secret=' . $this->_consumer_secret; 

// echo json_encode( $params );
// die();
// echo $this->_api_url . $endpoint . $paramString . $wandson;
// die();



		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

		// Set up the enpoint URL
		curl_setopt( $ch, CURLOPT_URL, $this->_api_url . $endpoint . $paramString . $wandson );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 0 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        if ( 'PUT' === $method ) {
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $params ) );
    	} else if ( 'POST' === $method ) {
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $params ) );
    	} else if ( 'DELETE' === $method ) {
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );
    	}


		$return = curl_exec( $ch );


		$err = curl_error($ch);


		
		$code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

		if ( $this->_return_as_object ) {
			$return = json_decode( $return );
		}

		if ( empty( $return ) ) {
			$return = '{"errors":[{"code":"' . $code . '","message":"cURL HTTP error ' . $code . '"}]}';
			$return = json_decode( $return );
		}
		return $return;
	}

	/**
	 * Generate oAuth signature
	 * @param  array  $params
	 * @param  string $http_method
	 * @param  string $endpoint
	 * @return string
	 */
	public function generate_oauth_signature( $params, $http_method, $endpoint ) {
		$base_request_uri = rawurlencode( $this->_api_url . $endpoint );

		// normalize parameter key/values and sort them
		$params = $this->normalize_parameters( $params );
		uksort( $params, 'strcmp' );

		// form query string
		$query_params = array();
		foreach ( $params as $param_key => $param_value ) {
			$query_params[] = $param_key . '%3D' . $param_value; // join with equals sign
		}

		$query_string = implode( '%26', $query_params ); // join with ampersand

		// form string to sign (first key)
		$string_to_sign = $http_method . '&' . $base_request_uri . '&' . $query_string;

		return base64_encode( hash_hmac( self::HASH_ALGORITHM, $string_to_sign, $this->_consumer_secret, true ) );
	}

	/**
	 * Normalize each parameter by assuming each parameter may have already been
	 * encoded, so attempt to decode, and then re-encode according to RFC 3986
	 *
	 * Note both the key and value is normalized so a filter param like:
	 *
	 * 'filter[period]' => 'week'
	 *
	 * is encoded to:
	 *
	 * 'filter%5Bperiod%5D' => 'week'
	 *
	 * This conforms to the OAuth 1.0a spec which indicates the entire query string
	 * should be URL encoded
	 *
	 * @since 0.3.1
	 * @see rawurlencode()
	 * @param array $parameters un-normalized pararmeters
	 * @return array normalized parameters
	 */
	private function normalize_parameters( $parameters ) {

		$normalized_parameters = array();

		foreach ( $parameters as $key => $value ) 
		{

			
				// if ($key == 'product')
				// {
				// 	$value = json_encode($value);
				// }	
				

				$key   = str_replace( '%', '%25', rawurlencode( rawurldecode( $key ) ) );
				$value = str_replace( '%', '%25', rawurlencode( rawurldecode( $value ) ) );
				
				$normalized_parameters[ $key ] = $value;
			
			
		}



		return $normalized_parameters;
	}

}

/* End of file WooCommerce.php */