<?php
function __autoload($className) {
	include_once($className.".php");
}
class JsonRpcClient {
	private $_rpcUrl;

	public function __construct($rpcUrl) {
		$this->_rpcUrl = $rpcUrl;
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
		if($rpcRequestList instanceof ObjectList) {
			$rpcBatchArray = array();
			foreach($rpcRequestList as $rpcRequest) {
				if($rpcRequest instanceof RpcRequest) {
					array_push($rpcBatchArray, $rpcRequest->getRpcRequestObject());
				}
			}
			return $this->httpRequest($rpcBatchArray);
		}
	}
	private function httpRequest($rpcBatchArray) {
		$curlHandler = curl_init();
		$curlOptions = array(
			CURLOPT_URL => $this->_rpcUrl,
			CURLOPT_POST => true,
			CURLINFO_CONTENT_TYPE => "application/json",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => json_encode($rpcBatchArray)
		);
		curl_setopt_array($curlHandler,$curlOptions);
		
		$response = curl_exec($curlHandler);
		curl_close($curlHandler);
		return json_decode($response);
	}
}
?>