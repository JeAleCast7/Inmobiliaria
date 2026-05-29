# Documentación de Pasarela de Pagos (Mockup)

Este documento describe la implementación de la Pasarela de Pagos ficticia en el sistema Inmobiliario (MVC).

## 1. Visión General
Esta funcionalidad permite a los agentes habilitar de manera explícita la opción de pago para los clientes en sus inmuebles. El cliente, al acceder al portal, puede realizar el pago mediante una pasarela ficticia y posteriormente obtener un recibo PDF del pago completado.

## 2. Flujo de Trabajo

### A. Agente
1. El agente inicia sesión y se dirige a la sección **"Habilitar Pago"**.
2. El agente selecciona un **Cliente** y un **Inmueble** (del cual el agente está a cargo).
3. Se crea una **Factura** en la base de datos con el estado `pendiente`.

### B. Cliente
1. El cliente inicia sesión y va a su listado de **Inmuebles** asociados.
2. Si el cliente tiene una factura en estado `pendiente` para ese inmueble, aparecerá un botón de **"Pagar"**.
3. Al hacer clic, se redirige a una **Pasarela de Pago Ficticia** que simula un pago con tarjeta.
4. Tras pagar:
   - El estado de la factura cambia a `pagada`.
   - Se despliega automáticamente un **Recibo en PDF** confirmando el pago y listando el detalle.
   
### C. Facturas
- En la vista de **Mis Facturas** del cliente, este podrá consultar su historial de pagos y descargar el recibo PDF cuando lo desee.

## 3. Actualizaciones de Base de Datos
- **Tabla `facturas`**: Se agregó la columna `estado` ENUM('pendiente', 'pagada') DEFAULT 'pendiente' para poder tener un registro previo al pago real.

## 4. Archivos Creados y/o Modificados

### Controllers
- `AgenteController.php`: Acción `habilitarPago` (y `storeHabilitarPago`).
- `ClienteController.php`: Acciones `pasarelaPago`, `procesarPago`, `misFacturas`, `descargarRecibo`.

### Views
- `app/Views/agente/habilitar_pago.php`: Formulario para el Agente.
- `app/Views/cliente/pasarela_pago.php`: Mockup del formulario de pago.
- `app/Views/cliente/mis_inmuebles.php` (o donde el cliente ve sus inmuebles): Botón de Pagar.
- `app/Views/cliente/mis_facturas.php`: Listado e historial de pago con PDF.

### Librerías de PDF
- Se integró la librería de FPDF/TCPDF o en su defecto DOMPDF (ver el historial en vendor/ o app/Libs) para generar el comprobante PDF.
