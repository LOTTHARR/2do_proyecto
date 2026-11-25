# Agenda de Contactos

## Materia
Programación Web – Unidad 4

## Integrantes
- Jorge Enrique Gutiérrez Avendaño
- [Nombre del integrante 2]
- [Nombre del integrante 3]

## Descripción del proyecto
Aplicación web **Agenda de Contactos** desarrollada con PHP y MySQL bajo arquitectura modular. Permite gestionar contactos personales o profesionales con fotografía, búsqueda en tiempo real por parámetros GET, validación en cliente y servidor, protección contra XSS e inyección SQL, y confirmación segura de eliminación mediante POST con token CSRF.

## Tecnologías utilizadas
- **PHP** (PDO con sentencias preparadas, manejo de sesiones y subida de archivos)
- **MySQL** (Tabla `contactos` con codificación `utf8mb4`)
- **HTML5** (Formularios semánticos con validación nativa)
- **CSS3** (Estilos externos responsivos sin frameworks externos)

## Funcionalidades CRUD
- **Crear (`crear.php` / `guardar.php`):** Alta de contactos con nombre, apellido, teléfono, fotografía obligatoria (JPG, PNG o WEBP, máx. 3MB) y campos opcionales (email, dirección, notas).
- **Leer (`index.php` / `ver.php`):** Listado general de contactos, barra de búsqueda por GET (`?q=`) sobre nombre, apellido, teléfono y email, y vista de detalle individual (`?id=`).
- **Actualizar (`editar.php` / `actualizar.php`):** Edición de datos del contacto con reemplazo opcional de fotografía (eliminando la imagen anterior del servidor).
- **Eliminar (`eliminar.php`):** Pantalla de confirmación por GET y borrado definitivo por POST con verificación CSRF y eliminación de la imagen asociada en `archivos/`.
