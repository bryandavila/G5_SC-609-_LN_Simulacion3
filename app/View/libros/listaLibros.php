<?php 
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . "/G5_SC-609-_LN_Simulacion3/app/Controller/LibroController/libroController.php";
    include_once '../layout.php';
    $libroController = new libroController();
    $libros = $libroController->listarLibros();
?>

<!DOCTYPE html>
<html>

    <?php HeadCSS(); ?>

    <body class="d-flex flex-column min-vh-100">
    <?php MostrarNav(); MostrarMenu(); ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Lista de libros</h1>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Img</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($libros as $libro): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($libro['id_libro']); ?></td>
                                <td><img src="/G5_SC-609-_LN_Simulacion3/app/View/uploaded_img/libros_img/<?php echo htmlspecialchars($libro['image']); ?>"  alt="Libro" style="width: 40px; height: 40px;"></td>
                                <td><?php echo htmlspecialchars($libro['nombre_libro']); ?></td>
                                <td><?php echo htmlspecialchars($libro['cantidad']); ?></td>
                                <td class="d-flex gap-2">
                                    <a href="../libros/editarLibro.php?id_libro=<?php echo $libro['id_libro']; ?>" 
                                    class="btn btn-success btn-sm w-100 d-flex align-items-center justify-content-center">
                                        <i class="fa fa-edit me-2"></i> Editar
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger w-100 d-flex align-items-center justify-content-center"
                                            onclick="confirmarEliminacion(<?php echo urlencode($libro['id_libro']); ?>)">
                                        <i class="fa fa-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            <?php if (isset($_SESSION['mensaje'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    text: '<?php echo $_SESSION['mensaje']; ?>',
                });
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>
        });
    </script>

    <script>
        function confirmarEliminacion(id_libro) {
            Swal.fire({
                title: '¿Estás seguro de que deseas eliminar este libro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../Controller/LibroController/libroController.php',
                        type: 'POST',
                        data: {
                            action: 'elimarLibro',
                            id_libro: id_libro
                        },
                        
                        success: function(response) {
                            console.log(response); 
                            var result = JSON.parse(response);
                            console.log(result); 
                            if (result.success) {
                                Swal.fire({
                                    title: 'Éxito',
                                    text: result.message,
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: result.message,
                                    icon: 'error'
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>

    </body>
</html>
