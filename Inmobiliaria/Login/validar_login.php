<?php
include("../includes/conexion.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (
    isset($_POST['correo']) && strlen(trim($_POST['correo'])) >= 1 &&
    isset($_POST['password']) && strlen(trim($_POST['password'])) >= 1
    ) {

        $correo = trim($_POST['correo']);
        $password = trim($_POST['password']);

        $stmt = mysqli_prepare($conex, "SELECT id_usuario, email, password, rol, activo FROM usuarios WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $correo);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $usuario = mysqli_fetch_assoc($resultado);

            // Comparación directa - texto plano
            if ($password === $usuario['password']) {

                if ($usuario['activo'] == 0) {
                    header("Location: index.php?error=inactivo");
                    exit;
                }

                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['rol'] = $usuario['rol'];

                switch ($usuario['rol']) {
                    case 'admin':
                        $_SESSION['nombre'] = 'Administrador';
                        header("Location: ../Admin/index.php");
                        break;
                    case 'agente':
                        $q = mysqli_query($conex, "SELECT nombre FROM agentes WHERE id_usuario = " . $usuario['id_usuario']);
                        $a = mysqli_fetch_assoc($q);
                        $_SESSION['nombre'] = $a['nombre'] ?? 'Agente';
                        header("Location: ../Agente/index.php");
                        break;
                    case 'cliente':
                        $q = mysqli_query($conex, "SELECT nombre FROM clientes WHERE id_usuario = " . $usuario['id_usuario']);
                        $c = mysqli_fetch_assoc($q);
                        $_SESSION['nombre'] = $c['nombre'] ?? 'Cliente';
                        header("Location: ../Cliente/index.php");
                        break;
                }
                exit;

            }
            else {
                header("Location: index.php?error=credenciales");
                exit;
            }
        }
        else {
            header("Location: index.php?error=credenciales");
            exit;
        }

    }
    else {
        header("Location: index.php?error=campos");
        exit;
    }

}
else {
    header("Location: index.php");
    exit;
}
?>
