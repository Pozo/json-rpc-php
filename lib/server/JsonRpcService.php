<?php

class JsonRpcService {
	const ANNOTATION = '@JsonRpcMethod';
	const REQUEST_LOGIN = 'request_login';

    public function __construct() {
        session_start();
    }
    public function __destruct() {
        session_write_close();
    }
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
	public function isAuthenticationRequired($request,$service){
		$annotationVariables = $this->getAnnotationVariables($request,$service);
		return (empty($annotationVariables))?false:$annotationVariables[JsonRpcService::REQUEST_LOGIN];
	}
	private function getAnnotationVariables($request,$service) {
		$collectedAnnotations = array();
		$method = new ReflectionMethod(get_class($service),$request->method);
		$methodComment = $method->getDocComment();

		$bracketContentStart = strpos($methodComment,'(');
		$bracketContentEnd = strpos($methodComment,')')-$bracketContentStart-1;

		// () pair not found
		if(!$bracketContentStart || !$bracketContentEnd) {
			return $collectedAnnotations;
		}

		$rawAnnotations = substr($methodComment,$bracketContentStart+1,$bracketContentEnd);
		$annotationsArray = explode(',',$rawAnnotations);
		foreach($annotationsArray as $key => $value) {
				$withoutUnecessaryCharacters = str_replace(
					array(
					     ' ', # additional space
					     '*', # an automate generated * character @ block comments when you hit enter
					     '\'',# remove duplicating
					     '"'),# remove duplicating
					'',$value);

				$keyValuePair = explode('=',$withoutUnecessaryCharacters);
				if(count($keyValuePair)!=2) {
					return false;
				} else {
					$collectedAnnotations[$keyValuePair[0]] = $keyValuePair[1];
				}
		}
        return $collectedAnnotations;
	}
	/** @JsonRpcMethod*/
	public function authenticate($username,$password) {
		if($username == 'test' && $password == '1234') {
            $_SESSION['RPC']['authenticated'] = true;
			return true;
		} else {
			return false;
		}
	}
	/** @JsonRpcMethod*/
	public function invalidateSession() {
        session_destroy();
	}
}
?>