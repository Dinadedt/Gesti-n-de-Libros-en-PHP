<?php
// Definición de la clase Autor
class Autor {
    private $id;
    private $nombre;
    private $nacionalidad;

    public function __construct($id, $nombre, $nacionalidad) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->nacionalidad = $nacionalidad;
    }

    // Getters y setters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getNacionalidad() { return $this->nacionalidad; }
}

// Definición de la clase Categoria
class Categoria {
    private $id;
    private $nombre;
    private $descripcion;

    public function __construct($id, $nombre, $descripcion) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    // Getters y setters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
}

// Definición de la clase Libro
class Libro {
    private $id;
    private $titulo;
    private $autor;
    private $categoria;
    private $isbn;
    private $estado; // disponible, prestado, reservado

    public function __construct($id, $titulo, Autor $autor, Categoria $categoria, $isbn) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->categoria = $categoria;
        $this->isbn = $isbn;
        $this->estado = 'disponible';
    }

    // Métodos para gestionar el estado del libro
    public function prestar() {
        if ($this->estado === 'disponible') {
            $this->estado = 'prestado';
            return true;
        }
        return false;
    }

    public function devolver() {
        $this->estado = 'disponible';
    }

    // Getters y setters
    public function getId() { return $this->id; }
    public function getTitulo() { return $this->titulo; }
    public function getAutor() { return $this->autor; }
    public function getCategoria() { return $this->categoria; }
    public function getIsbn() { return $this->isbn; }
    public function getEstado() { return $this->estado; }
}

// Definición de la clase Prestamo
class Prestamo {
    private $id;
    private $libro;
    private $fechaPrestamo;
    private $fechaDevolucion;

    public function __construct($id, Libro $libro, $fechaPrestamo, $fechaDevolucion = null) {
        $this->id = $id;
        $this->libro = $libro;
        $this->fechaPrestamo = $fechaPrestamo;
        $this->fechaDevolucion = $fechaDevolucion;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getLibro() { return $this->libro; }
    public function getFechaPrestamo() { return $this->fechaPrestamo; }
    public function getFechaDevolucion() { return $this->fechaDevolucion; }
}

// Definición de la clase Biblioteca
class Biblioteca {
    private $libros = [];
    private $autores = [];
    private $categorias = [];
    private $prestamos = [];

    // Métodos para agregar elementos
    public function agregarLibro(Libro $libro) {
        $this->libros[] = $libro;
    }

    public function agregarAutor(Autor $autor) {
        $this->autores[] = $autor;
    }

    public function agregarCategoria(Categoria $categoria) {
        $this->categorias[] = $categoria;
    }

    // Métodos de búsqueda
    public function buscarLibroPorTitulo($titulo) {
        return array_filter($this->libros, function($libro) use ($titulo) {
            return stripos($libro->getTitulo(), $titulo) !== false;
        });
    }

    public function buscarLibroPorAutor($nombreAutor) {
        return array_filter($this->libros, function($libro) use ($nombreAutor) {
            return stripos($libro->getAutor()->getNombre(), $nombreAutor) !== false;
        });
    }

    public function buscarLibroPorCategoria($nombreCategoria) {
        return array_filter($this->libros, function($libro) use ($nombreCategoria) {
            return stripos($libro->getCategoria()->getNombre(), $nombreCategoria) !== false;
        });
    }

    // Método de préstamo
    public function prestarLibro(Libro $libro) {
        if ($libro->prestar()) {
            $prestamo = new Prestamo(
                count($this->prestamos) + 1, 
                $libro, 
                date('Y-m-d')
            );
            $this->prestamos[] = $prestamo;
            return $prestamo;
        }
        return null;
    }

    // Métodos para obtener listas
    public function getLibros() { return $this->libros; }
    public function getAutores() { return $this->autores; }
    public function getCategorias() { return $this->categorias; }
    public function getPrestamos() { return $this->prestamos; }
}

// Ejemplo de uso
function ejemploDeUso() {
    // Crear una biblioteca
    $biblioteca = new Biblioteca();

    // Crear autores
    $autor1 = new Autor(1, 'Gabriel García Márquez', 'Colombiano');
    $autor2 = new Autor(2, 'Jorge Luis Borges', 'Argentino');
    $biblioteca->agregarAutor($autor1);
    $biblioteca->agregarAutor($autor2);

    // Crear categorías
    $categoria1 = new Categoria(1, 'Ficción', 'Libros de ficción literaria');
    $categoria2 = new Categoria(2, 'Realismo Mágico', 'Obras de realismo mágico');
    $biblioteca->agregarCategoria($categoria1);
    $biblioteca->agregarCategoria($categoria2);

    // Crear libros
    $libro1 = new Libro(1, 'Cien Años de Soledad', $autor1, $categoria2, '9780060883287');
    $libro2 = new Libro(2, 'Ficciones', $autor2, $categoria1, '9788432227929');
    $biblioteca->agregarLibro($libro1);
    $biblioteca->agregarLibro($libro2);

    // Buscar libros
    $librosPorTitulo = $biblioteca->buscarLibroPorTitulo('Cien');
    $librosPorAutor = $biblioteca->buscarLibroPorAutor('Borges');

    // Realizar un préstamo
    $prestamo = $biblioteca->prestarLibro($libro1);

    // Imprimir resultados (en un escenario real, esto se haría con una interfaz de usuario)
    echo "Libros encontrados por título:\n";
    foreach ($librosPorTitulo as $libro) {
        echo $libro->getTitulo() . "\n";
    }

    echo "\nLibros encontrados por autor:\n";
    foreach ($librosPorAutor as $libro) {
        echo $libro->getTitulo() . "\n";
    }

    echo "\nPréstamo realizado: " . 
         ($prestamo ? "Libro '{$prestamo->getLibro()->getTitulo()}' prestado el {$prestamo->getFechaPrestamo()}" : "Préstamo fallido") 
         . "\n";
}

// Descomentar para probar el ejemplo
// ejemploDeUso();
?>