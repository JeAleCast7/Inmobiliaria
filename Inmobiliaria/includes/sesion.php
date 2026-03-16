<?php
session_start();

function verificar_sesion()
{
    if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['rol'])) {
        header("Location: ../Login/index.php");
        exit;
    }
}

function verificar_rol($rol_requerido)
{
    verificar_sesion();
    if ($_SESSION['rol'] !== $rol_requerido) {
        header("Location: ../Login/index.php");
        exit;
    }
}

function cerrar_sesion()
{
    session_start();
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit;
}
?>
