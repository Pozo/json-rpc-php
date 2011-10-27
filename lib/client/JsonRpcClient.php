<?php
function __autoload($className) {
	include_once($className.".php");
}
class JsonRpcClient {
	private $_rpcUrl;
	private $_curlCookie;

	public function __construct($rpcUrl) {
		$this->_rpcUrl = $rpcUrl;
		$this->_curlCookie = dirname(__FILE__).DIRECTORY_SEPARATOR.'curl_cookie';
	}
	public function __call($name, $arguments) {
		return $this->call(new RpcRequest($name,$arguments));
	}
	public function call($rpcRequest) {
		if($rpcRequest instanceof RpcRequest) {
			return $this->httpRequest($rpcRequest->getRpcRequestObject());
		}
	}
	public function callBatch($rpcRequestList) {
        $rpcBatchArray = array();
        foreach($rpcRequestList as $rpcRequest) {
            if($rpcRequest instanceof RpcRequest) {
                array_push($rpcBatchArray, $rpcRequest->getRpcRequestObject());
            }
        }
        return $this->httpRequest($rpcBatchArray);
	}
	private function httpRequest($rpcBatchArray) {
		$curlHandler = curl_init();
		$curlOptions = array(
			CURLOPT_URL => $this->_rpcUrl,
			// cookie stuff
			CURLOPT_COOKIE => true,
            CURLOPT_COOKIEFILE => $this->_curlCookie,
            CURLOPT_COOKIEJAR => $this->_curlCookie,
			// request specific stuff
			CURLINFO_CONTENT_TYPE => "application/json",
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode($rpcBatchArray),
			CURLOPT_RETURNTRANSFER => true

		);
		curl_setopt_array($curlHandler,$curlOptions);
		
		$response = curl_exec($curlHandler);
		curl_close($curlHandler);
		return json_decode($response);
	}
}
?>