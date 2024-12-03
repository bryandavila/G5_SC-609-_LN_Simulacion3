<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/G5_SC-609-_LN_Simulacion3/app/Model/libroModel.php";

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    class libroController {
        private $libroModel;

        public function __construct() {
            $this->libroModel = new libroModel();
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }

        public function listarLibros() {
            return $this->libroModel->obtenerLibros();
        }

        public function crearLibro() {
            $nombre_libro = $_POST['nombre_libro'];
            $cantidad = $_POST['cantidad'];
            $img_libro = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $img_libro = $this->procesarImagen($_FILES['image']);
                if (!$img_libro) {
                    $_SESSION['mensaje'] = "Error al subir la imagen.";
                    return;
                }
            }

            $libro = [
                'id_libro' => time(),
                'image' => $img_libro,
                'nombre_libro' => $nombre_libro,
                'cantidad' => (int)$cantidad
            ];

            if ($this->libroModel->crearLibro($libro)) {
                $_SESSION['mensaje'] = "Libro agregado exitosamente.";
            } else {
                $_SESSION['mensaje'] = "Error al agregar el libro.";
            }
        }

        public function obtenerLibroActualizar($id_libro) {
            return $this->libroModel->obtenerLibroXId($id_libro);
        }

        public function actualizarLibro() {
            $id_libro = $_POST['id_libro'];
            $image = $_POST['image'] ?? null;
            $nombre_libro = $_POST['nombre_libro'];
            $cantidad = $_POST['cantidad'];
            $img_libro = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $img_libro = $this->procesarImagen($_FILES['image']);
                if (!$img_libro) {
                    $_SESSION['mensaje'] = "Error al subir la imagen.";
                    return;
                }
            }

            $productoActualizado = [
                'image' => $img_libro ?? $image,
                'nombre_libro' => $nombre_libro,
                'cantidad' => (int)$cantidad
            ];

            if ($this->libroModel->actualizarLibro($id_libro, $productoActualizado)) {
                $_SESSION['mensaje'] = "Libro actualizado exitosamente.";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar el libro.";
            }
        }

        public function eliminarLibro($id_libro) {
            if ($this->libroModel->eliminarLibro($id_libro)) {
                echo json_encode(['success' => true, 'message' => 'Libro eliminado.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el libro.']);
            }
        }

        private function procesarImagen($archivo) {
            $temp_name = $archivo['tmp_name'];
            $file_name = basename($archivo['name']);
            $file_name = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $file_name);
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/G5_SC-609-_LN_Simulacion3/app/View/uploaded_img/libros_img/';
            $file_type = mime_content_type($temp_name);
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (!in_array($file_type, $allowed_types)) {
                return false; 
            }

            $target_file = $upload_dir . $file_name;
            if (move_uploaded_file($temp_name, $target_file)) {
                return $file_name; 
            }
            return false; 
        }
    }

    $controller = new libroController();

    if (isset($_REQUEST['action'])) {
        $action = $_REQUEST['action'];
        switch ($action) {
            case 'Crear':
                $controller->crearLibro();
                header("Location: /G5_SC-609-_LN_Simulacion3/app/View/libros/listaLibros.php");
                break;
            case 'Actualizar':
                $controller->actualizarLibro();
                header("Location: /G5_SC-609-_LN_Simulacion3/app/View/libros/listaLibros.php");
                break;
            case 'elimarLibro':
                if (isset($_POST['id_libro'])) {
                    $controller->eliminarLibro(intval($_POST['id_libro']));
                } else {
                    echo json_encode(['success' => false, 'message' => 'ID del libro no proporcionado.']);
                }
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Acción no reconocida.']);
        }
    }
?>
