# Refactorización del Catálogo Inmobiliario (API Dinámica)

Este plan describe cómo transformar el catálogo de propiedades actual para que responda a un modelo de "Datos Reales mediante API". En lugar de tener código PHP incrustado directamente en el HTML de la vista y mezclar estructura con lógica de datos, separaremos esto para que el frontend "solo llame los datos" y dibuje las tarjetas.

## Cambio de Paradigma

Actualmente, el archivo `index_view.html` está siendo procesado por PHP a través de un `include` en `index.php`, lo que permite que el ciclo de propiedades renderice el HTML. Transformaremos esto para que el HTML sea completamente limpio y agnóstico, delegando a un script en Javascript el consumo de los datos que provea la Base de Datos a través de un "Endpoint" o API. Esto hará que el catálogo sea **totalmente reutilizable** en cualquier otra página solo al añadir un `div` contenedor y el script.

## User Review Required

> [!IMPORTANT]
> **Aprobación de la separación de responsabilidades (Backend vs Frontend):** 
> Al implementar esta arquitectura, vamos a eliminar el ciclo `while` de PHP que está dentro de `index_view.html`. A cambio, crearemos un nuevo archivo de PHP exclusivo para obtener datos (JSON) y un código JavaScript que construirá el HTML del catálogo directamente en el navegador de los usuarios a partir de esos datos.
> **Por favor, aprueba este enfoque para continuar.**

## Proposed Changes

---

### Backend / API

#### [NEW] [api/obtener_propiedades.php](file:///c:/xampp/htdocs/Inmobiliaria/Inmobiliaria/api/obtener_propiedades.php)
Crearemos este archivo con la responsabilidad exclusiva de conectar a la base de datos (reutilizando tu `conexion.php`), ejecutar la consulta SQL que ya existe en `index.php` para obtener las propiedades, y devolver los resultados estructurados en un formato JSON (JavaScript Object Notation). 

#### [MODIFY] [index.php](file:///c:/xampp/htdocs/Inmobiliaria/Inmobiliaria/index.php)
Dado que ahora el frontend (HTML) llamará los datos mediante JavaScript de forma asíncrona, ya no será necesario que `index.php` consulte la base de datos antes de cargar la vista. Se simplificará `index.php` para que su única función sea redirigir o cargar el `index_view.html`. 

---

### Componentes Frontend (Vista y Javascript)

#### [MODIFY] [index_view.html](file:///c:/xampp/htdocs/Inmobiliaria/Inmobiliaria/index_view.html)
- **Borrado del PHP incrustado:** Vamos a retirar toda la lógica de servidor (tags `<?php ... ?>`) usada en la sección del catálogo (líneas 92 en adelante). 
- **Contenedor limpio:** Dejaremos un elemento HTML limpio `<div id="properties-grid" class="properties-grid"></div>` que actuará como el "lienzo" donde JS insertará los datos. 
- **Integración JS:** Agregaremos las funciones de JavaScript (usando el API `fetch()`) para llamar los datos de `api/obtener_propiedades.php` de forma asíncrona.
- **Mantener Funcionalidad (Modal Interactivo):** Tras obtener el JSON, JavaScript armará exactamente el mismo diseño de "Tarjeta" (`<article class="property-card fade-in" onclick="openPropertyModal(this)" ...>`) asignando correctamente los atributos para garantizar que la vista en detalle (el carrusel de fotos, el contacto a Whatsapp y los precios) funcione sin alterar.

## Open Questions

> [!WARNING]
> 1. Actualmente el límite de propiedades destacadas de inicio es de 6 (según la consulta en `index.php`: `LIMIT 6`). ¿Mantengo ese límite por defecto en la nueva API?
> 2. Una vez que este catálogo sea reutilizable, cualquier archivo HTML podrá cargar este catálogo tan solo con incluir un script de JS. ¿Tienes planeado crear una página dedicada de "Propiedades" (que muestre todas sin el límite de 6)? Puedo prepararlo para que opcionalmente reciba un parámetro y muestre más inmuebles si lo deseas en un futuro.

## Verification Plan

### Manual Verification
1. Ingresaremos a la aplicación raíz `index.php`. 
2. Observaremos desde el Inspector del Navegador (pestaña Red/Network) que se hace una petición automática a `api/obtener_propiedades.php`.
3. Verificaremos que el diseño de las tarjetas (estilos CSS) permanezca idéntico y sin ninguna ruptura en el layout, usando flex / CSS Grid actual.
4. Haremos click en una de las tarjetas generadas dinámicamente y aseguraremos que el modal (pantalla superpuesta) de detalle abra correctamente. 
5. Se comprobará que la cuenta de imágenes del carrusel del modal y las interacciones con Whatsapp sean exitosas y exactas a como está operando actualmente.
