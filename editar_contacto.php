<?php
include("includes/header.php");

// Validar si recibimos el id del contacto por url
if (isset($_GET['id'])) {
    $idContacto = $_GET['id'];

    // Obtener la categoria actual
    $query = "SELECT * FROM contactos WHERE id = :id";
    $stmt = $conn->prepare($query);

    $stmt->bindParam(":id", $idContacto, PDO::PARAM_INT);
    $stmt->execute();

    $contacto = $stmt->fetch(PDO::FETCH_OBJ);

    // Validar si recibimos el id de la categoria por la url
    if (isset($_GET['idCategoria'])) {
        $idCategoria = $_GET['idCategoria'];

        $query = "SELECT * FROM categorias";
        $stmt = $conn->prepare($query);

        $stmt->execute();

        $categorias = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Insertamos datos
    if (isset($_POST['editarContacto'])) {
        // Obtener los valores
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $categoria = $_POST['categoria'];

        // Validamos si esta vacio
        if (empty($nombre) || empty($apellido) || empty($telefono) || empty($email) || empty($categoria)) {
            $error = "Error, algunos campos obligatorios están vacíos";
            header("Location: editar_contacto.php?error=" . $error);
        } else {
            // Si pasa por aqui es porque se puede ingresar el nuevo registro
            $query = "UPDATE contactos SET 
                nombre = :nombre, 
                apellido = :apellido, 
                telefono = :telefono, 
                email = :email, 
                categoria = :categoria
                WHERE id = :id";

            $stmt = $conn->prepare($query);

            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":apellido", $apellido, PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $telefono, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":categoria", $categoria, PDO::PARAM_INT);
            $stmt->bindParam(":id", $idContacto, PDO::PARAM_INT);

            $result = $stmt->execute();

            if ($result) {
                $mensaje = "Contacto editado correctamente";
                header("Location: contactos.php?mensaje=" . $mensaje);
                exit();
            } else {
                $error = "Error, no se pudo editar el registro";
                header("Location: editar_contacto.php?error=" . $error);
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
        <h3>Editar Contacto</h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 offset-3">
        <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingresa el nombre" value="<?php if ($contacto) echo $contacto->nombre ?>" required>
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos:</label>
                <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Ingresa los apellidos" value="<?php if ($contacto) echo $contacto->apellido ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="number" class="form-control" name="telefono" id="telefono" placeholder="Ingresa el teléfono" value="<?php if ($contacto) echo $contacto->telefono ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Ingresa el email" value="<?php if ($contacto) echo $contacto->email ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Categoría:</label>
                <select class="form-select" aria-label="Default select example" name="categoria">
                    <option value="">--Selecciona una Categoría--</option>
                    <?php foreach ($categorias as $fila) : ?>
                        <option value="<?php echo $fila->id ?>" <?php if ($idCategoria == $fila->id) echo "selected" ?>><?php echo $fila->nombre ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <br />
            <button type="submit" name="editarContacto" class="btn btn-primary w-100"><i class="bi bi-person-bounding-box"></i> Editar Contacto</button>
        </form>
    </div>
</div>
<?php include("includes/footer.php") ?>