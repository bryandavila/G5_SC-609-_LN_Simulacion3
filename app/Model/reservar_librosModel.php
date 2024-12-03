<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/G5_SC-609-_LN_Simulacion3/app/Model/baseDatosModel.php";

class ReservarLibrosModel {
    private $db;

    public function __construct() {
        $this->db = (new Conexion())->conectar();
    }

    public function insertarReserva($usuario_id, $libro_id, $fecha_prestamo, $fecha_devolucion) {
        // Convertir fechas a UTC antes de almacenarlas
        $fecha_prestamo_utc = new MongoDB\BSON\UTCDateTime((new DateTime($fecha_prestamo))->setTimezone(new DateTimeZone('UTC'))->getTimestamp() * 1000);
        $fecha_devolucion_utc = new MongoDB\BSON\UTCDateTime((new DateTime($fecha_devolucion))->setTimezone(new DateTimeZone('UTC'))->getTimestamp() * 1000);

        $reserva = [
            'usuario_id' => $usuario_id,
            'libro_id' => $libro_id,
            'fecha_prestamo' => $fecha_prestamo_utc,
            'fecha_devolucion' => $fecha_devolucion_utc
        ];

        $resultado = $this->db->prestamo->insertOne($reserva);
        return $resultado->getInsertedCount() > 0;
    }

    public function obtenerReservas() {
        return $this->db->prestamo->find()->toArray();
    }

    public function actualizarReserva($id, $fecha_devolucion) {
        // Convertir fecha de devolución a UTC antes de almacenarla
        $fecha_devolucion_utc = new MongoDB\BSON\UTCDateTime((new DateTime($fecha_devolucion))->setTimezone(new DateTimeZone('UTC'))->getTimestamp() * 1000);

        $resultado = $this->db->prestamo->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => ['fecha_devolucion' => $fecha_devolucion_utc]]
        );
        return $resultado->getModifiedCount() > 0;
    }

    public function eliminarReserva($id) {
        $resultado = $this->db->prestamo->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        return $resultado->getDeletedCount() > 0;
    }
}
?>
