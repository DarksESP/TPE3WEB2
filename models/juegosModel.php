<?php

class juegosModel {
    private $db;

    function __construct() {
     // 1. abro conexión con la DB
    $this->db = new PDO('mysql:host=localhost;dbname=tp_tienda_videojuegos;charset=utf8', 'root', '');
    }

    public function get($id) {
        $query = $this->db->prepare('SELECT * FROM juego WHERE id = ?');
        $query->execute([$id]);
        $juego = $query->fetch(PDO::FETCH_OBJ);

        return $juego;
    }
 

public function getJuegos($genero = null, $campo = null, $order = 'ASC', $pagina = 1, $limite = 10) {
    $sql = "SELECT * FROM juego";
    $params = [];

    // Si se filtra por género
    if ($genero) {
        $sql .= " WHERE LOWER(genero) = ?";
        $params[] = strtolower($genero);
    }

    // Si se pide ordenamiento
    $orden = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
    if ($campo ) {
        $sql .= " ORDER BY $campo $orden";
    }

    // Agregar paginado
    $offset = ($pagina - 1) * $limite;
    $sql .= " LIMIT ? OFFSET ?";

    $query = $this->db->prepare($sql);

    // Bind dinámico
    $i = 1;
    foreach ($params as $p) {
        $query->bindValue($i++, $p);
    }

    $query->bindValue($i++, (int)$limite, PDO::PARAM_INT);
    $query->bindValue($i, (int)$offset, PDO::PARAM_INT);

    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
}

    
    function removeJuego($id) {
        $query = $this->db->prepare('DELETE from juego where id = ?');
        $query->execute([$id]);
    }

    function insert($nombre, $genero, $id_consola, $descripcion, $imagen, $audio_url) {
         $nombreN = strtolower($nombre);
        $generoN = strtolower($genero);
        $query = $this->db->prepare("INSERT INTO juego(nombre, genero, id_consola, descripcion, imagen, audio_url) VALUES(?,?,?, ?, ?,?)");
        $query->execute([$nombreN, $generoN, $id_consola, $descripcion, $imagen, $audio_url]);

        // var_dump($query->errorInfo());

        return $this->db->lastInsertId();
    }

      function update($id, $nombre, $genero, $id_consola) {
        $nombreN = strtolower($nombre);
        $generoN = strtolower($genero);
        $query = $this->db->prepare("
            UPDATE juego
            SET nombre = ?, genero = ?, id_consola = ?
            WHERE id = ?
        ");

        $query->execute([$nombreN, $generoN, $id_consola, $id]);
    }
  }