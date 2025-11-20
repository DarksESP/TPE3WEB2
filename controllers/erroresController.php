<?php
class erroresController {
    public function error400() {
        header("HTTP/1.1 400 Bad Request");
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["error" => "Ruta o método inválido. 400"]);
    }

}

?>
