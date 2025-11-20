<?php
require_once './models/juegosModel.php';
require_once './models/consolaModel.php';


class juegosController
{
    private $modelJuegos;
    private $modelConsola;

    public function __construct()
    {
        $this->modelJuegos = new juegosModel();
        $this->modelConsola = new consolaModel();

        // no hay vista en la API REST
    }

    public function getJuegos($req, $res)
    {

    if (
    (isset($_GET['pagina']) && !is_numeric($_GET['pagina'])) ||
    (isset($_GET['limite']) && !is_numeric($_GET['limite']))
) {
    return $res->json("Los parámetros 'pagina' y/o 'limite' deben ser valores enteros.", 400);
}

         $pagina = $_GET['pagina'] ?? 1;
         $limite = $_GET['limite'] ?? 5;
        if ($pagina <=0) {
            return $res->json("El valor de página debe ser mayor a 0", 400);
        }
           if ($limite <=0) {
            return $res->json("El valor de limite debe ser mayor a 0");
        }
         //Ver si el order fue ingresado
         $order   = isset($_GET['order']) ? strtolower($_GET['order']) : 'asc';
         
         $genero = isset($_GET['genero']) ? $_GET['genero'] : null;

     if ($genero !== null && !is_string($genero)) {
         return $res->json("El parámetro 'genero' debe ser una cadena de texto.", 400);
       }
         $genero  = isset($_GET['genero']) ? $_GET['genero'] : null;
         
         // Ver si el cliente pasó "orderBy"
         $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : null;
         $camposValidos = ['nombre', 'genero', 'id_consola', 'id'];
         

  
         // Validaciones
        if ($orderBy && !in_array($orderBy, $camposValidos)) {
            return $res->json("NO INDICO UN PARAMETRO CORRECTO", 400);
        }

        if (!in_array($order, ['asc', 'desc'])) {
           return $res->json("NO INDICO UN ORDEN CORRECTO", 400);
        }

       $juegos = $this->modelJuegos->getJuegos($genero, $orderBy, $order, $pagina, $limite);

        return $res->json ($juegos, 200);

    }

    public function getJuego($req, $res)
    {
        // obtengo el ID que viene como parámetro del endpoint
        $idJuego = $req->params->id;

        $juego = $this->modelJuegos->get($idJuego);

        if (!$juego) {
            return $res->json("El juego con el id=$idJuego no existe", 404);
        }

        return $res->json($juego);
    }

    public function deleteJuego($req, $res)
    {
        $idJuego = $req->params->id;
        $juego = $this->modelJuegos->get($idJuego);


        if (!$juego) {
            return $res->json("El juego con el id=$idJuego no existe", 404);
        }
     

        $this->modelJuegos->removeJuego($idJuego);

        return $res->json("Eliminado: " + $juego, 200);
    }

    public function mostrarError400($req, $res)
    {

        return $res->json("INGRESE CORRECTAMENTE LOS DATOS", 400);

    }

    public function insertJuego($req, $res)
    {
        // Valida que vengan todos los datos necesarios en el body
        // Si falta alguno, devolvemos un error 400 (Bad Request)
        if (empty($req->body->nombre) || empty($req->body->genero) || empty($req->body->id_consola) || empty($req->body->descripcion) || empty($req->body->imagen) || empty ($req->body->audio_url)) {
            return $res->json('Faltan datos', 400);
        }

        $nombre = $req->body->nombre;
        $genero = $req->body->genero;
        $id_consola = $req->body->id_consola;
        $descripcion= $req->body->descripcion;
        $imagen = $req->body->imagen;
        $audio = $req->body->audio_url;
        if (!empty($this->modelConsola->getConsolaByID($id_consola))) {



            // inserta la nueva tarea
            $newJuegoId = $this->modelJuegos->insert($nombre, $genero, $id_consola, $descripcion, $imagen, $audio);

            // si el modelo devuelve false, algo falló al guardar (por ejemplo, error en la base de datos)
            if ($newJuegoId == false) {
                return $res->json('Error del servidor', 500);
            }

            // se considera una buena práctica devolver la entidad creada que contiene
            // todos los datos que el modelo agregó automaticamente
            $newJuego = $this->modelJuegos->get($newJuegoId);
            return $res->json($newJuego, 201);
        } else {
            return $res->json("NO EXISTE UNA CONSOLA CON ÉSE ID", 400);
        }
    }


    public function updateJuego($req, $res)
    {
        $idJuego = $req->params->id;
        $juego = $this->modelJuegos->get($idJuego);

        if (!$juego) {
            return $res->json("El juego con el id=$idJuego no existe", 404);
        }

        if (
            empty($req->body->nombre) ||
            empty($req->body->genero) ||
            empty($req->body->id_consola ||
             empty($req->body->imagen) ||
             empty ($req->body->audio_url))
        ) {
            // En una petición PUT se deben enviar todos los campos de la tarea.
            // Si solo queremos modificar algunos, el método correcto sería PATCH.
            return $res->json('Faltan datos', 400);
        }

        $nombre = $req->body->nombre;
        $genero = $req->body->genero;
        $id_consola = $req->body->id_consola;


        if (!empty($this->modelConsola->getConsolaByID($id_consola))) {

            $this->modelJuegos->update($idJuego, $nombre, $genero, $id_consola);

            $updatedJuego = $this->modelJuegos->get($idJuego);
            return $res->json($updatedJuego, 200);
        } else {
            return $res->json("NO EXISTE UNA CONSOLA CON EL ID: $id_consola", 400);
        }


    }
}


