<?php 
	
	/**
	  * @author Zhernosek Andrei <zhernosek12@gmail.com>
	  *
	  * Thanks for https://gist.github.com/badmofo/d109fbc447fea64d99e5ca58bdf53d7b#file-coinlist-py
	  */
  
	class CoinList {
		
		function __construct($access_key, $access_secret, $endpoint_url='https://trade-api.coinlist.co') {
			$this->access_key = $access_key;
			$this->access_secret = $access_secret;
			$this->endpoint_url = $endpoint_url;
		}
		
		function sha265hmac($data, $key) {
			$h = hash_hmac('sha256', $data, $key, true);
			return base64_encode($h);
		}
		
		function request($method, $path, $params=[], $body=[]) {
			$timestamp = time();
			$path_with_params = $path . ((count($params) == 0) ? "" : "?" . http_build_query($params));
			$json_body = trim(json_encode($body));
			
			$message = $timestamp . $method . $path_with_params . ((count($body) == 0) ? '' : $json_body);
			
			$secret = base64_decode($this->access_secret);
			$signature = $this->sha265hmac($message, $secret);
			
			$headers = array(
				'Content-Type: application/json',
				'CL-ACCESS-KEY:'.$this->access_key,
				'CL-ACCESS-SIG:'.$signature,
				'CL-ACCESS-TIMESTAMP:'.$timestamp
			);
			$url = $this->endpoint_url . $path_with_params;
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => $method,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_POSTFIELDS => http_build_query($params),
			));
			
			$response = curl_exec($curl);
			curl_close($curl);
			
			return json_decode($response, true);
		}
	}

?>