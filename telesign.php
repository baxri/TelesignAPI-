<?php


class TeleSign{
	private $api_key_base_65 = 'yvPPGgvPbYfBI65juyXVkNoI0lSFa82K4YxzgbTOoYl4UXPn/WFY6KZzPBTjAtGk7TwEfPUgoXGlHv7xVUh31Q==';
	private $customer_id = '6470CD34-7794-4543-A70F-AFBD04DF53C5';
	private $rest_url = 'https://rest.telesign.com/v1/verify/sms';

	public function sendSMS( $code = '' ){

		$curl = curl_init($this->rest_url);

		$headers = array(
			"Authorization: {$this->AutorizationKey()}",
			"Content-Type: application/x-www-form-urlencoded",
			"Date: ".gmdate('D, d M Y H:i:s T', time())
			
		);

		$curl_post_data = array(       
			"phone_number" => '+995598602084',
			"language" => 'en-US',
			"verify_code" => $code,
      	);     
		


		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);		
	   // curl_setopt($curl, CURLOPT_HEADER, 1);
		//curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt( $curl, CURLOPT_POST, true);
		curl_setopt( $curl,  CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt( $curl,  CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);

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

	private function AutorizationKey(){
		return "TSA ".$this->customer_id.":".$this->api_key_base_65;
	}
}