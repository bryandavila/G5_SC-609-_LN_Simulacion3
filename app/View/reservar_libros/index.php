<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/G5_SC-609-_LN_Simulacion3/app/Controller/ReservarLibrosController/ReservarLibrosController.php";
include_once '../layout.php';

$controller = new ReservarLibrosController();
$reservas = $controller->obtenerReservas();

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'reservar':
                $usuario_id = $_POST['usuario_id'];
                $libro_id = $_POST['libro_id'];
                $fecha_prestamo = $_POST['fecha_prestamo'];
                $fecha_devolucion = $_POST['fecha_devolucion'];
                try {
                    if ($controller->reservarLibro($usuario_id, $libro_id, $fecha_prestamo, $fecha_devolucion)) {
                        $message = "Reserva realizada con éxito.";
                    } else {
                        $error = "No se pudo realizar la reserva.";
                    }
                } catch (Exception $e) {
                    $error = "Error: " . $e->getMessage();
                }
                break;
            case 'actualizar':
                $id = $_POST['id'];
                $fecha_devolucion = $_POST['fecha_devolucion'];
                try {
                    if ($controller->actualizarReserva($id, $fecha_devolucion)) {
                        $message = "Reserva actualizada con éxito.";
                    } else {
                        $error = "No se pudo actualizar la reserva.";
                    }
                } catch (Exception $e) {
                    $error = "Error: " . $e->getMessage();
                }
                break;
            case 'eliminar':
                $id = $_POST['id'];
                try {
                    if ($controller->eliminarReserva($id)) {
                        $message = "Reserva eliminada con éxito.";
                    } else {
                        $error = "No se pudo eliminar la reserva.";
                    }
                } catch (Exception $e) {
                    $error = "Error: " . $e->getMessage();
                }
                break;
        }
    }
    $reservas = $controller->obtenerReservas();  // Refrescar las reservas después de cada acción
}
?>

<!DOCTYPE html>
<html lang="es">

<?php 
HeadCSS();
?>

<body class="d-flex flex-column min-vh-100">

<?php 
MostrarNav();
MostrarMenu();
?>

<div class="flex-grow-1 mb-5">
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h1 class="display-4 text-white">Gestión de Reservas de Libros</h1>
                        <p class="text-white">Administre las reservas y devoluciones de libros de la biblioteca.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="mb-0">Reservar Libro</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                        <?php endif; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="form-group">
                                <label for="usuario_id">ID Usuario:</label>
                                <input type="text" name="usuario_id" class="form-control" value="674e7edc21380d6323cffe8c" required>
                            </div>
                            <div class="form-group">
                                <label for="libro_id">ID Libro:</label>
                                <input type="text" name="libro_id" class="form-control" value="674e7efb21380d6323cffe8d" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_prestamo">Fecha Préstamo:</label>
                                <input type="date" name="fecha_prestamo" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_devolucion">Fecha Devolución:</label>
                                <input type="date" name="fecha_devolucion" class="form-control" required>
                            </div>
                            <button type="submit" name="accion" value="reservar" class="btn btn-primary">Reservar Libro</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-5">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="mb-0">Reservas Actuales</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <?php if (!empty($reservas)): ?>
                            <table class="table table-hover table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>ID Usuario</th>
                                        <th>ID Libro</th>
                                        <th>Fecha Préstamo</th>
                                        <th>Fecha Devolución</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservas as $reserva): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($reserva['_id']); ?></td>
                                            <td><?php echo htmlspecialchars($reserva['usuario_id']); ?></td>
                                            <td><?php echo htmlspecialchars($reserva['libro_id']); ?></td>
                                            <td><?php echo (new DateTime($reserva['fecha_prestamo']->toDateTime()->format('Y-m-d H:i:s'), new DateTimeZone('UTC')))
                                                            ->setTimezone(new DateTimeZone('America/Costa_Rica'))->format('Y-m-d'); ?></td>
                                            <td><?php echo (new DateTime($reserva['fecha_devolucion']->toDateTime()->format('Y-m-d H:i:s'), new DateTimeZone('UTC')))
                                                            ->setTimezone(new DateTimeZone('America/Costa_Rica'))->format('Y-m-d'); ?></td>
                                            <td>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($reserva['_id']); ?>">
                                                    <input type="date" name="fecha_devolucion" class="form-control d-inline" required>
                                                    <button type="submit" name="accion" value="actualizar" class="btn btn-warning btn-sm">Actualizar</button>
                                                </form>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($reserva['_id']); ?>">
                                                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger btn-sm">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted text-center">No hay reservas actuales.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php MostrarFooter(); ?>

<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/js-cookie/js.cookie.js"></script>
<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<script src="assets/vendor/chart.js/dist/Chart.min.js"></script>
<script src="assets/vendor/chart.js/dist/Chart.extension.js"></script>
<script src="assets/js/argon.js?v=1.2.0"></script>
</body>

</html>
