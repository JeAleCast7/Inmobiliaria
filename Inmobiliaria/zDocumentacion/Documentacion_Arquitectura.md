# Documentación Detallada: Arquitectura SOLID del Proyecto Inmobiliaria

Este documento consolida y explica al detalle la arquitectura orientada a objetos bajo los **5 Principios SOLID** que ha sido aplicada al sistema de Login y Registro de la plataforma inmobiliaria.

---

## 1. Visión General de la Arquitectura

El sistema pasó de tener scripts monolíticos (código estructurado o "espagueti" donde la lógica de control, el acceso a base de datos y validaciones conviven en un solo archivo) a una **Arquitectura en Capas Limpias**. Estas capas son:

1. **Modelos (Entities):** Representan el esquema y estado de la información.
2. **Interfaces (Contracts):** Establecen qué acciones debe cumplir cualquier clase que desee pertenecer a esa función, sin importar cómo la realice.
3. **Repositorios (Data Access):** Encargados puramente de la inserción, edición y extracción de datos en la base de datos (usando `MySQLi`).
4. **Servicios (Business Logic):** Toman decisiones de negocio utilizando los modelos y repositorios.
5. **Controladores (Endpoints):** Los archivos antiguos (`validar_login.php` y `validar_registro.php`) que ahora sólo coordinan el tráfico entre la red y los Servicios.

---

## 2. Los 5 Principios SOLID Aplicados

### S - Single Responsibility Principle (Responsabilidad Única)
*Una clase debe tener una sola razón para cambiar.*

* **En Modelos:** Las clases `Usuario` y `Cliente` son simples contenedores de propiedades (`id`, `nombre`, `correo`, etc.) y métodos *get/set*. No saben cómo guardarse a sí mismas en una base de datos. Si el día de mañana agregamos propiedades, solo cambia el modelo.
* **En Repositorios:** La clase `RepositorioUsuario` tiene exclusivamente operaciones hacia la tabla `usuarios` (insertar, obtener). No mezcla código de la tabla `clientes` (eso lo hace `RepositorioCliente`).
* **En Gestores:** `ConexionMySQL` tiene una única responsabilidad: conectar a la BD. `GestorTransaccionMySQL` tiene la responsabilidad exclusiva de orquestar operaciones atómicas en BD (`begin_transaction`, `commit`, `rollback`). 

### O - Open/Closed Principle (Abierto/Cerrado)
*Las entidades de software deben estar abiertas para la extensión, pero cerradas para la modificación.*

* **En Servicios:** Observa el constructor de `ServicioAutenticacion`. Recibe variables con el tipo (Interface) en vez de clases concretas (Inyección de Dependencias).
  ```php
  public function __construct(IRepositorioUsuario $repositorioUsuario, ...)
  ```
  Si mañana dejas de usar `MySQLi` y quieres migrar al estándar `PDO` o incluso sacar a los usuarios de una API REST externa, solo debes programar un nuevo Repositorio externo que cumpla con `IRepositorioUsuario`, e inyectarlo. **No tienes que modificar ni tocar ni reescribir ni una coma en el código interno de `ServicioAutenticacion`, ya que está cerrado a modificación pero abierto a extensión**.

### L - Liskov Substitution Principle (Sustitución de Liskov)
*Objetos de una clase derivada deben poder sustituir a objetos de la clase base sin afectar la correctitud del programa.*

* Dado que `ServicioAutenticacion` depende estrictamente del contrato expuesto de `IRepositorioUsuario`, sin importar con qué objeto instanciemos a este repositorio por detrás (por ejemplo una clase de "pruebas ficticia" o una de base de datos MySQL real), el Servicio actuará idéntica y exitosamente. Las firmas de cada método (`obtenerPorEmail`, etc.) están obligadas a respetar el contrato, lo que erradica la posibilidad de fallos inesperados al cambiar piezas del sistema.

### I - Interface Segregation Principle (Segregación de Interfaces)
*Los clientes no deben ser obligados a depender de interfaces que no utilizan.*

* Era muy fácil crear en el proyecto una interfaz única: `IRepositorioPrincipal` que agrupara funciones `generarBilletera()`, `conseguirUsuario()`, `obtenerDNI()`.
* **Aplicación Real:** Para respetar del principio, se ha creado una interfaz minúscula y purisimamente específica por tema. Ejemplo:
    - `IRepositorioBilletera` contiene un único método: `insertar()`.
    - `IRepositorioAgente` contiene solo `obtenerNombrePorUsuarioId()`.
* De esta manera, si un servicio requiere tratar con billeretas, su clase no se sobrecargará implementando métodos basura/vacíos que solo le servirían a un Agente o un Usuario, librándonos del código fantasma.

### D - Dependency Inversion Principle (Inversión de Dependencias)
*Los módulos de alto nivel no deben depender de los módulos de bajo nivel. Ambos deben depender de abstracciones.*

* El nivel de "Negocios" (`ServicioRegistro`) es el **Módulo de Alto Nivel**. Su rol es muy superior.
* El nivel de Base de datos (`RepositorioUsuario` haciendo consultas SQL en MySQL) es de **Bajo Nivel**.
* Al aplicar *DIP*, evitamos que el Servicio llame explícitamente a creaciones concretas como `new RepositorioUsuario()`.
* **Solución aplicada:** Ambos interactúan entre sí pasándose y dependiendo estrictamente de su abstracción (Interfaces). De nuevo, el Controlador en `validar_login.php` ensambla los bloques de construcción pasándole los parámetros abstractos por el argumento hacia nuestro Servicio, invirtiendo quién es el dueño o fabricante de la instanciación.

---

## 3. Flujos Explicados

### Flujo de Registro (ServicioRegistro.php)

1. **Recepción:** El endpoint `validar_registro.php` atrapa $_POST y construye un `ConexionObjeto` (capa baja). 
2. **Inyección:** Se preparan los repositorios necesarios y el Gestor transaccional pasándoselos al núcleo de negocios `ServicioRegistro`.
3. **Validación:** El servicio limpia los trim y verifica los correos duplicados.
4. **Acoplamiento de modelos:** Se instancia en memoria un `$usuario = new Usuario(...)` encapsulando los datos de $_POST.
5. **Transacción:** El control transaccional comienza protegiendo el ecosistema para prever fallos:
   - Se inserta `Usuario` en MySQL a través del *repository* y se recobra su id referencial.
   - Un modelo `Cliente` se arma asociando la `id` de usuario recuperada previamente.
   - Se clona el cliente y se le prepara una `Billetera`.
6. **Resolución:** Si todo salió bien, cerramos el túnel de transacción (commit) y devolvemos variables abstractas  `['exito'=> true]` hacia el controlador.
7. **Respuesta:** El enrutador `validar_registro.php` recibe el "banderín verde" y hace la Redirección Final mediante cabeceras HTTP nativas a `Login/index.php`.

### Flujo de Inicios de Sesión (ServicioAutenticacion.php)

1. Una vez la inyección abstracta aterriza, es tarea del Servicio buscar un `$usuario` por medio del *Repository* consultando el correo.
2. Comparamos `password === usuarioDB->password`. (*La condición actual usa comparaciones limpias dado su reciente ajuste nativo*).
3. Evaluamos la validez o suspensión del usuario (`getActivo()`).
4. Con el resultado favorable, procedemos a llenar las `$_SESSION` globales con datos preestablecidos en nuestro Modelo del Usuario que recuperamos del repositorio.
5. Invocamos Repositorios alternativos `IRepositorioCliente` o `IRepositorioAgente` para recargar los nombres cosméticos de nuestro dashboard basados en los roles.
6. Devolvemos el control el redireccionador enviándole la ruta exitosa a su correspondiente carpeta.

---

## 4. Beneficios Inmediatos de la Ejecución

- **Reutilización:** Si quieres que ahora un software de escritorio inicie sesión contra esta misma base, solo tienes que reusar `ServicioAutenticacion.php` con las interfaces sin romper nada web.
- **Testeabilidad Categórica:** Es ahora completamente factible montar un script de pruebas unitarias (`PHPUnit`) automatizadas solo inyectando interfaces falsas a los servicios.
- **Sostenibilidad:** Cualquier desarrollador nuevo puede leer `Usuario.php` o `IRepositorioUsuario.php` y saber inmediatamente su función en segundos, sin requerir rebuscar dentro del monstruoso condicional espagueti del código primario.
