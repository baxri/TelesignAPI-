<?php

class TeleSign{

	private $api_key_base_64 = 'yvPPGgvPbYfBI65juyXVkNoI0lSFa82K4YxzgbTOoYl4UXPn/WFY6KZzPBTjAtGk7TwEfPUgoXGlHv7xVUh31Q==';
	private $customer_id = '6470CD34-7794-4543-A70F-AFBD04DF53C5';
	private $rest_url = 'https://rest.telesign.com/v1/verify/sms';
	private $rest_uri = '/v1/verify/sms';

	public function sendSMS( $code = '' ){

		$date = date_create(gmdate('D, d M Y H:i:s T', time()), timezone_open('Etc/GMT+0'));	
		$curl = curl_init($this->rest_url);

		date_timezone_set($date, timezone_open('Etc/GMT-4'));
		$date = date_format($date, 'D, d M Y H:i:s +0400 ');

		echo $date;

		$curl_post_data = array(       
			"phone_number" => '995598602084',
			"language" => 'en-US',
			"verify_code" => $code,
      	);     

		$headers = array(			
			"Authorization: {$this->AutorizationKey( $date, $curl_post_data )}",
			"Content-Type: application/x-www-form-urlencoded",
			"Date: ".$date		
		);


		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);		
	    //curl_setopt($curl, CURLOPT_HEADER, 1);
		//curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt( $curl, CURLOPT_POST, true);
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl,  CURLOPT_POSTFIELDS, $curl_post_data);

		$curl_response = curl_exec($curl);
		$info = curl_getinfo($curl);
		$error = curl_error($curl);
		curl_close($curl);

		if( !empty($curl_response) ){
			$curl_response = json_decode($curl_response);
		}

		echo '<pre>';
		print_r($curl_response);
		die;

	}

	private function AutorizationKey( $date, $post_data ){
		
		foreach ( $post_data as $key => $value ) {
			static $post_string;
			$post_string .= $key;
			$post_string .= '=';
			$post_string .= urlencode($value);
			$post_string .= '&';
		}

		$post_string = substr( $post_string, 0, strlen( $post_string ) - 1 );
		
		echo '<pre>';
		echo 'StringToSign'."\n";
		//Generate StringToSign string
		$StringToSign = 'POST'."\n"
						.'application/x-www-form-urlencoded'."\n"
						.$date."\n"
						.$post_string."\n"
						.$this->rest_uri						
						;

		print_r($StringToSign);
		echo "\n".'--------------------------------------'."\n\n\n";
		
		//UTF-8 Encoding
		$StringToSign = utf8_encode($StringToSign);

		echo 'UTF8 Encoded StringToSign'."\n";		
		print_r($StringToSign);
		echo "\n".'--------------------------------------'."\n\n\n";
		echo 'Base 64 decoded api key'."\n";
		$key = base64_decode($this->api_key_base_64);
		echo $key;

		//echo "\n".'--------------------------------------'."\n\n\n";
		//echo 'Base 65 decoded api key With UTF-8 Encoded WICH I AM USING'."\n";
		//$key = utf8_encode($key);
		//echo $key;

		echo "\n".'--------------------------------------'."\n\n\n";
		echo 'Signature With hash_hmac(sha1, StringToSign, api_key )'."\n";

		//Hashing the Signature with sha1 algorithm 
		$signature = hash_hmac('sha1', $StringToSign, $key, true );

		echo '<pre>';
		print_r($signature);
		
		echo "\n".'--------------------------------------'."\n\n\n";
		echo 'Signature Base 64 encoded'."\n";
		//Base 65 Encoding
		$signature .= base64_encode($signature);
		echo $signature;

		echo "\n".'--------------------------------------'."\n\n\n";
		echo 'Autorization Key'."\n";
		$autorization = "TSA ".$this->customer_id.":".$signature;
		echo $autorization;
		
		return $autorization;
	}
}