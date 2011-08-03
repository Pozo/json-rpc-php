<?php

class RpcRequest {
	const VERSION = "2.0";
	private $_methodName;
	private $_params;
	private $_isNotifiCation;
	private static $_id = 0;
	
	private $request;

	public function __construct($methodName, $params = null, $isNotification = false) {
		$this->_methodName = $methodName;
		$this->_params = $params;
		$this->_isNotifiCation = $isNotification;
	}
	public function setMethodName($methodName) {
		$this->_methodName = $methodName;
	}
	public function setParams($params) {
		$this->_params = $params;
	}
	public function setIsNotifiCation($isNotifiCation) {
		$this->_isNotifiCation = $isNotifiCation;
	}
	public function getRpcRequestObject() {
		$this->buildRequestObject();
		return $this->request;
	}
	private function buildRequestObject() {
		$this->request = new stdClass();
		$this->setRequestVersion();
		$this->setRequestMethod();
		$this->setRequestParams();
		$this->setRequestId();
	}
	private function setRequestVersion() {
		$this->request->jsonrpc = RpcRequest::VERSION;
	}
	private function setRequestMethod() {
		if(!is_null($this->_methodName)) {
			$this->request->method = $this->_methodName;
		}
	}
	private function setRequestParams() {
		if(!is_null($this->_params)) {
			$this->request->params = $this->_params;
		}
	}
	private function setRequestId() {
		if(!$this->_isNotifiCation) {
			$this->request->id = $this->getId();
		}
	}
	private function getId() {
		return RpcRequest::$_id++;
	}
}
?>