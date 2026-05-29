<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Inmueble — Administrador</title>
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
            <h1>Agregar Nuevo Inmueble</h1>
            <p>Complete todos los campos para registrar un nuevo inmueble y publicarlo en el inventario.</p>
        </div>

        <div class="form-container">
            <a href="<?php echo URL_ROOT; ?>/admin/dashboard" class="btn-back">← Volver</a>
            
            <?php if (isset($_GET['error'])): ?>
                <div style="background-color: rgba(239, 68, 68, 0.15); color: #f87171; padding: 12px; border-radius: 8px; margin-bottom: 24px; border: 1px solid rgba(239, 68, 68, 0.3);">
                    Error al crear el inmueble. Por favor, verifique los datos.
                </div>
            <?php endif; ?>

            <form action="<?php echo URL_ROOT; ?>/admin/inmuebles/store" method="POST" enctype="multipart/form-data">
                
                <h3 class="form-section-title" style="margin-top: 0;">1. Datos Básicos</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tipo">Tipo de Inmueble</label>
                        <select name="tipo" id="tipo" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="casa">Casa</option>
                            <option value="apartamento">Apartamento</option>
                            <option value="oficina">Oficina</option>
                            <option value="local">Local Comercial</option>
                            <option value="bodega">Bodega</option>
                            <option value="lote">Lote / Terreno</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección Completa</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" required placeholder="Ej: Calle 100 #15-25, Bogotá">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="estrato">Estrato</label>
                        <input type="number" name="estrato" id="estrato" class="form-control" min="0" max="6" value="3">
                    </div>
                    <div class="form-group">
                        <label for="area_m2">Área (m²)</label>
                        <input type="number" step="0.01" name="area_m2" id="area_m2" class="form-control" required placeholder="Ej: 120.5">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="habitaciones">Habitaciones</label>
                        <input type="number" name="habitaciones" id="habitaciones" class="form-control" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="banos">Baños</label>
                        <input type="number" name="banos" id="banos" class="form-control" min="0" value="0">
                    </div>
                </div>

                <h3 class="form-section-title">2. Gestión Comercial</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tipo_operacion">Tipo de Operación (Inventario)</label>
                        <select name="tipo_operacion" id="tipo_operacion" class="form-control" required>
                            <option value="venta">Venta</option>
                            <option value="arriendo">Arriendo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio Final ($)</label>
                        <input type="number" name="precio" id="precio" class="form-control" required placeholder="Ej: 350000000">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="estado_inmueble">Estado Físico del Inmueble</label>
                        <select name="estado_inmueble" id="estado_inmueble" class="form-control" required>
                            <option value="disponible" selected>Disponible</option>
                            <option value="mantenimiento">En Mantenimiento</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="estado_inventario">Visibilidad en Inventario</label>
                        <select name="estado_inventario" id="estado_inventario" class="form-control" required>
                            <option value="activo" selected>Activo (Visible)</option>
                            <option value="pausado">Pausado (Oculto)</option>
                        </select>
                    </div>
                </div>

                <h3 class="form-section-title">3. Asignación y Multimedia</h3>
                <div class="form-group">
                    <label for="id_agente">Agente Encargado (Opcional)</label>
                    <select name="id_agente" id="id_agente" class="form-control">
                        <option value="">-- Sin asignar --</option>
                        <?php if (isset($agentes) && !empty($agentes)): ?>
                            <?php foreach ($agentes as $agente): ?>
                                <option value="<?php echo $agente['id_agente']; ?>">
                                    <?php echo htmlspecialchars($agente['nombre']); ?> (<?php echo htmlspecialchars($agente['cargo']); ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fotos">Fotos del Inmueble (Cloudinary)</label>
                    <input type="file" name="fotos[]" id="fotos" class="form-control" multiple accept="image/*" required>
                    <small style="color: #a0b098; display: block; margin-top: 6px; font-size: 12px;">Puede seleccionar múltiples fotos al mismo tiempo. Éstas se subirán directamente a su servidor de Cloudinary.</small>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción y Ficha Técnica</label>
                    <textarea name="descripcion" id="descripcion" rows="5" class="form-control" required placeholder="Escriba aquí los detalles principales del inmueble..."></textarea>
                </div>

                <div class="form-group" style="margin-top: 32px;">
                    <button type="submit" class="btn-submit">Crear Inmueble y Publicar</button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
