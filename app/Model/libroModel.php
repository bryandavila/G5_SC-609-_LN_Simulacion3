<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/G5_SC-609-_LN_Simulacion3/app/Model/baseDatosModel.php";

    class libroModel {
        private $conexion;

        public function __construct() {
            $this->conexion = new Conexion();
        }

        public function obtenerLibros() {
            try {
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return [];
                }
                $librosCollection = $db->libros; 
                $libros = $librosCollection->find(); 

                $listaLibros = [];
                foreach ($libros as $libro) {
                    $listaLibros[] = [
                        'id_libro' => $libro['id_libro'], 
                        'image' => $libro['image'],
                        'nombre_libro' => $libro['nombre_libro'],
                        'cantidad' => $libro['cantidad'],
                    ];
                }
                return $listaLibros;
            } catch (\Exception $e) {
                return [];
            }
        }

        public function crearLibro($libro) {
            try {
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return false;
                }

                $librosCollection = $db->libros;
                $librosCollection->insertOne($libro); 
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        public function obtenerLibroXId($id_libro) {
            try {
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return [];
                }
        
                $librosCollection = $db->libros;
                $libro = $librosCollection->findOne(['id_libro' => (int)$id_libro]);
        
                if ($libro !== null) {
                    return [
                        'id_libro' => $libro['id_libro'],
                        'image' => $libro['image'],
                        'nombre_libro' => $libro['nombre_libro'],
                        'cantidad' => $libro['cantidad'],
                    ];
                }
                return [];
            } catch (\Exception $e) {
                return [];
            }
        }
        
        
        public function actualizarLibro($id_libro, $libroActualizado) {
            try {
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return false;
                }
        
                $librosCollection = $db->libros;
                $resultado = $librosCollection->updateOne(
                    ['id_libro' => (int)$id_libro], 
                    ['$set' => $libroActualizado]
                );
        
                return $resultado->getModifiedCount() > 0;
            } catch (\Exception $e) {
                return false;
            }
        }
        

        public function eliminarLibro($id_libro) {
            try {
                $db = $this->conexion->conectar();
                if ($db === null) {
                    return false;
                }

                $librosCollection = $db->libros;
                $librosCollection->deleteOne(['id_libro' => (int)$id_libro]);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
    } 
?>
