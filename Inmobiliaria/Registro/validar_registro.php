<?php
include("../includes/conexion.php");

if (isset($_POST['registro'])) {

    $campos = ['nombre', 'tipo_documento', 'numero_documento', 'telefono', 'direccion', 'correo', 'password'];
    foreach ($campos as $campo) {
        if (!isset($_POST[$campo]) || strlen(trim($_POST[$campo])) < 1) {
            header("Location: index.php?error=campos");
            exit;
        }
    }

    $nombre = trim($_POST['nombre']);
    $tipo_documento = trim($_POST['tipo_documento']);
    $numero_documento = trim($_POST['numero_documento']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);

    if (!is_numeric($numero_documento) || !is_numeric($telefono)) {
        header("Location: index.php?error=no_numerico");
        exit;
    }

    if (strlen($password) <= 6) {
        header("Location: index.php?error=password");
        exit;
    }

    $stmt = mysqli_prepare($conex, "SELECT id_usuario FROM usuarios WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) > 0) {
        header("Location: index.php?error=email");
        exit;
    }

    // Texto plano - sin hash
    $password_plain = $password;

    mysqli_begin_transaction($conex);

    try {
        $stmt1 = mysqli_prepare($conex, "INSERT INTO usuarios (email, password, rol) VALUES (?, ?, 'cliente')");
        mysqli_stmt_bind_param($stmt1, "ss", $correo, $password_plain);
        mysqli_stmt_execute($stmt1);
        $id_usuario = mysqli_insert_id($conex);

        $stmt2 = mysqli_prepare($conex, "INSERT INTO clientes (id_usuario, nombre, tipo_documento, numero_documento, telefono, direccion) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt2, "isssss", $id_usuario, $nombre, $tipo_documento, $numero_documento, $telefono, $direccion);
        mysqli_stmt_execute($stmt2);

        $id_cliente = mysqli_insert_id($conex);
        $stmt3 = mysqli_prepare($conex, "INSERT INTO billetera (id_cliente, saldo, estado) VALUES (?, 0, 'activa')");
        mysqli_stmt_bind_param($stmt3, "i", $id_cliente);
        mysqli_stmt_execute($stmt3);

        mysqli_commit($conex);
        header("Location: ../Login/index.php?registro=exitoso");
        exit;

    }
    catch (Exception $e) {
        mysqli_rollback($conex);
        header("Location: index.php?error=db");
        exit;
    }

}
else {
    header("Location: index.php");
    exit;
}
?>
