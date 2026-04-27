<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin — Boyaca Real Estate</title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/assets/css/panel.css">
</head>

<body>

    <!-- ═══ Sidebar ═══ -->
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
            <div class="sidebar__user-name"><?php echo htmlspecialchars($_SESSION['nombre']); ?></div>
            <div class="sidebar__user-role">Administrador</div>
        </div>

        <div class="sidebar__label">Principal</div>
        <ul class="sidebar__menu">
            <li><a class="active" onclick="mostrar('dashboard', this)"><span class="icon">🏠</span> Dashboard</a></li>
            <li><a onclick="mostrar('agentes', this)"><span class="icon">👔</span> Agentes</a></li>
            <li><a onclick="mostrar('clientes', this)"><span class="icon">👥</span> Clientes</a></li>
            <li><a onclick="mostrar('inmuebles', this)"><span class="icon">🏢</span> Inmuebles</a></li>
        </ul>

        <div class="sidebar__label">Operaciones</div>
        <ul class="sidebar__menu">
            <li><a onclick="mostrar('reservas', this)"><span class="icon">📋</span> Reservas</a></li>
            <li><a onclick="mostrar('facturas', this)"><span class="icon">💰</span> Facturas</a></li>
            <li><a onclick="mostrar('inventario', this)"><span class="icon">📦</span> Inventario</a></li>
            <li><a onclick="mostrar('permisos', this)"><span class="icon">📜</span> Permisos</a></li>
        </ul>

        <div class="sidebar__logout">
            <a href="<?php echo URL_ROOT; ?>/logout"><span class="icon">🚪</span> Cerrar Sesión</a>
        </div>
    </aside>

    <!-- ═══ Main Content ═══ -->
    <main class="main">
        <div class="main__header">
            <h1>Panel de Administración</h1>
            <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>. Aquí tienes un resumen general.</p>
        </div>

        <!-- Dashboard -->
        <div id="dashboard">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card__icon">👥</div>
                    <div class="stat-card__number"><?php echo $total_clientes; ?></div>
                    <div class="stat-card__label">Clientes Registrados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon">👔</div>
                    <div class="stat-card__number"><?php echo $total_agentes; ?></div>
                    <div class="stat-card__label">Agentes Activos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon">🏢</div>
                    <div class="stat-card__number"><?php echo $total_inmuebles; ?></div>
                    <div class="stat-card__label">Total Inmuebles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon">✅</div>
                    <div class="stat-card__number"><?php echo $total_disponibles; ?></div>
                    <div class="stat-card__label">Disponibles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon">📋</div>
                    <div class="stat-card__number"><?php echo $total_reservas; ?></div>
                    <div class="stat-card__label">Reservas Pendientes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon">💰</div>
                    <div class="stat-card__number"><?php echo $total_facturas; ?></div>
                    <div class="stat-card__label">Facturas Emitidas</div>
                </div>
            </div>

            <!-- Últimos clientes -->
            <div class="panel-section">
                <div class="panel-section__header">
                    <h3 class="panel-section__title">Últimos Clientes Registrados</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cédula</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ultimos_clientes)): ?>
                            <?php foreach ($ultimos_clientes as $c): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($c['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($c['tipo_documento'] . ' ' . $c['numero_documento']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($c['email']); ?></td>
                                    <td><?php echo htmlspecialchars($c['telefono']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($c['fecha_registro'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#607050; padding:40px;">No hay clientes
                                    registrados aún</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Últimos inmuebles -->
            <div class="panel-section">
                <div class="panel-section__header">
                    <h3 class="panel-section__title">Últimos Inmuebles</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Dirección</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Agente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ultimos_inmuebles)): ?>
                            <?php foreach ($ultimos_inmuebles as $i): ?>
                                <tr>
                                    <td><?php echo ucfirst($i['tipo']); ?></td>
                                    <td><?php echo htmlspecialchars($i['direccion']); ?></td>
                                    <td>$<?php echo number_format($i['precio'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span
                                            class="badge badge--<?php echo $i['estado'] == 'disponible' ? 'success' : ($i['estado'] == 'reservado' ? 'warning' : 'info'); ?>">
                                            <?php echo ucfirst($i['estado']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($i['agente_nombre'] ?? 'Sin asignar'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#607050; padding:40px;">No hay inmuebles
                                    registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Últimas reservas -->
            <div class="panel-section">
                <div class="panel-section__header">
                    <h3 class="panel-section__title">Últimas Reservas</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Inmueble</th>
                            <th>Estado</th>
                            <th>Fecha Reserva</th>
                            <th>Fecha Visita</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ultimas_reservas)): ?>
                            <?php foreach ($ultimas_reservas as $r): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($r['cliente_nombre']); ?></td>
                                    <td><?php echo ucfirst($r['tipo_inmueble']) . ' - ' . htmlspecialchars($r['direccion']); ?>
                                    </td>
                                    <td>
                                        <span class="badge badge--<?php
                                        $badge_map = ['pendiente' => 'warning', 'confirmada' => 'success', 'cancelada' => 'danger', 'finalizada' => 'info'];
                                        echo $badge_map[$r['estado']] ?? 'info';
                                        ?>">
                                            <?php echo ucfirst($r['estado']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($r['fecha_reserva'])); ?></td>
                                    <td><?php echo $r['fecha_visita'] ? date('d/m/Y H:i', strtotime($r['fecha_visita'])) : '—'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#607050; padding:40px;">No hay reservas aún
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Agentes -->
        <div id="agentes" style="display:none;">
            <div class="panel-section">
                <div class="panel-section__header">
                    <h3 class="panel-section__title">Gestión de Agentes</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Email</th>
                            <th>Cargo</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($agentes)): ?>
                            <?php foreach ($agentes as $a): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($a['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($a['tipo_documento'] . ' ' . $a['numero_documento']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($a['user_email']); ?></td>
                                    <td><?php echo htmlspecialchars($a['cargo']); ?></td>
                                    <td><?php echo htmlspecialchars($a['telefono']); ?></td>
                                    <td><span
                                            class="badge badge--<?php echo $a['activo'] ? 'success' : 'danger'; ?>"><?php echo $a['activo'] ? 'Activo' : 'Inactivo'; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center; color:#607050; padding:40px;">No hay agentes
                                    registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Clientes -->
        <div id="clientes" style="display:none;">
            <div class="panel-section">
                <div class="panel-section__header">
                    <h3 class="panel-section__title">Gestión de Clientes</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($clientes_all)): ?>
                            <?php foreach ($clientes_all as $cl): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cl['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($cl['tipo_documento'] . ' ' . $cl['numero_documento']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($cl['user_email']); ?></td>
                                    <td><?php echo htmlspecialchars($cl['telefono']); ?></td>
                                    <td><?php echo htmlspecialchars($cl['direccion']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($cl['fecha_registro'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center; color:#607050; padding:40px;">No hay clientes
                                    registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Inmuebles — Catálogo -->
    <div id="inmuebles" style="display:none;">
        <div class="panel-section">
            <div class="panel-section__header">
                <h3 class="panel-section__title">Gestión de Inmuebles</h3>
            </div>
            <?php
            $iconos_tipo = [
                'casa' => '🏠', 'apartamento' => '🏢', 'oficina' => '🏛️',
                'local' => '🏪', 'lote' => '📐', 'bodega' => '🏭'
            ];
            ?>
            <div class="catalog-grid">
                <?php if (!empty($inmuebles_all)): ?>
                <?php foreach ($inmuebles_all as $im):
                    $icono = $iconos_tipo[$im['tipo']] ?? '🏠';
                    $tipo_op = isset($im['precio_pub']) ? 'venta' : 'venta';
                    $precio = $im['precio_pub'] ?? $im['precio'];

                    $fotos_json = isset($im['fotos']) && $im['fotos'] ? $im['fotos'] : '[]';
                    $fotos_array = json_decode($fotos_json, true) ?: [];
                    $primera_foto = !empty($fotos_array) ? $fotos_array[0] : '';
                    if ($primera_foto && strpos($primera_foto, 'http') === false) {
                        $primera_foto = URL_ROOT . '/' . $primera_foto;
                    }
                ?>
                <article class="catalog-card" style="cursor:pointer;" onclick="openPropertyModalFromData({
                    desc: '<?php echo addslashes($im['descripcion']); ?>',
                    price: '<?php echo number_format($precio, 0, ',', '.'); ?>',
                    beds: '<?php echo $im['habitaciones']; ?>',
                    baths: '<?php echo $im['banos']; ?>',
                    area: '<?php echo $im['area_m2']; ?>',
                    phone: '<?php echo addslashes($im['agente_telefono'] ?? ''); ?>',
                    images: '<?php echo str_replace("'", "\\'", $fotos_json); ?>'
                })">
                    <div class="catalog-card__image" <?php echo $primera_foto ? 'style="background-image: url(\''.htmlspecialchars($primera_foto).'\'); background-size: cover; background-position: center;"' : ''; ?>>
                        <?php echo $primera_foto ? '' : $icono; ?>
                        <span class="catalog-card__badge catalog-card__badge--<?php echo $im['estado'] == 'disponible' ? 'venta' : 'arriendo'; ?>">
                            <?php echo ucfirst($im['estado']); ?>
                        </span>
                    </div>
                    <div class="catalog-card__body">
                        <div class="catalog-card__type"><?php echo ucfirst($im['tipo']); ?></div>
                        <h3 class="catalog-card__title"><?php echo htmlspecialchars(mb_strimwidth($im['descripcion'], 0, 60, '...')); ?></h3>
                        <div class="catalog-card__location">
                            📍 <?php echo htmlspecialchars($im['direccion']); ?>
                        </div>
                        <div class="catalog-card__features">
                            <?php if ($im['habitaciones'] > 0): ?>
                            <span class="catalog-card__feature">🛏️ <?php echo $im['habitaciones']; ?> Hab.</span>
                            <?php endif; ?>
                            <?php if ($im['banos'] > 0): ?>
                            <span class="catalog-card__feature">🚿 <?php echo $im['banos']; ?> Baños</span>
                            <?php endif; ?>
                            <span class="catalog-card__feature">📐 <?php echo $im['area_m2']; ?> m²</span>
                            <?php if ($im['estrato'] > 0): ?>
                            <span class="catalog-card__feature">⭐ Estrato <?php echo $im['estrato']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="catalog-card__price">
                            $<?php echo number_format($precio, 0, ',', '.'); ?>
                        </div>
                        <div class="catalog-card__agent">
                            👔 Agente: <strong><?php echo htmlspecialchars($im['agente_nombre'] ?? 'Sin asignar'); ?></strong>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
                <?php else: ?>
                <p style="text-align:center; color:#607050; grid-column:1/-1; padding:40px;">No hay inmuebles registrados</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

        <!-- Reservas -->
        <div id="reservas" style="display:none;">
            <div class="panel-section">
                <div class="panel-section__header">
                    <h3 class="panel-section__title">Gestión de Reservas</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Inmueble</th>
                            <th>Agente</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($reservas_all)): ?>
                            <?php foreach ($reservas_all as $re): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($re['cliente_nombre']); ?></td>
                                    <td><?php echo ucfirst($re['tipo_inmueble']) . ' - ' . htmlspecialchars($re['direccion']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($re['agente_nombre'] ?? '—'); ?></td>
                                    <td><span class="badge badge--<?php
                                    $badge_map = ['pendiente' => 'warning', 'confirmada' => 'success', 'cancelada' => 'danger'];
                                    echo $badge_map[$re['estado']] ?? 'info';
                                    ?>"><?php echo ucfirst($re['estado']); ?></span></td>
                                    <td><?php echo date('d/m/Y', strtotime($re['fecha_reserva'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#607050; padding:40px;">No hay reservas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Facturas -->
        <div id="facturas" style="display:none;">
            <div class="panel-section">
                <div class="panel-section__header">
                    <h3 class="panel-section__title">Facturas</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Valor</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($facturas_all)): ?>
                            <?php foreach ($facturas_all as $fa): ?>
                                <tr>
                                    <td>#<?php echo $fa['id_factura']; ?></td>
                                    <td><?php echo htmlspecialchars($fa['cliente_nombre']); ?></td>
                                    <td><?php echo ucfirst($fa['tipo']); ?></td>
                                    <td>$<?php echo number_format($fa['valor_total'], 0, ',', '.'); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($fa['fecha'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#607050; padding:40px;">No hay facturas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Inventario -->
        <div id="inventario" style="display:none;">
            <div class="panel-section">
                <div class="panel-section__header">
                    <h3 class="panel-section__title">Inventario</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Inmueble</th>
                            <th>Tipo Operación</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Publicación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($inventario_all)): ?>
                            <?php foreach ($inventario_all as $inv): ?>
                                <tr style="cursor:pointer;" onclick="openPropertyModalFromData({
                        desc: '<?php echo addslashes($inv['descripcion'] ?? ''); ?>',
                        price: '<?php echo number_format($inv['precio'], 0, ',', '.'); ?>',
                        beds: '<?php echo $inv['habitaciones'] ?? '0'; ?>',
                        baths: '<?php echo $inv['banos'] ?? '0'; ?>',
                        area: '<?php echo $inv['area_m2'] ?? '0'; ?>',
                        phone: '',
                        images: '<?php echo addslashes($inv['fotos'] ?? '[]'); ?>'
                    })">
                                    <td><?php echo ucfirst($inv['tipo_inmueble']) . ' - ' . htmlspecialchars($inv['direccion']); ?>
                                    </td>
                                    <td><?php echo ucfirst($inv['tipo']); ?></td>
                                    <td>$<?php echo number_format($inv['precio'], 0, ',', '.'); ?></td>
                                    <td><span
                                            class="badge badge--<?php echo $inv['estado'] == 'activo' ? 'success' : 'warning'; ?>"><?php echo ucfirst($inv['estado']); ?></span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($inv['fecha_publicacion'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#607050; padding:40px;">No hay inventario
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Permisos -->
        <div id="permisos" style="display:none;">
            <div class="panel-section">
                <div class="panel-section__header">
                    <h3 class="panel-section__title">Permisos Gubernamentales</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Inmueble</th>
                            <th>Tipo Permiso</th>
                            <th>Estado</th>
                            <th>Solicitud</th>
                            <th>Vencimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($permisos_all)): ?>
                            <?php foreach ($permisos_all as $pe): ?>
                                <tr>
                                    <td><?php echo ucfirst($pe['tipo_inmueble']) . ' - ' . htmlspecialchars($pe['direccion']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($pe['tipo_permiso']); ?></td>
                                    <td><span class="badge badge--<?php
                                    $badge_map = ['aprobado' => 'success', 'pendiente' => 'warning', 'rechazado' => 'danger'];
                                    echo $badge_map[$pe['estado']] ?? 'info';
                                    ?>"><?php echo ucfirst($pe['estado']); ?></span></td>
                                    <td><?php echo date('d/m/Y', strtotime($pe['fecha_solicitud'])); ?></td>
                                    <td><?php echo $pe['fecha_vencimiento'] ? date('d/m/Y', strtotime($pe['fecha_vencimiento'])) : '—'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#607050; padding:40px;">No hay permisos
                                    registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        function mostrar(seccion, elemento) {
            const secciones = ['dashboard', 'agentes', 'clientes', 'inmuebles', 'reservas', 'facturas', 'inventario', 'permisos'];
            secciones.forEach(s => {
                const el = document.getElementById(s);
                if (el) el.style.display = 'none';
            });
            const target = document.getElementById(seccion);
            if (target) target.style.display = 'block';

            document.querySelectorAll('.sidebar__menu a').forEach(a => a.classList.remove('active'));
            if (elemento) elemento.classList.add('active');
        }

        // ── Property Modal Logic ──
        let currentModalImages = [];
        let currentImageIndex = 0;

        function openPropertyModalFromData(data) {
            const modal = document.getElementById('propertyModal');
            document.body.style.overflow = 'hidden';

            document.getElementById('modalDesc').textContent = data.desc;
            document.getElementById('modalBeds').textContent = data.beds || '0';
            document.getElementById('modalBaths').textContent = data.baths || '0';
            document.getElementById('modalArea').textContent = data.area || '0';
            document.getElementById('modalPrice').textContent = '$' + data.price;

            const waButton = document.getElementById('modalWhatsapp');
            if (waButton) {
                if (data.phone) {
                    const phone = data.phone;
                    const formattedPhone = phone.startsWith('3') ? '57' + phone : phone;
                    const message = encodeURIComponent(`Hola, quisiera más información sobre este inmueble.`);
                    waButton.href = `https://wa.me/${formattedPhone}?text=${message}`;
                    waButton.style.display = 'inline-flex';
                } else {
                    waButton.style.display = 'none';
                }
            }

            try {
                currentModalImages = JSON.parse(data.images || '[]');
            } catch (e) {
                currentModalImages = [];
            }

            // Ajuste de ruta para admin
            if (!Array.isArray(currentModalImages) || currentModalImages.length === 0) {
                currentModalImages = ['<?php echo URL_ROOT; ?>/assets/img/logo.png'];
            } else {
                currentModalImages = currentModalImages.map(img => '<?php echo URL_ROOT; ?>/' + img);
            }
            currentImageIndex = 0;
            updateModalImage();

            modal.classList.add('active');
        }

        function closePropertyModal() {
            document.getElementById('propertyModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        function updateModalImage() {
            const imgEl = document.getElementById('modalImage');
            if (currentModalImages[currentImageIndex]) {
                imgEl.src = currentModalImages[currentImageIndex];
            }
        }

        function prevModalImage() {
            if (currentModalImages.length <= 1) return;
            currentImageIndex = (currentImageIndex - 1 + currentModalImages.length) % currentModalImages.length;
            updateModalImage();
        }

        function nextModalImage() {
            if (currentModalImages.length <= 1) return;
            currentImageIndex = (currentImageIndex + 1) % currentModalImages.length;
            updateModalImage();
        }
    </script>

    <!-- ═══════ PROPERTY MODAL ═══════ -->
    <div id="propertyModal" class="property-modal">
        <div class="property-modal__content">
            <button class="property-modal__close" onclick="closePropertyModal()">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <div class="property-modal__brand">
                <img src="<?php echo URL_ROOT; ?>/assets/img/logo.png" alt="BRE Logo">
                Boyaca Real Estate
            </div>

            <div class="property-modal__grid">
                <div class="property-modal__carousel">
                    <button class="carousel__btn carousel__btn--prev" onclick="prevModalImage()">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <div class="carousel__image-container">
                        <img id="modalImage" src="<?php echo URL_ROOT; ?>/assets/img/logo.png" alt="Property Image">
                    </div>
                    <button class="carousel__btn carousel__btn--next" onclick="nextModalImage()">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>

                <div class="property-modal__details">
                    <h3 class="property-modal__title">Detalles del Inmueble</h3>
                    <p id="modalDesc" class="property-modal__desc"></p>
                    <div class="property-modal__features">
                        <div class="modal-feature"><span id="modalBeds"></span> Hab</div>
                        <div class="modal-feature"><span id="modalBaths"></span> Baños</div>
                        <div class="modal-feature"><span id="modalArea"></span> m2</div>
                    </div>
                    <div class="property-modal__cta">
                        <a id="modalWhatsapp" href="#" target="_blank" class="btn btn--outline btn--whatsapp">Contactar
                            Agente</a>
                    </div>
                    <div class="property-modal__price-wrapper">
                        <div id="modalPrice" class="property-modal__price"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>