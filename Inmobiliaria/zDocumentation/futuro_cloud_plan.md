# Plan de Migración a Cloud para Imágenes de Propiedades

Este documento detalla la arquitectura y los pasos a seguir a futuro para escalar el sistema de imágenes de "Boyacá Real Estate", pasando del almacenamiento local (`uploads/`) a una solución de nube estructurada para alto volumen de datos.

## 1. Almacenamiento en la Nube y CDN

Para asegurar que tu servidor PHP no se congestione sirviendo imágenes cuando incremente el tráfico, todo archivo multimedia debe delegarse externamente.

- **Proveedor Recomendado:** Amazon S3, Cloudinary, o Cloudflare R2.
- **Ventaja de CDN:** Los servicios CDN replicarán tus imágenes en servidores alrededor del mundo, cargándolas instantáneamente y ahorrando todo tu ancho de banda local.
- **Estructura URLs:** Las rutas JSON ya no serán `uploads/foto.png` sino rutas absolutas aseguradas en HTTPS: `https://cdn.boyacarealestate.com/images/casa-id.webp`.

## 2. Modificaciones en Base de Datos

La estructura JSON actual no requiere cambios de tipo de variable ni remodelado (sigue siendo `text`), lo cual certifica que **tu código actual es robusto** y adaptable al cambio.

- Se guardarán los URLs completos devueltos por el proveedor directamente en el JSON. 
- Alternativamente, si se migra a Cloudinary, puedes guardar solo el *identificador público* del archivo y generar las URL dinámicamente en el frontend.

## 3. Resiliencia y Rendimiento en Frontend (HTML/JS)

Cuando comiencen a servirse cientos o miles de tarjetas, el código asíncrono debe asegurar que la estructura "no se rompa":

- **Lazy Loading (Carga Diferida):** Actualmente colocamos la foto real insertando un atributo `background-image` explícito. Para grandes volúmenes de datos, el script debe utilizar la interfaz `IntersectionObserver` de JS, de manera que la imagen solo intente descargarse cuando el usuario haga scroll hacia ella.
- **Fallbacks a Prueba de Fallos:** Si el servicio en la nube falla o una imagen se elimina por error, la vista reaccionará limpiamente sin espacios vacíos.
  - *Implementación sugerida en JS:* Hacer un `new Image()` por detrás, verificar su evento `onload` y `onerror`. Si falla, revertimos al ícono original `🏢` o a un logo predeterminado de la empresa.

## 4. Paginación y Carga desde el Backend (PHP)

Solicitar miles de arreglos JSON simultáneamente crashearía el navegador. 
- En el archivo `api/obtener_propiedades.php` aplicaremos un parámetro transitable:
  ```sql
  SELECT ... LIMIT 10 OFFSET 0;
  ```
- El frontend añadirá un botón o hará scroll infinito ("Cargar más propiedades"), leyendo la página siguiente y embutiendo nuevas tarjetas (y resolviendo sus fotos progresivamente).
- **Procesamiento y Compresión en la Nube:** Antes de subir a la nube, el sistema o el proveedor mismo obligatoriamente convertirá los PNG/JPG pesados al formato ultra ligero **WebP** o **AVIF**. Las imágenes se servirán precortadas y renderizadas a tamaño exacto (ej. 400x300 píxeles para la tarjeta).

## Beneficios del Plan

> [!TIP]
> Seguir este esquema garantiza una migración "invisible" hacia un escalado gigantesco. La página `index.php` seguirá rindiendo sin cambios severos de lógica gracias a tu diseño actual de API con JSON, y las búsquedas estarán protegidas contra caídas por excesos de datos gracias a la partición de la base de datos y la recarga inteligente de imágenes (Lazy Load).
