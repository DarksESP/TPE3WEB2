<?php



class consolaModel {
      private $db;

    function __construct() {
     // 1. abro conexión con la DB
    $this->db = new PDO('mysql:host=localhost;dbname=tp_tienda_videojuegos;charset=utf8', 'root', '');
    }


    public function getConsolaByID($id) {
        $query = $this->db->prepare("SELECT * FROM consola WHERE id = ?");
        $query->execute ([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

}
?>