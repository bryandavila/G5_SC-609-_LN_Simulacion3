<?php 
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . "/G5_SC-609-_LN_Simulacion3/app/Controller/LibroController/libroController.php";
    include_once '../layout.php';
?>

<!DOCTYPE html>
<html>

    <?php HeadCSS(); ?>

    <body class="d-flex flex-column min-vh-100">
        <?php MostrarNav(); MostrarMenu(); ?>

        <div class="container mt-5">
            <h1 class="text-center mb-4">Crear Libro</h1>
            <form action="../../Controller/LibroController/libroController.php?action=Crear" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nombre_libro" class="form-label">Nombre del Libro</label>
                    <input type="text" class="form-control" id="nombre_libro" name="nombre_libro" required>
                </div>
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Imagen del Libro</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Crear Libro</button>
                <a href="../libros/listaLibros.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </body>

</html>
