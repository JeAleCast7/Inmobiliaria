<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boyacá Real Estate — Tu Hogar Ideal</title>
    <meta name="description"
        content="Encuentra tu hogar ideal con Boyaca Real Estate. Casas, apartamentos, oficinas y locales comerciales en las mejores ubicaciones de Bogotá.">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/assets/css/estilos.css?v=<?php echo time(); ?>">
</head>

<body>

    <!-- ═══════ NAVBAR ═══════ -->
    <nav class="navbar" id="navbar">
        <a href="<?php echo URL_ROOT; ?>/" class="navbar__logo">
            <div class="navbar__logo-icon">
                <!-- Logo PNG: reemplazar src con la ruta de tu logo -->
                <img src="<?php echo URL_ROOT; ?>/assets/img/logo.png" alt="BRE Logo">
            </div>
            <div class="navbar__logo-text">Boyacá <span>Real Estate</span></div>
        </a>

        <ul class="navbar__menu" id="navMenu">
            <li><a href="#inicio" class="navbar__link">Inicio</a></li>
            <li><a href="#propiedades" class="navbar__link">Propiedades</a></li>
            <li><a href="#nosotros" class="navbar__link">Nosotros</a></li>
            <li><a href="#contacto" class="navbar__link">Contacto</a></li>
        </ul>

        <div class="navbar__actions">
            <a href="<?php echo URL_ROOT; ?>/login" class="btn btn--outline">Iniciar Sesión</a>
            <a href="<?php echo URL_ROOT; ?>/register" class="btn btn--primary">Registrarse</a>
        </div>

        <button class="navbar__toggle" id="navToggle" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </button>
    </nav>

    <!-- ═══════ HERO ═══════ -->
    <section class="hero" id="inicio">
        <div class="hero__bg-pattern"></div>
        <div class="hero__grid"></div>
        <div class="hero__content">
            <div class="hero__badge">
                <span class="hero__badge-dot"></span>
                Propiedades exclusivas disponibles
            </div>
            <h1 class="hero__title">
                Encuentra tu <span>hogar ideal</span> con nosotros
            </h1>
            <p class="hero__subtitle">
                Somos expertos en bienes raíces. Te acompañamos en cada paso del proceso
                de compra, venta o arriendo de tu propiedad perfecta.
            </p>
            <div class="hero__buttons">
                <a href="#propiedades" class="btn btn--primary btn--large">Ver Propiedades</a>
                <a href="#contacto" class="btn btn--outline btn--large">Contáctanos</a>
            </div>
            <div class="hero__stats">
                <div class="hero__stat">
                    <div class="hero__stat-number">80+</div>
                    <div class="hero__stat-label">Propiedades</div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-number">50+</div>
                    <div class="hero__stat-label">Clientes</div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-number">5+</div>
                    <div class="hero__stat-label">Años de Experiencia</div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-number">98%</div>
                    <div class="hero__stat-label">Satisfacción</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════ PROPIEDADES ═══════ -->
    <section class="section" id="propiedades">
        <div class="section__header">
            <span class="section__label">Catálogo</span>
            <h2 class="section__title">Propiedades Destacadas</h2>
            <p class="section__desc">Explora nuestra selección de propiedades premium en las mejores ubicaciones</p>
        </div>

        <div class="properties-grid" id="properties-grid-container">
            <!-- El catálogo se cargará aquí dinámicamente mediante JS -->
        </div>
    </section>

    <!-- ═══════ NOSOTROS ═══════ -->
    <section class="section about" id="nosotros">
        <div class="section__header">
            <span class="section__label">Sobre Nosotros</span>
            <h2 class="section__title">¿Por qué elegirnos?</h2>
            <p class="section__desc">Más de una década de experiencia respaldando tus decisiones inmobiliarias</p>
        </div>

        <div class="about__grid">
            <div class="about__visual">🏗️</div>
            <div class="about__content">
                <h3>Tu aliado en bienes raíces</h3>
                <p>
                    En Boyaca Real Estate contamos con un equipo de agentes profesionales dedicados
                    a encontrar la propiedad perfecta para ti. Nuestro compromiso es brindarte un
                    servicio personalizado y transparente.
                </p>
                <p>
                    Desde casas familiares hasta oficinas corporativas, manejamos un portafolio
                    diverso para satisfacer todas tus necesidades inmobiliarias.
                </p>
                <div class="about__features">
                    <div class="about__feature">
                        <div class="about__feature-icon">🔒</div>
                        <span>Transacciones seguras</span>
                    </div>
                    <div class="about__feature">
                        <div class="about__feature-icon">⚡</div>
                        <span>Respuesta rápida</span>
                    </div>
                    <div class="about__feature">
                        <div class="about__feature-icon">📋</div>
                        <span>Asesoría legal incluida</span>
                    </div>
                    <div class="about__feature">
                        <div class="about__feature-icon">💎</div>
                        <span>Propiedades verificadas</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════ CONTACTO ═══════ -->
    <section class="section contact" id="contacto">
        <div class="section__header">
            <span class="section__label">Contacto</span>
            <h2 class="section__title">¿Tienes preguntas?</h2>
            <p class="section__desc">Escríbenos y te responderemos a la brevedad</p>
        </div>

        <div class="contact__grid">
            <div class="contact__info">
                <div class="contact__item">
                    <div class="contact__item-icon">📍</div>
                    <div>
                        <h4>Dirección</h4>
                        <p>Calle 100 #15-25, Bogotá, Colombia</p>
                    </div>
                </div>
                <div class="contact__item">
                    <div class="contact__item-icon">📞</div>
                    <div>
                        <h4>Teléfono</h4>
                        <p>601-555-1234</p>
                    </div>
                </div>
                <div class="contact__item">
                    <div class="contact__item-icon">✉️</div>
                    <div>
                        <h4>Email</h4>
                        <p>contacto@bre.com</p>
                    </div>
                </div>
                <div class="contact__item">
                    <div class="contact__item-icon">🕐</div>
                    <div>
                        <h4>Horario</h4>
                        <p>Lun - Vie: 8:00 AM - 6:00 PM<br>Sáb: 9:00 AM - 1:00 PM</p>
                    </div>
                </div>
            </div>
            <form class="contact__form" action="#" method="POST">
                <input type="text" name="nombre" placeholder="Tu nombre completo" required>
                <input type="email" name="email" placeholder="Tu correo electrónico" required>
                <input type="text" name="asunto" placeholder="Asunto">
                <textarea name="mensaje" placeholder="Escribe tu mensaje aquí..." required></textarea>
                <button type="submit" class="btn btn--primary btn--large">Enviar Mensaje</button>
            </form>
        </div>
    </section>

    <!-- ═══════ FOOTER ═══════ -->
    <footer class="footer">
        <div class="footer__grid">
            <div class="footer__brand">
                <a href="<?php echo URL_ROOT; ?>/" class="navbar__logo">
                    <div class="navbar__logo-icon">
                        <img src="<?php echo URL_ROOT; ?>/assets/img/logo.png" alt="BRE Logo">
                    </div>
                    <div class="navbar__logo-text">Boyacá <span>Real Estate</span></div>
                </a>
                <p>Tu aliado de confianza en bienes raíces. Más de 5 años ayudando a familias a encontrar su hogar
                    ideal.</p>
            </div>
            <div class="footer__col">
                <h4>Navegación</h4>
                <a href="#inicio">Inicio</a>
                <a href="#propiedades">Propiedades</a>
                <a href="#nosotros">Nosotros</a>
                <a href="#contacto">Contacto</a>
            </div>
            <div class="footer__col">
                <h4>Servicios</h4>
                <a href="#">Venta de inmuebles</a>
                <a href="#">Arriendo</a>
                <a href="#">Asesoría legal</a>
                <a href="#">Avalúos</a>
            </div>
            <div class="footer__col">
                <h4>Acceso</h4>
                    <li><a href="<?php echo URL_ROOT; ?>/login">Login</a></li>
                    <li><a href="<?php echo URL_ROOT; ?>/register">Registro</a></li>
            </div>
        </div>
        <div class="footer__bottom">
            &copy;
            <?php echo date('Y'); ?> Boyaca Real Estate. Todos los derechos reservados.
        </div>
    </footer>

    <script>
        // Configuración Global desde PHP
        const URL_ROOT = '<?php echo URL_ROOT; ?>';

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Mobile menu toggle
        function toggleMenu() {
            document.getElementById('navMenu').classList.toggle('active');
        }

        // Scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    document.getElementById('navMenu').classList.remove('active');
                }
            });
        });

        // Property Modal Logic
        let currentModalImages = [];
        let currentImageIndex = 0;

        function openPropertyModal(card) {
            const modal = document.getElementById('propertyModal');
            document.body.style.overflow = 'hidden';

            document.getElementById('modalDesc').textContent = card.getAttribute('data-desc');
            document.getElementById('modalBeds').textContent = card.getAttribute('data-beds') || '0';
            document.getElementById('modalBaths').textContent = card.getAttribute('data-baths') || '0';
            document.getElementById('modalArea').textContent = card.getAttribute('data-area') || '0';
            document.getElementById('modalPrice').textContent = '$' + card.getAttribute('data-price');

            const phone = card.getAttribute('data-phone');
            const title = card.getAttribute('data-title');
            const waButton = document.getElementById('modalWhatsapp');
            // PROTECCIÓN ADBLOCKER (Brave Shields)
            // Si el bloqueador destruyó el botón de WhatsApp (waButton = null), el condicional
            // impide que el sistema se quiebre, permitiendo que la ventana de la propiedad siga operando.
            if (waButton) {
                if (phone) {
                    const formattedPhone = phone.startsWith('3') ? '57' + phone : phone;
                    const message = encodeURIComponent(`Hola, quisiera más información sobre este inmueble: ${title}`);
                    waButton.href = `https://wa.me/${formattedPhone}?text=${message}`;
                    waButton.style.display = 'inline-flex';
                } else {
                    waButton.style.display = 'none';
                }
            }

            try {
                currentModalImages = JSON.parse(card.getAttribute('data-images') || '[]');
            } catch (e) {
                currentModalImages = [];
            }
            if (!Array.isArray(currentModalImages) || currentModalImages.length === 0) {
                currentModalImages = [`${URL_ROOT}/assets/img/logo.png`];
            } else {
                currentModalImages = currentModalImages.map(img => img.startsWith('http') ? img : `${URL_ROOT}/${img}`);
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

        // --- LÓGICA DE CATÁLOGO DINÁMICO ---
        function escapeHtml(unsafe) {
            if (!unsafe) return "";
            return unsafe
                .toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        async function loadDynamicCatalog() {
            const container = document.getElementById('properties-grid-container');
            if (!container) return;

            try {
                const response = await fetch('<?php echo URL_ROOT; ?>/api/properties/featured');
                const data = await response.json();

                if (data.error || data.length === 0) {
                    container.innerHTML = '<p style="text-align:center; color: var(--color-text-muted); grid-column: 1 / -1;">No hay propiedades disponibles en este momento.</p>';
                    return;
                }

                const iconos_tipo = {
                    'casa': '',
                    'apartamento': '🏢',
                    'oficina': '💼',
                    'local': '🏪',
                    'lote': '🏞️',
                    'bodega': '🏭'
                };

                let htmlContent = '';

                data.forEach(prop => {
                    const icono = iconos_tipo[prop.tipo] || '';
                    const tipo_badge = prop.tipo_operacion || 'venta';
                    const precio = prop.precio_publicado || prop.precio;

                    // Formatear precio
                    const formattedPrice = new Intl.NumberFormat('es-CO').format(precio);

                    // Asegurar datos seguros para JSON
                    const fotos_json = prop.fotos ? prop.fotos : '[]';

                    // Extraer la primera foto si existe FOTOS REALES
                    let primera_foto = '';
                    try {
                        const fotos_array = JSON.parse(prop.fotos || '[]');
                        if (fotos_array.length > 0) {
                            primera_foto = fotos_array[0];
                        }
                    } catch (e) {
                        console.error('Error parseando fotos', e);
                    }

                    // Título truncado
                    const truncatedDesc = prop.descripcion.length > 50
                        ? prop.descripcion.substring(0, 50) + '...'
                        : prop.descripcion;

                    const imgPath = primera_foto ? (primera_foto.startsWith('http') ? primera_foto : `${URL_ROOT}/${primera_foto}`) : '';
                    htmlContent += `
                        <article class="property-card fade-in visible" onclick="openPropertyModal(this)"
                            data-title="${escapeHtml(truncatedDesc)}"
                            data-price="${formattedPrice}"
                            data-desc="${escapeHtml(prop.descripcion)}"
                            data-beds="${escapeHtml(prop.habitaciones)}"
                            data-baths="${escapeHtml(prop.banos)}"
                            data-area="${escapeHtml(prop.area_m2)}"
                            data-phone="${escapeHtml(prop.agente_telefono || '')}"
                            data-images="${escapeHtml(fotos_json)}"
                            style="cursor: pointer;">
                            <div class="property-card__image" ${imgPath ? `style="background-image: url('${escapeHtml(imgPath)}'); background-size: cover; background-position: center;"` : ''}>
                                ${imgPath ? '' : icono}
                                <span class="property-card__badge property-card__badge--${escapeHtml(tipo_badge)}">
                                    ${escapeHtml(tipo_badge.charAt(0).toUpperCase() + tipo_badge.slice(1))}
                                </span>
                            </div>
                            <div class="property-card__body">
                                <div class="property-card__type">
                                    ${escapeHtml(prop.tipo.charAt(0).toUpperCase() + prop.tipo.slice(1))}
                                </div>
                                <h3 class="property-card__title">
                                    ${escapeHtml(prop.descripcion)}
                                </h3>
                                <div class="property-card__location">
                                    📍 ${escapeHtml(prop.direccion)}
                                </div>
                                <div class="property-card__features">
                                    ${prop.habitaciones > 0 ? `<span class="property-card__feature">🛏️ ${prop.habitaciones} Hab.</span>` : ''}
                                    ${prop.banos > 0 ? `<span class="property-card__feature">🚿 ${prop.banos} Baños</span>` : ''}
                                    <span class="property-card__feature">📐 ${prop.area_m2} m²</span>
                                    ${prop.estrato > 0 ? `<span class="property-card__feature">⭐ Estrato ${prop.estrato}</span>` : ''}
                                </div>
                                <div class="property-card__price">
                                    $ ${formattedPrice}
                                    ${tipo_badge === 'arriendo' ? '<small>/mes</small>' : ''}
                                </div>
                            </div>
                        </article>
                    `;
                });

                container.innerHTML = htmlContent;

            } catch (error) {
                console.error("Error cargando el catálogo:", error);
                container.innerHTML = '<p style="text-align:center; color: var(--color-text-muted); grid-column: 1 / -1;">Error al cargar las propiedades.</p>';
            }
        }

        // Cargar el catálogo tan pronto como el DOM esté listo
        document.addEventListener('DOMContentLoaded', loadDynamicCatalog);

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
                <!-- Left: Carousel -->
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

                <!-- Right: Details -->
                <div class="property-modal__details">
                    <h3 class="property-modal__title">"Información sobre inmueble"</h3>
                    <p id="modalDesc" class="property-modal__desc"></p>

                    <div class="property-modal__features">
                        <div class="modal-feature"><span id="modalBeds"></span> Hab</div>
                        <div class="modal-feature"><span id="modalBaths"></span> Baños</div>
                        <div class="modal-feature"><span id="modalArea"></span> m2</div>
                    </div>

                    <div class="property-modal__cta">
                        <div class="property-modal__cta-text">
                            ¿Quieres más información<br>o agendar una cita?
                            <svg style="margin-left:10px" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </div>
                        <a id="modalWhatsapp" href="#" target="_blank" class="btn btn--outline btn--whatsapp">
                            Habla con uno de<br>nuestros agentes!
                        </a>
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