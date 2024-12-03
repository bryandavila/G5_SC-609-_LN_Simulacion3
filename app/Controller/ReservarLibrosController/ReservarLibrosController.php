<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/G5_SC-609-_LN_Simulacion3/app/Model/baseDatosModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/G5_SC-609-_LN_Simulacion3/app/Model/reservar_librosModel.php";

class ReservarLibrosController {
    private $model;

    public function __construct() {
        $this->model = new ReservarLibrosModel();
    }

    public function reservarLibro($usuario_id, $libro_id, $fecha_prestamo, $fecha_devolucion) {
        try {
            $usuario_id = new MongoDB\BSON\ObjectId($usuario_id);
            $libro_id = new MongoDB\BSON\ObjectId($libro_id);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            throw new InvalidArgumentException("IDs de usuario o libro inválidos: " . $e->getMessage());
        }

        return $this->model->insertarReserva($usuario_id, $libro_id, $fecha_prestamo, $fecha_devolucion);
    }

    public function obtenerReservas() {
        return $this->model->obtenerReservas();
    }

    public function actualizarReserva($id, $fecha_devolucion) {
        try {
            $id = new MongoDB\BSON\ObjectId($id);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            throw new InvalidArgumentException("ID de reserva inválido: " . $e->getMessage());
        }

        return $this->model->actualizarReserva($id, $fecha_devolucion);
    }

    public function eliminarReserva($id) {
        try {
            $id = new MongoDB\BSON\ObjectId($id);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            throw new InvalidArgumentException("ID de reserva inválido: " . $e->getMessage());
        }

        return $this->model->eliminarReserva($id);
    }
}
?>
