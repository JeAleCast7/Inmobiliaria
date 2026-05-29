<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Agente — Administrador</title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/assets/css/panel.css">
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar__header">
            <div class="sidebar__logo">
                <img src="<?php echo URL_ROOT; ?>/assets/img/logo.png" alt="BRE Logo">
            </div>
            <div>
                <div class="sidebar__title">Boyaca Real Estate</div>
                <div class="sidebar__subtitle">Panel Admin</div>
            </div>
        </div>

        <div class="sidebar__user">
            <div class="sidebar__user-name"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Administrador'); ?></div>
            <div class="sidebar__user-role">Administrador</div>
        </div>

        <ul class="sidebar__menu">
            <li><a href="<?php echo URL_ROOT; ?>/admin/dashboard"><span class="icon">🏠</span> Volver al Dashboard</a></li>
        </ul>
    </aside>

    <main class="main">
        <div class="main__header">
            <h1>Agregar Nuevo Agente</h1>
            <p>Complete todos los campos para registrar un nuevo agente y crear su cuenta de acceso al sistema.</p>
        </div>

        <div class="form-container">
            <a href="<?php echo URL_ROOT; ?>/admin/dashboard" class="btn-back">← Volver</a>
            
            <?php if (isset($_GET['error'])): ?>
                <div style="background-color: rgba(239, 68, 68, 0.15); color: #f87171; padding: 12px; border-radius: 8px; margin-bottom: 24px; border: 1px solid rgba(239, 68, 68, 0.3);">
                    <?php 
                        $err = $_GET['error'];
                        if ($err === 'campos') echo "Por favor, complete todos los campos obligatorios.";
                        elseif ($err === 'email') echo "El correo electrónico ingresado ya se encuentra registrado.";
                        else echo "Error al registrar el agente. Por favor, intente nuevamente.";
                    ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo URL_ROOT; ?>/admin/agentes/store" method="POST">
                
                <h3 class="form-section-title" style="margin-top: 0;">1. Información de Perfil</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required placeholder="Ej: Juan Pérez">
                    </div>
                    <div class="form-group">
                        <label for="cargo">Cargo / Rol Interno</label>
                        <input type="text" name="cargo" id="cargo" class="form-control" placeholder="Ej: Agente Senior, Asesor de Ventas" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tipo_documento">Tipo de Documento</label>
                        <select name="tipo_documento" id="tipo_documento" class="form-control" required>
                            <option value="CC" selected>Cédula de Ciudadanía (CC)</option>
                            <option value="CE">Cédula de Extranjería (CE)</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="CERL">CERL</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="numero_documento">Número de Documento</label>
                        <input type="number" name="numero_documento" id="numero_documento" class="form-control" required placeholder="Ej: 1020345678">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="telefono">Teléfono de Contacto</label>
                        <input type="number" name="telefono" id="telefono" class="form-control" placeholder="Ej: 3105550001" required>
                    </div>
                </div>

                <h3 class="form-section-title">2. Credenciales de Acceso</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Correo Electrónico (Login)</label>
                        <input type="email" name="email" id="email" class="form-control" required placeholder="correo@agentebre.com">
                        <small style="color: #a0b098; display: block; margin-top: 4px; font-size: 11px;">Este será el usuario de acceso para el agente.</small>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña Temporal</label>
                        <input type="text" name="password" id="password" class="form-control" required placeholder="Ej: agente123">
                        <small style="color: #a0b098; display: block; margin-top: 4px; font-size: 11px;">Indique una contraseña provisoria para el primer acceso.</small>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 32px;">
                    <button type="submit" class="btn-submit">Registrar Agente y Crear Cuenta</button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
