<?php

class JsonRpcService {
	const ANNOTATION = '@JsonRpcMethod';
	public function getCallableMethodNames() {
		$methodNames = array();
		$reflection = new ReflectionClass($this);
		foreach($reflection->getMethods() as $method) {
			if($this->isJsonRpcMethod($method)) {
				$methodNames[$method->name] = $method->getParameters();
			}
		}
		return $methodNames;
	}
	public function getCallableMethodParameters($methodName) {
		$reflection = new ReflectionClass($this);
		foreach($reflection->getMethods() as $method) {
			if($method->name == $methodName) {
				return $method->getParameters();
			}
		}
	}
	protected function isJsonRpcMethod($method) {
		if(strstr($method->getDocComment(),JsonRpcService::ANNOTATION)) {
			return true;
		}
		return false;
	}
}
?>