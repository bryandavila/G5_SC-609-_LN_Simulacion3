<?php 
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . "/G5_SC-609-_LN_Simulacion3/app/Controller/LibroController/libroController.php";
    include_once '../layout.php';

    if (!isset($_GET['id_libro'])) {
        header("Location: ./listarLibros.php");
        exit;
    }

    $libroController = new libroController();
    $libro = $libroController->obtenerLibroActualizar($_GET['id_libro']);
?>

<!DOCTYPE html>
<html>

    <?php HeadCSS(); ?>

    <body class="d-flex flex-column min-vh-100">
        <?php MostrarNav(); MostrarMenu(); ?>

        <div class="container mt-5">
            <h1 class="text-center mb-4">Editar Libro</h1>
            <form action="../../Controller/LibroController/libroController.php?action=Actualizar" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_libro" value="<?php echo htmlspecialchars($libro['id_libro']); ?>">
                <input type="hidden" name="image" value="<?php echo htmlspecialchars($libro['image']); ?>">

                <div class="mb-3">
                    <label for="nombre_libro" class="form-label">Nombre del Libro</label>
                    <input type="text" class="form-control" id="nombre_libro" name="nombre_libro" value="<?php echo htmlspecialchars($libro['nombre_libro']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($libro['cantidad']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Imagen del Libro</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <p class="mt-2">Imagen actual: <img src="/G5_SC-609-_LN_Simulacion3/app/View/uploaded_img/libros_img/<?php echo htmlspecialchars($libro['image']); ?>"  alt="Libro" style="width: 100px; height: 100px;"></p>
                </div>
                <button type="submit" class="btn btn-success">Actualizar Libro</button>
                <a href="../libros/listaLibros.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </body>

</html>
