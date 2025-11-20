<?php

class UserModel {
    private $db;

    function __construct() {
     // 1. abro conexión con la DB
       $this->db = new PDO('mysql:host=localhost;dbname=tp_tienda_videojuegos;charset=utf8', 'root', '');
    }

    public function get($id) {
        $query = $this->db->prepare('SELECT * FROM usuario WHERE id = ?');
        $query->execute([$id]);
        $user = $query->fetch(PDO::FETCH_OBJ);

        return $user;
    }

    public function getByNombre($nombre) {
        $query = $this->db->prepare('SELECT * FROM usuario WHERE nombre = ?');
        $query->execute([$nombre]);
        $user = $query->fetch(PDO::FETCH_OBJ);

        return $user;
    }
    
    public function getAll() {
        // 2. ejecuto la consulta SQL (SELECT * FROM tareas)
        $query = $this->db->prepare('SELECT * FROM usuario');
        $query->execute();

        // 3. obtengo los resultados de la consulta
        $users = $query->fetchAll(PDO::FETCH_OBJ);

        return $users;
    }

    function insert($name, $password) {
        $query = $this->db->prepare("INSERT INTO usuario(nombre, passwordd) VALUES(?,?)");
        $query->execute([$name, $password]);

        // var_dump($query->errorInfo());

        return $this->db->lastInsertId();
    }
}
