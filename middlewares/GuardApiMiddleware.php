<?php

    class GuardMiddleware extends Middleware {
        public function run($request, $response) {
            if(!$request->user) {
                header("WWW-Authenticate: Bearer realm='Access to the API'");
                return $response->json("No autorizado", 401);
            }
          
        }
    }
?>