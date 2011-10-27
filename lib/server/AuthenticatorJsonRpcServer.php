<?php

class AuthenticatorJsonRpcServer extends JsonRpcServer {
	private function isAuthenticatedRequest() {
		return isset($_SESSION['RPC']['authenticated']);
	}
	protected function isMethodAvailable($requestObject) {
		$ownerService = parent::isMethodAvailable($requestObject);

		if($ownerService->isAuthenticationRequired($requestObject,$ownerService)) {
            if($this->isAuthenticatedRequest()) {
                return $ownerService;
            } else {
                throw new Exception("Authentication required");
            }
		} else {
			return $ownerService;
		}
	}
}
?>
