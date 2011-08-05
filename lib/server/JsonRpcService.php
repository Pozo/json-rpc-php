<?php

class JsonRpcService {
	const ANNOTATION = '@JsonRpcMethod';
	const REQUEST_LOGIN = 'request_login';
	
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
	protected function isAuthenticationRequired(){
		$annotationVariables = $this->collectedAnnotations();
		return $annotationVariables[JsonRpcService::REQUEST_LOGIN];
	}
	private function getAnnotationVariables($method) {
		$collectedAnnotations = array();
		$methodComment = $method->getDocComment();
		$bracketContentStart = strpos($methodComment,'(');
		$bracketContentEnd = strpos($methodComment,')')-$bracketContentStart-1;
		// () pair not found
		if(!$bracketContentStart || !$bracketContentEnd) {
			return false;
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

}
?>