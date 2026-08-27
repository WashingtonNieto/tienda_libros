Actúa como un Arquitecto de Software y Desarrollador Full-Stack Senior especializado en PHP, MySQL, Bootstrap 5, SweetAlert2 y DataTables.js. 

Tu objetivo es guiarme paso a paso en el desarrollo completo de un sistema web monolítico MVC sin frameworks backend pesados (PHP 8+ nativo con PDO), integrando Bootstrap 5 para el maquetado, SweetAlert2 para alertas interactivas, DataTables.js para la gestión avanzada de tablas y transacciones PDO para la consistencia de datos.

### Contexto del Negocio:
Una plataforma web para la comercialización e inventario de libros con dos roles principales:
1. Administrador: Compra libros a proveedores (incrementa inventario) y gestiona el sistema.
2. Vendedor/Usuario: Realiza ventas de libros a clientes registrados (disminuye inventario).

### Requisitos Funcionales y UI/UX:
- Landing Page & Dashboard: Catálogo visual con tarjetas de Bootstrap (Cards) para mostrar carátulas de libros más vendidos y recomendados.
- Autenticación y Autorización: Login y Registro maquetados con Bootstrap 5, control de accesos basado en roles (RBAC: Admin y Vendedor) y alertas de error/éxito con SweetAlert2.
- Tablas Dinámicas con DataTables.js: Integración en todos los módulos CRUD (Clientes, Proveedores, Categorías, Libros y Reportes) para brindar búsqueda rápida, paginación, ordenamiento por columnas y traducción al español.
- Interacción y Confirmaciones: Uso de SweetAlert2 para mensajes de confirmación antes de eliminar registros, notificaciones emergentes de stock insuficiente y mensajes Flash de operaciones exitosas (crear, editar, eliminar).
- Módulos CRUD requeridos: Clientes, Proveedores, Categorías y Libros usando tablas responsivas, modales y validaciones visuales.
- Gestión de Inventario Transaccional: Automatización e integridad en entradas (compras a proveedores) y salidas (ventas a clientes).
- Reportes: Módulo de informes mensuales de ventas con métricas destacadas e indicadores clave.

### Restricciones Técnicas:
- Stack Frontend: HTML5, Bootstrap 5 (CSS/JS), SweetAlert2 (JS) y DataTables.js (CSS/JS con tema Bootstrap 5 y lenguaje en español) vía CDN.
- Stack Backend: PHP 8+ nativo con PDO (Prepared Statements para prevenir inyección SQL).
- Transacciones ACID (Imprescindible): Obligatoriedad de implementar transacciones PDO (`$pdo->beginTransaction()`, `$pdo->commit()`, `$pdo->rollBack()`) dentro de bloques `try-catch` para todas las operaciones de Compras y Ventas. Esto debe garantizar que el registro de la cabecera, el detalle de la transacción y el ajuste del stock del libro se ejecuten de forma atómica.
- Patrón de Arquitectura: MVC (Modelo-Vista-Controlador) con arquitectura de carpetas limpia, plantilla base (`header.php`, `footer.php`, `navbar.php`), helper de alertas SweetAlert2 e inicializador común de DataTables.
- Base de Datos: MySQL/MariaDB (motor InnoDB para soporte de transacciones y FK) con script de claves foráneas, restricciones de integridad e índices.
- Seguridad: Hashing de contraseñas (`password_hash`), manejo de sesiones seguras y validación de inputs.

---

### Estructura de Entregables por Fases:
Por favor, responde de forma iterativa. En tu primera respuesta, entrega únicamente la **Fase 1**. Al finalizar cada fase, detente y espera a que valide el código para continuar con la siguiente.

- Fase 1: Análisis del Dominio y Modelo E/R Script completo en SQL usando el motor InnoDB (Tablas: usuarios, roles, clientes, proveedores, categorias, libros, compras, ventas, detalle_compras, detalle_ventas) con datos de prueba (SEEDERS).
- Fase 2: Arquitectura del Proyecto MVC Estructura de carpetas (`/config`, `/controllers`, `/models`, `/views`, `/public`), conexión PDO Singleton, Plantilla Base con Bootstrap 5, SweetAlert2 y script de inicialización global de DataTables.js en español.
- Fase 3: Autenticación y Control de Acceso Modelos, controladores y vistas para Login y Logout, protección de rutas por roles y alertas emergentes con SweetAlert2 ante credenciales inválidas o accesos denegados.
- Fase 4: Módulos CRUD Base (Categorías, Clientes y Proveedores) Código completo para operaciones CRUD integrando DataTables.js para listar registros y SweetAlert2 para la confirmación de borrado ("¿Estás seguro?") y notificaciones de éxito.
- Fase 5: Módulo de Libros y Compras a Proveedores CRUD de Libros con carga de imágenes para carátulas, asignación de categorías y registro de compras. Implementación obligatoria de **transacciones PDO** para insertar la compra, guardar el detalle e incrementar el stock del libro.
- Fase 6: Módulo de Ventas y Carátulas Destacadas Proceso de venta a clientes con **transacciones PDO** (validación previa de stock, inserción de venta/detalle y descuento automático de stock con rollBack ante errores). Layout visual con Cards para "Libros más vendidos" y "Recomendados".
- Fase 7: Módulo de Reportes Mensuales Consulta SQL avanzada y vista resumen con métricas y DataTables.js para la visualización del informe mensual de ventas.

Comienza únicamente con la **Fase 1**.

Estructura MVC

tienda_libros/
├── config/
│   ├── database.php        # Conexión Singleton PDO
│   └── config.php          # Constantes globales (BASE_URL, DB config)
├── controllers/
│   ├── AuthController.php  # Controlador base para auth (se llenará en Fase 3)
│   └── HomeController.php  # Controlador de la vista principal
├── models/
│   └── User.php            # Modelo base (ejemplo)
├── views/
│   ├── layouts/
│   │   ├── header.php      # CDNs (Bootstrap, DataTables, SweetAlert2)
│   │   ├── footer.php      # Scripts JS e inicializaciones globales
│   │   └── navbar.php      # Menú de navegación responsivo
│   ├── home/
│   │   └── index.php       # Vista de bienvenida / Catálogo inicial
│   └── auth/
│       └── login.php       # Vista de login (Fase 3)
├── public/
│   ├── css/
│   │   └── styles.css      # Estilos personalizados
│   ├── js/
│   │   └── app.js          # Scripts globales y helper de SweetAlert2
│   └── uploads/
│       └── covers/         # Carpeta para imágenes de carátulas
└── index.php               # Enrutador Base / Front Controller