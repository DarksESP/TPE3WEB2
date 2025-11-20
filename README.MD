# 🎮 API REST - Gestión de Juegos

## 📘 Descripción
Esta API permite administrar un catálogo de videojuegos, incluyendo sus consolas asociadas.  
Se puede **listar, agregar, editar y eliminar juegos**, además de obtener información **filtrada o paginada**.

La API fue desarrollada en **PHP** con arquitectura **MVC** y conexión a **MySQL**, siguiendo los principios **RESTful**.

---

## 🚀 Endpoints principales

### 🔹 Juegos

| Método | Endpoint | Descripción | Códigos de respuesta |
|--------|----------|-------------|--------------------|
| GET    | /login |Permite al usuario registrarse con un usuario; y si son correctos los datos, se retorna un token para acceder a las funcionalidades POST, DELETE y PUT |   200,  401 |
| GET    | /juegos  | Devuelve todos los juegos (puede incluir paginación, orden y filtros). | 200, 400 |
| GET    | /juegos/:id | Devuelve un juego específico por su ID. | 200, 404 |
| POST   | /juegos  | Crea un nuevo juego (requiere nombre, consola, género, etc). | 201, 400 |
| PUT    | /juegos/:id | Actualiza un juego existente. | 200, 400, 404 |
| DELETE | /juegos/:id | Elimina un juego de la base de datos. | 200, 404 

📝 Ejemplo de JSON para Crear o Actualizar un Juego (POST / PUT)

Este es el formato esperado por la API para enviar los datos de un juego en los endpoints POST y PUT:

{ 

  "nombre": "The Witcher 3",

  "id_consola": 33,

  "genero": "rpg",

  "descripcion": "Descripción del juego...",

  "imagen": "ruta_o_url_de_imagen.jpg",

  "audio_url": "ruta_o_url_de_audio.mp3"
  
}


### 🔸 Parámetros opcionales en `/juegos`

**Parámetros disponibles:**
- `pagina` → número de página (por defecto 1)
- `limite` → cantidad de resultados por página (por defecto 5)
- `orderBy` → campo para ordenar (`nombre`, `genero`, `id_consola`, `id`)
- `order` → dirección (`asc` o `desc`)
- `genero` → filtra los juegos por género

Podés combinar varios filtros en un solo pedido:

GET api/juegos?pagina=1&limite=5&orderBy=nombre&order=asc&genero=accion

    api/juegos?order=asc&&orderBy=id&&genero=rpg&&pagina=1&&limite=1

