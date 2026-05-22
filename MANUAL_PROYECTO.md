# Manual de Funcionamiento del Proyecto - Barber Shop

Este documento explica de forma clara y detallada cómo funciona el proyecto, la arquitectura sobre la cual está construido, qué hace cada uno de sus apartados principales y de qué manera interactúan sus diferentes componentes.

---

## 1. Arquitectura y Tecnologías Principales

El proyecto está desarrollado utilizando el framework **Laravel (versión 11+)** junto con un conjunto de herramientas modernas para la web:

1. **PHP**: Lenguaje del lado del servidor que maneja las reglas del negocio, la base de datos y la seguridad.
2. **Laravel Livewire**: Tecnología que permite crear componentes interactivos y dinámicos en tiempo real sin recargar la página completa, ofreciendo una experiencia tipo Single Page Application (SPA).
3. **Tailwind CSS**: Framework de diseño utilizado para dar estilo a las vistas, logrando una estética visual cuidada, moderna y adaptada a dispositivos móviles.
4. **DomPDF (`barryvdh/laravel-dompdf`)**: Biblioteca para generar documentos PDF dinámicos. En este proyecto se usa para crear tickets de citas e informes de agenda de los barberos.
5. **SweetAlert**: Biblioteca de Javascript integrada con Livewire para mostrar notificaciones y alertas visuales atractivas (`swal`) al crear, actualizar o denegar acciones.

---

## 2. Base de Datos y Modelos (`app/Models`)

El sistema cuenta con un diseño de base de datos relacional para gestionar usuarios, perfiles específicos, servicios y citas. A continuación, se detallan los modelos principales:

*   **[User.php](file:///c:/xampp/htdocs/barber-shop/app/Models/User.php)**: 
    *   Entidad de autenticación (inicio de sesión).
    *   Campos principales: `name`, `email`, `password`, `role_id` y `photo`.
    *   Tiene helpers como `isAdmin()` y `isSuperAdmin()`.
    *   Se conecta opcionalmente con los perfiles `barber` o `client` según el rol.
*   **[Role.php](file:///c:/xampp/htdocs/barber-shop/app/Models/Role.php)**:
    *   Define los roles disponibles en el sistema. Los roles predeterminados (creados mediante `DatabaseSeeder.php`) son:
        1. **Administrador** (ID: 1): Gestiona barberos, clientes, servicios y citas.
        2. **Super Administrador** (ID: 2): Posee todos los accesos del Administrador más el control exclusivo sobre los usuarios/personal del sistema.
        3. **Barbero** (ID: 3): Visualiza sus citas, agenda de trabajo y horarios.
        4. **Cliente** (ID: 4): Visualiza su historial de citas y agenda nuevas.
*   **[Barber.php](file:///c:/xampp/htdocs/barber-shop/app/Models/Barber.php)**:
    *   Contiene el perfil profesional del barbero (`specialty`, `phone`, `email`, `address`, `photo`).
    *   Tiene un disparador automático (`booted` event callback) de modo que, cuando se da de alta un barbero, se le generan automáticamente sus **7 horarios semanales** (Lunes a Sábado trabajando de 9:00 AM a 6:00 PM con almuerzo de 1:00 PM a 2:00 PM; Domingo inactivo por defecto).
    *   Usa **SoftDeletes** para ocultar registros eliminados sin borrar la información histórica de la base de datos.
*   **[Client.php](file:///c:/xampp/htdocs/barber-shop/app/Models/Client.php)**:
    *   Contiene la información de contacto del cliente (`phone`, `email`, `address`, etc.) y se relaciona con sus respectivas citas históricas.
*   **[Service.php](file:///c:/xampp/htdocs/barber-shop/app/Models/Service.php)**:
    *   Define los servicios ofrecidos por la barbería (ej. Corte de Cabello, Arreglo de Barba). Contiene campos como `name`, `price`, `duration` y `description`.
*   **[BarberSchedule.php](file:///c:/xampp/htdocs/barber-shop/app/Models/BarberSchedule.php)**:
    *   Tabla que guarda detalladamente por día de la semana (`day_of_week` de 1 a 7) si el barbero trabaja, su hora de entrada, de salida y sus tiempos de almuerzo.
*   **[Appointment.php](file:///c:/xampp/htdocs/barber-shop/app/Models/Appointment.php)**:
    *   El núcleo del negocio. Registra la relación entre un Cliente, un Barbero y un Servicio en una Fecha (`date`) y Hora (`time`) específica.
    *   Estados de cita (`status`): `pending` (pendiente), `confirmed` (confirmada), `completed` (completada) y `canceled` (cancelada).
    *   Soporta **SoftDeletes** para proteger el historial estadístico.

---

## 3. Flujo de Rutas y Seguridad (Middleware)

La seguridad del sistema está estructurada en los siguientes archivos y mecanismos:

### A. Prefijo y Seguridad Global de Administración
En **[bootstrap/app.php](file:///c:/xampp/htdocs/barber-shop/bootstrap/app.php)** se define que todas las rutas declaradas en **[routes/admin.php](file:///c:/xampp/htdocs/barber-shop/routes/admin.php)** tengan automáticamente:
*   El prefijo `/admin` en su URL (ej. `misitio.com/admin/barbers`).
*   Los middlewares `auth` (requiere iniciar sesión) y `role:1,2` (solamente Administradores y Super Administradores).

### B. Middleware Personalizado `CheckRole`
El middleware **[CheckRole.php](file:///c:/xampp/htdocs/barber-shop/app/Http/Middleware/CheckRole.php)** intercepta los accesos a rutas protegidas:
1. Comprueba si el usuario tiene sesión activa.
2. Compara el `role_id` del usuario contra los roles permitidos en la ruta.
3. **Control de Fugas**: Si un usuario con rol insuficiente (ej. un Cliente común) intenta acceder a `/admin`, el middleware realiza un **cierre de sesión inmediato** (logout), destruye la sesión actual para evitar bucles de redirección infinita y redirige a la pantalla de login con un mensaje de SweetAlert de "Acceso Denegado".

### C. Enlaces Firmados (Signed URLs) para Clientes
En **[routes/web.php](file:///c:/xampp/htdocs/barber-shop/routes/web.php)** existen dos rutas públicas especiales:
*   `/appointments/{appointment}/confirm` (Confirmar cita)
*   `/appointments/{appointment}/cancel` (Cancelar cita)

Estas rutas **no requieren iniciar sesión**. Se envían por correo electrónico a los clientes mediante enlaces protegidos criptográficamente (`Signed URLs`). El sistema utiliza `request()->hasValidSignature()` para validar que el enlace no haya sido alterado ni haya expirado. Si la firma es correcta, el cliente puede confirmar o cancelar su cita con un solo clic.

---

## 4. ¿Qué hace cada sección y cómo lo hace?

El panel administrativo está estructurado en módulos interactivos desarrollados con Livewire y Controladores tradicionales:

### A. Dashboard Principal
*   **Componente**: `MainDashboard` ([MainDashboard.php](file:///c:/xampp/htdocs/barber-shop/app/Livewire/Admin/MainDashboard.php))
*   **¿Qué hace?**: Muestra de un vistazo rápido el estado del negocio en tiempo real.
*   **¿Cómo lo hace?**:
    *   Calcula métricas clave como: **Ingresos Totales** (suma de precios de servicios de citas completadas), **Citas de Hoy**, **Total de Clientes** y **Total de Barberos**.
    *   Carga y lista de manera ordenada en una tabla las próximas citas correspondientes al día de hoy, mostrando la hora, foto y datos del cliente, servicio, barbero y su estado actual.

### B. Módulo de Citas (Appointments)
*   **Componentes**: `AppointmentManager` y `AppointmentForm`
*   **¿Qué hace?**: Permite listar, filtrar, agendar, editar y cancelar citas, además de imprimir tickets físicos.
*   **¿Cómo lo hace?**:
    *   **Filtrado interactivo**: El manager permite buscar citas por cliente o barbero en tiempo real.
    *   **Cálculo Dinámico de Horarios Disponibles (`getAvailableSlots`)**: Al seleccionar un barbero y una fecha en el formulario, Livewire calcula automáticamente en intervalos de 30 minutos qué horas están libres. Para ello:
        1. Consulta el horario de trabajo del barbero en esa fecha específica (excluyendo días no laborables).
        2. Omite el bloque de tiempo programado para almuerzo.
        3. Excluye los horarios de citas que ya estén agendadas en base de datos.
        4. Muestra al usuario botones interactivos con los horarios libres para agendar rápidamente.
    *   **Prevención de Doble Booking**: Antes de guardar, el backend valida si hay colisión de horarios con otra cita en el mismo bloque y emite una alerta SweetAlert si detecta conflicto.
    *   **Descarga de Tickets**: El controlador **[AppointmentTicketController.php](file:///c:/xampp/htdocs/barber-shop/app/Http/Controllers/AppointmentTicketController.php)** utiliza DomPDF para generar un archivo PDF (comprobante con diseño de ticket clásico) que se puede imprimir o descargar.

### C. Módulo de Barberos y Horarios
*   **Componentes/Controladores**: `BarberManager`, `BarberController` y `BarberSchedule`
*   **¿Qué hace?**: Alta, edición y eliminación de barberos, junto con la gestión personalizada de su agenda de trabajo.
*   **¿Cómo lo hace?**:
    *   **Sincronización de Perfiles**: Al registrar un nuevo barbero a través del `BarberController`, el sistema abre una transacción en la base de datos (`DB::transaction`) y realiza dos tareas simultáneas: crea un usuario de acceso (con contraseña por defecto) y genera su perfil profesional con foto.
    *   **Gestión de Horarios Integrada**: El componente Livewire `BarberSchedule` despliega un panel donde el administrador puede activar/desactivar días laborables individuales de cada barbero, y configurar rangos específicos de inicio, fin y recesos de almuerzo con validaciones lógicas cruzadas (ej. la hora de almuerzo debe estar estrictamente dentro del horario laboral).

### E. Módulo de Clientes (Clients)
*   **Componentes/Controladores**: `ClientManager` y `ClientController`
*   **¿Qué hace?**: Controla los perfiles de los clientes y almacena un expediente con su historial.
*   **¿Cómo lo hace?**:
    *   De forma análoga al módulo de barberos, sincroniza el perfil del cliente con la tabla de usuarios.
    *   El componente `ClientRecords` permite consultar un expediente histórico del cliente mostrando todas sus citas pasadas, servicios contratados, notas especiales y barberos favoritos.

### F. Módulo de Servicios (Services)
*   **Componentes**: `ServiceManager` y `ServiceForm`
*   **¿Qué hace?**: Catálogo de cortes, peinados y servicios disponibles para su asignación.
*   **¿Cómo lo hace?**:
    *   Proporciona un formulario ágil para agregar o actualizar servicios, validando precios y duraciones antes de guardarlos.

### G. Módulo de Usuarios (Solo Super Administrador)
*   **Componentes**: `UserManager` y `UserForm`
*   **¿Qué hace?**: Control administrativo del personal.
*   **¿Cómo lo hace?**:
    *   Protegido por el middleware `role:2`. Permite administrar cuentas, modificar contraseñas y asignar roles del sistema a cada persona.

---

## 5. Sistema de Correos Electrónicos (`app/Mail`)

El sistema cuenta con 3 clases de correos automatizados en formato Markdown para notificaciones fluidas:

1.  **[AppointmentConfirmed.php](file:///c:/xampp/htdocs/barber-shop/app/Mail/AppointmentConfirmed.php)**:
    *   **Desencadenante**: Se envía al cliente al momento de agendar una cita con éxito.
    *   **Detalle**: Incluye el desglose del servicio y adjunta automáticamente el ticket de la cita en formato PDF (`Comprobante-Cita-{id}.pdf`).
2.  **[AppointmentReminder.php](file:///c:/xampp/htdocs/barber-shop/app/Mail/AppointmentReminder.php)**:
    *   **Desencadenante**: Se envía al cliente un día antes de su cita para recordarle la asistencia.
    *   **Detalle**: Adjunta también el ticket en PDF y provee los botones con enlaces firmados para confirmar o cancelar la cita desde el correo.
3.  **[BarberDailyAgenda.php](file:///c:/xampp/htdocs/barber-shop/app/Mail/BarberDailyAgenda.php)**:
    *   **Desencadenante**: Se envía a los barberos para notificarles su carga de trabajo.
    *   **Detalle**: Agrupa todas las citas confirmadas de ese barbero para el día siguiente y le adjunta una agenda consolidada en PDF (`Agenda_{fecha}.pdf`).

---

## 6. Procesos Automáticos en Segundo Plano (Cron Jobs)

Para automatizar tareas que ocurren todos los días, el sistema implementa dos Comandos de Consola personalizados registrados en la programación diaria de **[bootstrap/app.php](file:///c:/xampp/htdocs/barber-shop/bootstrap/app.php)**:

### A. Recordatorios Diarios y Envío de Agendas
*   **Comando**: `php artisan barber:reminders`
*   **Clase**: **[SendAppointmentReminders.php](file:///c:/xampp/htdocs/barber-shop/app/Console/Commands/SendAppointmentReminders.php)**
*   **Funcionamiento**:
    1.  Se ejecuta automáticamente a medianoche.
    2.  Obtiene todas las citas de "mañana" que estén pendientes o confirmadas.
    3.  Envía un correo recordatorio individual a cada cliente.
    4.  Agrupa las citas de mañana por barbero y envía a cada barbero su agenda diaria en PDF.
    5.  **Estrategia de Tolerancia a Fallos**: Si el servidor de correos detecta saturación (ej. límites en Mailtrap), el comando captura la excepción, espera **11 segundos** y realiza un segundo intento. Además, añade una pausa prudencial de **3 segundos** entre correos individuales para no ser bloqueado por Spam.

### B. Depuración de Citas Vencidas
*   **Comando**: `php artisan barber:cancel-past-appointments`
*   **Clase**: **[CancelPastAppointments.php](file:///c:/xampp/htdocs/barber-shop/app/Console/Commands/CancelPastAppointments.php)**
*   **Funcionamiento**:
    1.  Se ejecuta diariamente en segundo plano.
    2.  Busca cualquier cita programada para fechas anteriores al día de hoy que haya quedado en estado `pending` o `confirmed`.
    3.  Actualiza su estado automáticamente a `canceled` (cancelada) para liberar el historial y mantener la base de datos limpia de citas fantasmas o no atendidas.
