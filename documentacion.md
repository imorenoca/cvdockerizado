1. Dockerización del proyecto.

Se añade: Dockerfile, docker-compose.yml, .dockeignore.
Se cambia Variables de entorno.php

docker compose up --build

- Manejo de Sesión entre páginas.-> Front Controller / router -> Middleware

Antes se considera necesario definir funcionalidades y casos de uso.

Funcionalidades:
Requisitos Funcionales

A. Actor: Usuario no registrado

1. Puede registrarse creando una cuenta con rol "usuario", siempre que el nombre de usuario y el correo no existan ya en el sistema y 
los datos introducidos sean válidos.

B. Actor: Usuario registrado (rol usuario o administrador)

2. Puede iniciar sesión con su usuario/correo y contraseña.
3.Puede cerrar sesión en cualquier momento.

C.Actor: Usuario registrado con rol "usuario"

4. Puede ver el listado de sus propias ofertas de empleo (no las de otros usuarios).
5. Puede añadir una nueva oferta.
6. Puede modificar una oferta, solo si le pertenece.
7. Puede eliminar una oferta, solo si le pertenece.
8. Puede filtrar su listado de ofertas por estado (abierto, cerrado, guardado).
9. Puede ver el listado de empresas (catálogo compartido entre todos los usuarios).
10. Puede añadir, modificar y eliminar empresas; no puede eliminar una empresa si está asociada a alguna oferta.
11. Puede ver el listado de contactos (catálogo compartido).
12. Puede añadir, modificar y eliminar contactos.
13. Puede ver el listado de tipos de envío (catálogo compartido).
14. Puede añadir, modificar y eliminar tipos de envío; no puede eliminar un tipo de envío si está asociado a alguna oferta.

D. Actor: Usuario registrado con rol "administrador"

15. Puede listar los usuarios registrados, mostrando su nombre y correo.
16. No tiene acceso a la gestión de ofertas, empresas, contactos ni envíos (rol estrictamente separado del de "usuario").
Requisitos No Funcionales

Seguridad

1. Las contraseñas se almacenan con una función de hash segura con salt (no algoritmos obsoletos como SHA1 o MD5 sin salt).
2. El acceso a cualquier funcionalidad que requiera sesión debe comprobar, en el servidor, que existe una sesión iniciada válida.
3. El acceso a las funcionalidades de cada rol debe comprobar el rol exacto del usuario (un administrador no puede acceder a la gestión de ofertas/empresas/contactos/envíos, y un usuario no puede acceder al listado de usuarios), no basta con comprobar que hay sesión iniciada.
4. Un usuario solo puede modificar o eliminar sus propias ofertas; el sistema debe verificar la propiedad del recurso antes de ejecutar la acción, no solo la sesión.
5. Todas las consultas a base de datos que incluyan datos introducidos por el usuario deben usar sentencias preparadas, para prevenir inyección SQL.
6. Los archivos de lógica de la aplicación no deben ser ejecutables mediante acceso directo por URL; solo deben poder ejecutarse a través del punto de entrada de la aplicación.

Usabilidad y presentación

La aplicación debe mostrar mensajes de error claros y comprensibles al usuario en cada flujo (login fallido, registro con datos inválidos, 
intento de acceso no autorizado, etc.).
El diseño general debe ser homogéneo en toda la aplicación.
Los botones y menús deben ser legibles y de fácil identificación.
La interfaz debe ser intuitiva y de fácil uso.
La aplicación no debe mostrar publicidad.

Compatibilidad

La aplicación debe funcionar correctamente en Google Chrome, Microsoft Edge y Firefox.
El diseño debe ser responsive, implementado con CSS flexbox (o el sistema de rejilla equivalente que finalmente uses).

Mantenibilidad

El código debe seguir una estructura reconocible y mantenible (separación de responsabilidades entre modelo, vista y controlador).
La lógica común entre entidades similares (por ejemplo, operaciones CRUD repetidas) debe reutilizarse mediante una estructura común, evitando duplicación de código.

*Lista final de actores y casos de uso.

Usuario no registrado

CU-01 — Registro

Usuario registrado (rol: usuario)

CU-02 — Iniciar sesión
CU-03 — Cerrar sesión
CU-04 — Gestionar ofertas
CU-05 — Gestionar empresas
CU-06 — Gestionar contactos
CU-07 — Gestionar envíos

Administrador (rol: administrador)

CU-02 — Iniciar sesión (mismo caso de uso, actor distinto — el login es compartido por ambos roles registrados)
CU-03 — Cerrar sesión (igual, compartido)
CU-08 — Listar usuarios

CU-01 — Registro

Actor: Usuario no registrado
Precondiciones: el usuario no tiene sesión iniciada.
Flujo principal:

El usuario accede al formulario de registro.
El usuario introduce nombre de usuario, correo y contraseña.
El sistema valida el formato de los datos introducidos.
El sistema comprueba que el nombre de usuario y el correo no existen ya.
El sistema crea el usuario con rol "usuario".
El sistema redirige al usuario a la pantalla de login.

Flujos alternativos:

3a. Si algún campo no cumple el formato requerido (correo inválido, contraseña demasiado corta): el sistema muestra un mensaje de error específico y no continúa.
4a. Si el nombre de usuario o el correo ya existen: el sistema muestra un mensaje de error indicando cuál de los dos está duplicado y no crea el registro.

Postcondiciones: existe un nuevo usuario con rol "usuario" en el sistema, sin sesión iniciada todavía.

CU-02 — Iniciar sesión (Login)

Actor: Usuario, Administrador
Precondiciones: el actor está registrado y no tiene sesión iniciada.
Flujo principal:

El actor accede al formulario de login.
El actor introduce su usuario/correo y contraseña.
El sistema verifica las credenciales contra los datos almacenados.
El sistema inicia una sesión, guardando el identificador y el rol del actor.
El sistema redirige según el rol: al listado de ofertas si es "usuario", al listado de usuarios si es "administrador".

Flujos alternativos:

3a. Si el usuario/correo no existe o la contraseña no coincide: el sistema muestra un mensaje de error genérico ("usuario o contraseña incorrectos", sin especificar cuál de los dos falló) y no inicia sesión.

Postcondiciones: existe una sesión activa asociada al actor, con su rol registrado.

CU-03 — Cerrar sesión (Logout)

Actor: Usuario, Administrador
Precondiciones: el actor tiene una sesión activa.
Flujo principal:

El actor pulsa "Salir".
El sistema destruye la sesión activa.
El sistema redirige al actor a la pantalla de login.

Flujos alternativos: ninguno relevante.

Postcondiciones: no existe sesión activa para ese actor; cualquier intento posterior de acceder a una página que requiera sesión lo redirige de nuevo al login.

CU-04 — Gestionar ofertas

Actor: Usuario
Incluye: CU-02 (Iniciar sesión)
Precondiciones: el usuario tiene sesión iniciada con rol "usuario".
Flujo principal:

El usuario accede al listado de sus propias ofertas.
El sistema muestra únicamente las ofertas asociadas a ese usuario.
El usuario puede optar por: añadir una oferta nueva, modificar una existente, eliminar una existente, o filtrar el listado por estado (abierto, cerrado, guardado).
Si añade: el usuario introduce los datos de la oferta (empresa, contacto, envío, fechas, estado...) y el sistema la guarda asociada a su usuario.
Si modifica: el usuario edita los datos de una oferta suya y el sistema guarda los cambios.
Si elimina: el usuario confirma la eliminación y el sistema borra la oferta.
Si filtra: el usuario elige un estado y el sistema muestra solo las ofertas propias que lo cumplen.

Flujos alternativos:

5a/6a. Si el usuario intenta modificar o eliminar una oferta que no le pertenece (por ejemplo, manipulando la URL): el sistema deniega la acción y no realiza ningún cambio.
4a. Si los datos introducidos al añadir no son válidos: el sistema muestra un mensaje de error y no guarda la oferta.

Postcondiciones: el listado de ofertas del usuario refleja los cambios realizados (alta, modificación o baja).

CU-05 — Gestionar empresas

Actor: Usuario
Incluye: CU-02 (Iniciar sesión)
Precondiciones: el usuario tiene sesión iniciada con rol "usuario".
Flujo principal:

El usuario accede al listado de empresas (catálogo compartido por todos los usuarios).
El usuario puede optar por: añadir una empresa nueva, modificar una existente, o eliminarla.
Si añade: el usuario introduce los datos de la empresa y el sistema la guarda.
Si modifica: el usuario edita los datos y el sistema guarda los cambios.
Si elimina: el sistema comprueba que la empresa no está asociada a ninguna oferta antes de borrarla.

Flujos alternativos:

5a. Si la empresa está asociada a alguna oferta: el sistema deniega la eliminación y muestra un mensaje explicando el motivo.
3a. Si los datos introducidos no son válidos: el sistema muestra un mensaje de error y no guarda la empresa.

Postcondiciones: el catálogo de empresas refleja los cambios realizados, salvo que se haya denegado una eliminación por estar en uso.

CU-06 — Gestionar contactos

Actor: Usuario
Incluye: CU-02 (Iniciar sesión)
Precondiciones: el usuario tiene sesión iniciada con rol "usuario".
Flujo principal:

El usuario accede al listado de contactos (catálogo compartido).
El usuario puede optar por: añadir un contacto nuevo, modificar uno existente, o eliminarlo.
Si añade: el usuario introduce los datos del contacto y el sistema lo guarda.
Si modifica: el usuario edita los datos y el sistema guarda los cambios.
Si elimina: el sistema borra el contacto directamente, sin restricción por ofertas asociadas.

Flujos alternativos:

3a. Si los datos introducidos no son válidos: el sistema muestra un mensaje de error y no guarda el contacto.

Postcondiciones: el catálogo de contactos refleja los cambios realizados. Si el contacto eliminado estaba asociado a alguna oferta, esa oferta queda sin contacto asignado.

CU-07 — Gestionar envíos

Actor: Usuario
Incluye: CU-02 (Iniciar sesión)
Precondiciones: el usuario tiene sesión iniciada con rol "usuario".
Flujo principal:

El usuario accede al listado de tipos de envío (catálogo compartido).
El usuario puede optar por: añadir un tipo de envío nuevo, modificar uno existente, o eliminarlo.
Si añade: el usuario introduce el dato del tipo de envío y el sistema lo guarda.
Si modifica: el usuario edita el dato y el sistema guarda el cambio.
Si elimina: el sistema comprueba que el tipo de envío no está asociado a ninguna oferta antes de borrarlo.

Flujos alternativos:

5a. Si el tipo de envío está asociado a alguna oferta: el sistema deniega la eliminación y muestra un mensaje explicando el motivo.
3a. Si el dato introducido no es válido o ya existe: el sistema muestra un mensaje de error y no lo guarda.

Postcondiciones: el catálogo de tipos de envío refleja los cambios realizados, salvo que se haya denegado una eliminación por estar en uso.

CU-08 — Listar usuarios registrados

Actor: Administrador
Incluye: CU-02 (Iniciar sesión)
Precondiciones: el actor tiene sesión iniciada con rol "administrador".
Flujo principal:

El administrador accede al listado de usuarios.
El sistema muestra el nombre y correo de todos los usuarios registrados.

Flujos alternativos: ninguno relevante (es una operación de solo lectura, sin puntos de fallo esperables más allá del control de acceso, ya cubierto por la precondición).

Postcondiciones: ninguna (no modifica el estado del sistema).

Lista final de pantallas:

1. Bienvenida
2. Login
3. Registro
4. Listado de ofertas (con filtro y modal de confirmación de borrado)
5. Formulario de oferta (alta/edición)
6. Listado de empresas (con modal)
7. Formulario de empresa (alta/edición)
8. Listado de contactos (con modal)
9. Formulario de contacto (alta/edición)
10. Listado de envíos (con modal)
11. Formulario de envío (alta/edición)
12. Listado de usuarios (administrador)

1. Configuración de un único punto de entrada
.htaccess
Se configura un único punto de entrada.
index.php
Se pone el enrutamiento.
Se crean páginas de bienvenida:
bienvenida.php
error404.php.

Instalar Composer.
En Dockerfile
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

docker compose up --build -d

Versión
docker compose exec app composer --version

Crear el composer.json
docker compose exec app composer init --no-interaction --name=imorenoca/cvdockerizado

docker compose exec app composer require --dev phpunit/phpunit

- Configuración clase Router.php