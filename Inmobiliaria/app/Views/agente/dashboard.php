<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Agente — Boyaca Real Estate</title>
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
                <div class="sidebar__subtitle">Panel Agente</div>
            </div>
        </div>

        <div class="sidebar__user">
            <div class="sidebar__user-name"><?php echo htmlspecialchars($_SESSION['nombre']); ?></div>
            <div class="sidebar__user-role">Agente — <?php echo htmlspecialchars($agente['cargo']); ?></div>
        </div>

        <div class="sidebar__label">Menú</div>
        <ul class="sidebar__menu">
            <li><a class="active" onclick="mostrar('dashboard', this)"><span class="icon">🏠</span> Dashboard</a></li>
            <li><a onclick="mostrar('inmuebles', this)"><span class="icon">🏢</span> Mis Inmuebles</a></li>
            <li><a onclick="mostrar('reservas', this)"><span class="icon">📋</span> Mis Reservas</a></li>
            <li><a onclick="mostrar('billetera', this)"><span class="icon">💳</span> Mi Billetera</a></li>
        </ul>

        <div class="sidebar__logout">
            <a href="<?php echo URL_ROOT; ?>/logout"><span class="icon">🚪</span> Cerrar Sesión</a>
        </div>
    </aside>

    <main class="main">
        <div class="main__header">
            <h1>Hola, <?php echo htmlspecialchars($agente['nombre']); ?></h1>
            <p>Este es tu panel de agente. Gestiona tus inmuebles, reservas y billetera.</p>
        </div>

        <div id="dashboard">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card__icon">🏢</div>
                    <div class="stat-card__number"><?php echo $mis_inmuebles; ?></div>
                    <div class="stat-card__label">Mis Inmuebles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon">📋</div>
                    <div class="stat-card__number"><?php echo $mis_reservas; ?></div>
                    <div class="stat-card__label">Reservas Pendientes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon">💰</div>
                    <div class="stat-card__number"><?php echo $mis_ventas; ?></div>
                    <div class="stat-card__label">Ventas Realizadas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon">💳</div>
                    <div class="stat-card__number">
                        $<?php echo $billetera ? number_format($billetera['saldo'], 0, ',', '.') : '0'; ?></div>
                    <div class="stat-card__label">Saldo Billetera</div>
                </div>
            </div>
        </div>

        <!-- Mis Inmuebles — Catálogo -->
        <div id="inmuebles" style="display:none;">
            <div class="panel-section">
                <div class="panel-section__header">
                    <h3 class="panel-section__title">Mis Inmuebles Asignados</h3>
                </div>
                <?php
                $iconos_tipo = [
                    'casa' => '🏠',
                    'apartamento' => '🏢',
                    'oficina' => '🏛️',
                    'local' => '🏪',
                    'lote' => '📐',
                    'bodega' => '🏭'
                ];
                ?>
                <div class="catalog-grid">
                    <?php
                    if (!empty($inmuebles)):
                        foreach ($inmuebles as $im):
                            $icono = $iconos_tipo[$im['tipo']] ?? '🏠';
                            $tipo_op = $im['tipo_operacion'] ?? 'venta';
                            $precio = $im['precio_pub'] ?? $im['precio'];
                            ?>
                            <article class="catalog-card" style="cursor:pointer;" onclick="openPropertyModalFromData({
                    desc: '<?php echo addslashes($im['descripcion']); ?>',
                    price: '<?php echo number_format($im['precio'], 0, ',', '.'); ?>',
                    beds: '<?php echo $im['habitaciones']; ?>',
                    baths: '<?php echo $im['banos']; ?>',
                    area: '<?php echo $im['area_m2']; ?>',
                    phone: '<?php echo $agente['telefono']; ?>',
                    images: '<?php echo str_replace("'", "\\'", $im['fotos'] ?? '[]'); ?>'
                })">
                                <div class="catalog-card__image">
                                    <?php echo $icono; ?>
                                    <span class="catalog-card__badge catalog-card__badge--<?php echo $tipo_op; ?>">
                                        <?php echo ucfirst($tipo_op); ?>
                                    </span>
                                </div>
                                <div class="catalog-card__body">
                                    <div class="catalog-card__type"><?php echo ucfirst($im['tipo']); ?></div>
                                    <h3 class="catalog-card__title"><?php echo htmlspecialchars($im['descripcion']); ?></h3>
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
                                        <?php if ($tipo_op === 'arriendo'): ?>
                                            <small>/mes</small>
                                        <?php endif; ?>
                                    </div>
                                    <div style="margin-top:10px;">
                                        <span
                                            class="badge badge--<?php echo $im['estado'] == 'disponible' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($im['estado']); ?>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; else: ?>
                        <p style="text-align:center; color:#607050; grid-column:1/-1; padding:40px;">No tienes inmuebles
                            asignados</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Reservas -->
        <div id="reservas" style="display:none;">
            <div class="panel-section">
                <div class="panel-section__header">
                    <h3 class="panel-section__title">Mis Reservas</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Teléfono</th>
                            <th>Inmueble</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($reservas)): ?>
                            <?php foreach ($reservas as $r): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($r['cliente_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($r['cliente_tel']); ?></td>
                                    <td><?php echo ucfirst($r['tipo_inmueble']) . ' - ' . htmlspecialchars($r['direccion']); ?>
                                    </td>
                                    <td><span class="badge badge--<?php
                                    $badge_map = ['pendiente' => 'warning', 'confirmada' => 'success', 'cancelada' => 'danger'];
                                    echo $badge_map[$r['estado']] ?? 'info';
                                    ?>"><?php echo ucfirst($r['estado']); ?></span></td>
                                    <td><?php echo date('d/m/Y', strtotime($r['fecha_reserva'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#607050; padding:40px;">No tienes reservas
                                    asignadas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Billetera -->
        <div id="billetera" style="display:none;">
            <div class="stats-grid" style="margin-bottom:24px;">
                <div class="stat-card">
                    <div class="stat-card__icon">💰</div>
                    <div class="stat-card__number">
                        $<?php echo $billetera ? number_format($billetera['saldo'], 0, ',', '.') : '0'; ?></div>
                    <div class="stat-card__label">Saldo Disponible</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon">✅</div>
                    <div class="stat-card__number"><?php echo $billetera ? ucfirst($billetera['estado']) : 'N/A'; ?>
                    </div>
                    <div class="stat-card__label">Estado</div>
                </div>
            </div>
            <div class="panel-section">
                <div class="panel-section__header">
                    <h3 class="panel-section__title">Últimos Movimientos</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($movimientos)): ?>
                            <?php foreach ($movimientos as $m): ?>
                                <tr>
                                    <td><span
                                            class="badge badge--<?php echo $m['tipo'] == 'comision' ? 'success' : ($m['tipo'] == 'bonificacion' ? 'info' : 'warning'); ?>"><?php echo ucfirst(str_replace('_', ' ', $m['tipo'])); ?></span>
                                    </td>
                                    <td>$<?php echo number_format($m['monto'], 0, ',', '.'); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($m['fecha'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align:center; color:#607050; padding:40px;">Sin movimientos
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
            ['dashboard', 'inmuebles', 'reservas', 'billetera'].forEach(s => {
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
                    const formattedPhone = data.phone.startsWith('3') ? '57' + data.phone : data.phone;
                    const message = encodeURIComponent(`Hola, quisiera más información.`);
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