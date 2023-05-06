<?php

include("includes/header.php");

if (isset($_GET['id'])) {
    $idCategoria = $_GET['id'];

    // Obtener la categoria actual
    $query = "SELECT * FROM categorias WHERE id = :id";
    $stmt = $conn->prepare($query);

    $stmt->bindParam(":id", $idCategoria, PDO::PARAM_INT);
    $stmt->execute();

    $categoria = $stmt->fetch(PDO::FETCH_OBJ);

    // Editamos datos
    if (isset($_POST['editarCategoria'])) {
        // Obtener los valores
        $nombre = $_POST['nombre'];

        // Validamos si esta vacio
        if (empty($nombre)) {
            $error = "Error, algunos campos obligatorios están vacíos";
            header("Location: editar_categoria.php?error=" . $error);
        } else {
            // Configuramos la fecha para la insercion
            $fechaActual = date("Y-m-d");

            // Si pasa por aqui es porque se puede ingresar el nuevo registro
            $query = "UPDATE categorias SET nombre = :nombre WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $idCategoria, PDO::PARAM_INT);
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);

            $result = $stmt->execute();

            if ($result) {
                $mensaje = "Categoria editada correctamente";
                header("Location: categorias.php?mensaje=" . $mensaje);
                exit();
            } else {
                $error = "Error, no se pudo editar el registro";
                header("Location: categorias.php?error=" . $error);
                exit();
            }
        }
    }
}
?>

<div class="row">
    <div class="col-sm-12">
        <?php if(isset($_GET['error'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $_GET['error'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <h3>Editar Categoría</h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 offset-3">
        <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingresa el nombre" value="<?php if ($categoria) echo $categoria->nombre; ?>" required>
            </div>

            <button type="submit" name="editarCategoria" class="btn btn-primary w-100">Editar Categoría</button>
        </form>
    </div>
</div>
<?php include("includes/footer.php") ?>