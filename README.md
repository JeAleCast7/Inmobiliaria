# 🏠 Boyacá Real Estate — Sistema de Gestión Inmobiliaria

Plataforma web profesional para la gestión integral de propiedades inmobiliarias. Permite administrar inmuebles, agendar visitas, gestionar carteras de clientes y agentes, y simular transacciones mediante una **pasarela de pagos ficticia con billetera digital**.

---

## 🚀 Tecnologías

| Capa | Tecnología |
|---|---|
| Backend | PHP ≥ 7.4 (optimizado para 8.2.12) |
| Servidor Web | Apache 2.4 con `mod_rewrite` |
| Base de Datos | MariaDB 10.4.32 / MySQL |
| Acceso a BD | PDO (Prepared Statements) |
| Frontend | HTML5, CSS3 personalizado, JavaScript nativo |
| Almacenamiento de fotos | Cloudinary (API REST con firma SHA-1) |
| Contenerización | Docker & Docker Compose |
| Gestión de BD alternativa | phpMyAdmin |

---

## ⚙️ Instalación y Ejecución

### Opción A — Docker (Recomendado, Plug & Play)

Requiere tener [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado.

> **Nota:** Si tienes XAMPP corriendo, apaga el servicio de MySQL antes de levantar Docker para evitar conflictos en el puerto 3306.

```bash
# 1. Clonar el repositorio
git clone https://github.com/JeAleCast7/Inmobiliaria.git
cd Inmobiliaria/Inmobiliaria

# 2. Levantar todos los servicios en segundo plano
docker-compose up -d
```

| Servicio | URL |
|---|---|
| Aplicación web | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 (usuario: `root`, sin contraseña) |
| MariaDB | `localhost:3306` |

```bash
# Detener sin borrar datos
docker-compose stop

# Destruir contenedores Y volúmenes (borra la BD)
docker-compose down -v

# Ver logs en tiempo real
docker-compose logs -f web
```

La base de datos se inicializa automáticamente al primer arranque desde `db_init/init.sql` (incluye estructura, usuarios demo y propiedades de ejemplo).

---

### Opción B — XAMPP (Servidor local tradicional)

**Requisitos:** XAMPP con PHP 8.2 y MariaDB 10.4 activos.

1. Clona o copia el repositorio dentro de `C:\xampp\htdocs\Inmobiliaria`.
2. Copia el archivo de configuración y edítalo:
   ```
   cp Inmobiliaria/.env.example Inmobiliaria/.env
   ```
   Variables clave del `.env`:
   ```ini
   DB_HOST=localhost
   DB_NAME=inmobiliaria_db
   DB_USER=root
   DB_PASS=
   APP_URL=http://localhost/Inmobiliaria/Inmobiliaria/public/
   ```
3. Importa la base de datos en phpMyAdmin desde:
   - `Inmobiliaria/database/inmobiliaria_db.sql` (estructura + datos semilla)
4. Accede a la aplicación en:
   ```
   http://localhost/Inmobiliaria/Inmobiliaria/public/
   ```

---

## 🗂️ Arquitectura del Proyecto (MVC Personalizado)

Framework MVC ligero en PHP puro, sin dependencias externas como Laravel o Symfony.

```
Inmobiliaria/
├── app/
│   ├── Config/                  # Configuraciones de la aplicación
│   ├── Controllers/
│   │   ├── AdminController.php  # Panel administrativo global (CRUD inmuebles/agentes)
│   │   ├── AgenteController.php # Panel agente: reservas, habilitación de pagos, billetera
│   │   ├── AuthController.php   # Login, Registro, Logout
│   │   ├── ClienteController.php# Panel cliente: catálogo, pasarela de pago, recibo
│   │   ├── HomeController.php   # Portal público
│   │   └── PropertyController.php
│   ├── Core/
│   │   ├── Database.php         # Singleton PDO
│   │   ├── Router.php           # Enrutador HTTP → Controlador
│   │   ├── BaseController.php   # Helpers: view(), redirect(), json()
│   │   └── Autoloader.php       # Autocargador PSR-4
│   ├── Models/
│   │   └── User.php
│   ├── Services/                # Lógica desacoplada e integraciones
│   └── Views/
│       ├── admin/               # Vistas del administrador
│       ├── agente/              # Vistas del agente
│       ├── auth/                # Login y registro
│       ├── cliente/             # Dashboard, pasarela de pago, recibo
│       ├── portal/              # Catálogo público
│       └── layout/              # Componentes reutilizables
├── public/
│   ├── index.php                # Front Controller (entrada única)
│   ├── .htaccess                # URLs amigables con mod_rewrite
│   └── assets/css/              # Hojas de estilo globales
├── database/                    # Scripts SQL complementarios
├── db_init/init.sql             # Inicializador automático para Docker
├── documentation/               # Docs técnicos: BD, pasarela, Docker
├── Dockerfile                   # Imagen PHP + Apache
├── Dockerfile.db                # Imagen MariaDB personalizada
├── docker-compose.yml
└── composer.json
```

---

## 👥 Roles y Funcionalidades

### 🔑 Administrador
- Dashboard con estadísticas globales (clientes, agentes, inmuebles, reservas, facturas)
- **CRUD completo de inmuebles**: crear, editar y eliminar propiedades con subida de fotos a Cloudinary
- **CRUD de agentes**: registrar, editar, activar/desactivar y eliminar agentes (con billetera automática)
- Gestión de clientes: listado completo y eliminación en cascada
- Visualización de reservas, facturas, inventario y permisos de gobierno
- Descarga de recibos de pagos completados

### 🏢 Agente
- Vista de sus inmuebles asignados con galería de fotos
- **Agendar reservas de visita**: asigna un cliente a un inmueble con fecha y hora opcional
- **Habilitar pago ficticio**: genera una factura pendiente para que el cliente pueda pagar
- Billetera virtual: visualiza saldo de comisiones (10% por cada pago completado) y movimientos
- Panel de reservas pendientes con estado actualizado

### 🧑‍💼 Cliente
- Catálogo de propiedades disponibles con filtros y modal de detalle
- **Mis Reservas**: seguimiento de visitas agendadas por su agente
- **Mis Facturas**: listado de facturas pendientes y pagadas
- **Pasarela de pago ficticia**: simula el pago de una propiedad
- Billetera con historial de movimientos
- Recibo de pago descargable/imprimible (PDF)
- Auto-registro desde el portal público

---

## 💳 Flujo de Pago (End-to-End)

```
Agente: Habilitar Pago (genera factura pendiente)
   ↓
Cliente: Mis Facturas → Pagar
   ↓
Pasarela ficticia → Confirmar
   ↓
✅ Factura marcada como "pagada"
✅ Inmueble e inventario actualizados a "vendido" o "arrendado"
✅ Movimiento "pagado" registrado en billetera del cliente
✅ Comisión (10%) acreditada en billetera del agente
✅ Recibo generado
```

---

## 🗄️ Esquema de Base de Datos

| Tabla | Descripción |
|---|---|
| `usuarios` | Credenciales y rol (`admin`, `agente`, `cliente`) |
| `clientes` | Perfil del cliente vinculado a `usuarios` |
| `agentes` | Perfil del agente con cargo y vinculación a inmobiliaria |
| `administrador` | Relación 1:1 con `usuarios` para el rol admin |
| `inmuebles` | Propiedades: tipo, precio, área, habitaciones, estado |
| `inventario` | Publicación: tipo de operación, precio, fotos (JSON), estado |
| `reservas` | Visitas: cliente + inmueble + agente + fecha_visita |
| `facturas` | Transacciones pendientes o pagadas |
| `billetera` | Monedero digital del cliente |
| `billetera_agente` | Monedero de comisiones del agente |
| `movimientos_billetera` | Historial de pagos del cliente |
| `movimientos_billetera_agente` | Historial de comisiones del agente |
| `gobierno` | Permisos legales de los inmuebles |

---

## 🔐 Credenciales de Prueba

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | `jesus@adminbre.com` | `admin123` |
| Agente Senior | `carlos@agentebre.com` | `agente123` |
| Agente de Ventas | `laura@agentebre.com` | `agente123` |
| Cliente | `larva@gmail.com` | `1234567` |
| Cliente | `papu@gmail.com` | `papu777` |

> ⚠️ Las contraseñas se almacenan en texto plano por requerimiento del proyecto académico. No usar en producción.

---

## 🧪 Flujo de Prueba Completo

1. **Agente** (`carlos@agentebre.com`) → **Agendar Reserva**: seleccionar cliente e inmueble, indicar fecha/hora de visita opcional.
2. **Agente** → **Habilitar Pago**: seleccionar el mismo cliente y propiedad para generar la factura.
3. **Cliente** (`larva@gmail.com`) → **Mis Reservas**: verificar que aparece la reserva con estado `pendiente`.
4. **Cliente** → **Dashboard** → localizar la factura `Pendiente` → pulsar **Pagar**.
5. Completar el formulario de la pasarela ficticia y confirmar.
6. Verificar redirección al recibo y opción de imprimir/guardar PDF.
7. **Cliente** → **Mi Billetera**: confirmar que aparece el movimiento `pagado`.
8. **Agente** → **Mi Billetera**: verificar que el saldo aumentó con la comisión del 10%.
9. Verificar que el inmueble ya **no aparece** en el catálogo de disponibles (estado actualizado a `vendido` o `arrendado`).

---

## 📦 Docker Hub

Las imágenes preconfiguradas están publicadas en Docker Hub:

- **Web (PHP + Apache):** `jealecast7/inmobiliaria:v1.1`
- **Base de datos (MariaDB + datos):** `jealecast7/inmobiliaria-db:v1.1`
