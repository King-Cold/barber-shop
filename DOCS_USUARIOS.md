# Flujo de Gestión de Usuarios - Barber Shop

Este documento explica de manera explícita y detallada cómo funciona el proceso de **guardar**, **editar** y **eliminar** usuarios en el sistema, detallando qué archivos intervienen, qué métodos se ejecutan y cuáles son las reglas de negocio aplicadas.

---

## 1. Módulo de Cuentas de Usuario (General)

Este flujo se ejecuta a través del Panel del Super Administrador (Rol ID: 2) para gestionar las credenciales generales de acceso al sistema (Administradores, SuperAdministradores, Barberos y Clientes).

### Archivos Clave:
* **Rutas:** [routes/admin.php](file:///c:/xampp/htdocs/barber-shop/routes/admin.php) (Líneas 21-26)
* **Modelo:** [User.php](file:///c:/xampp/htdocs/barber-shop/app/Models/User.php)
* **Controlador de Tabla/Listado:** [UserManager.php](file:///c:/xampp/htdocs/barber-shop/app/Livewire/Admin/UserManager.php)
* **Controlador de Formulario:** [UserForm.php](file:///c:/xampp/htdocs/barber-shop/app/Livewire/Admin/UserForm.php)
* **Vistas Blade:** 
  * `resources/views/livewire/admin/users/index.blade.php` (Listado)
  * `resources/views/livewire/admin/users/create.blade.php` (Crear)
  * `resources/views/livewire/admin/users/edit.blade.php` (Editar)

---

### A. Proceso de Guardar (Crear un Usuario)

Cuando el Super Administrador completa el formulario para añadir un nuevo usuario:

1. **`UserForm::mount()`**: El componente se inicializa con `$isEditing = false`. Las propiedades del formulario (`$name`, `$email`, `$role_id`, `$photo`) inician vacías o con valores por defecto.
2. **`UserForm::rules()`**: Retorna el set de validaciones. Para creación, exige que la contraseña (`$password`) sea obligatoria, tenga al menos 8 caracteres y coincida con la confirmación. El correo electrónico debe ser único en la tabla `users`.
3. **`UserForm::save()`**:
   * Se ejecuta `$this->validate()` para comprobar las reglas.
   * Si se subió una foto (`$this->photo`), se le asigna un nombre único basado en el tiempo actual y se guarda físicamente en el disco.
   * Llama a `User::create([...])` insertando el nombre, correo electrónico, contraseña encriptada (`Hash::make`), el rol seleccionado y la ruta de la foto de perfil.
   * **Sincronización Automática con Perfiles:**
     * **Si es Barbero (Rol 3):** Desvincula al usuario de cualquier perfil de cliente previo. Busca si ya existe un perfil de barbero con ese correo o usuario; si no existe, crea un registro en la tabla `barbers` usando `Barber::create([...])` y asocia el `user_id`. Seguidamente, redirige al Super Administrador para completar los datos profesionales del barbero.
     * **Si es Cliente (Rol 4):** Realiza un proceso similar al anterior, creando o asociando un registro en la tabla `clients` (`Client::create([...])`).
     * **Si es Administrativo (Rol 1 o 2):** Desvincula al usuario de las tablas `barbers` y `clients`.
   * Lanza un mensaje flash de SweetAlert indicando que el usuario fue creado con éxito y redirige a la lista.

---

### B. Proceso de Editar (Actualizar un Usuario)

Cuando el Super Administrador pulsa el botón de editar en la fila de un usuario específico:

1. **`UserForm::mount(User $user)`**: Recibe el modelo del usuario desde la ruta. Llena las propiedades (`$name`, `$email`, `$role_id`, `$currentPhoto`) con los valores actuales de la base de datos y establece `$isEditing = true`.
2. **`UserForm::rules()`**: Al estar editando, la contraseña se vuelve **opcional** (si se deja en blanco no se actualiza). La regla del correo electrónico cambia para permitir que el correo siga siendo el mismo del usuario editado sin causar error de duplicidad: `Rule::unique('users')->ignore($this->user->id)`.
3. **`UserForm::save()`**:
   * Valida la entrada.
   * Si se subió una nueva foto, primero elimina la foto anterior del disco público (`File::delete`) y guarda la nueva.
   * Prepara los datos. Si la contraseña no está vacía, la encripta y la añade al array de actualización.
   * Llama a `$this->user->update($data)`.
   * Ejecuta la **Sincronización Automática** (explicada en el proceso de creación) para asegurar que si se cambió el rol del usuario, los perfiles de Barberos/Clientes queden desvinculados o creados según corresponda.
   * Envía la alerta SweetAlert de actualización correcta y redirige a la lista.

---

### C. Proceso de Eliminar (Borrar un Usuario)

Cuando se presiona el botón de eliminar en la tabla de usuarios:

1. **`UserManager::confirmDelete($id)`**: Este método se ejecuta en el backend para validar que el borrado sea seguro.
   * **Validación de Autodeleción:** Compara el `$id` con el del usuario autenticado (`auth()->id()`). Si son iguales, deniega la acción lanzando un SweetAlert de error.
   * **Protección de Dueño:** Si el `$id` es `1` (Super Administrador Principal), bloquea la acción.
   * **Privilegios de Rol:** Si el usuario a borrar es Super Administrador (`$user->isSuperAdmin()`) y quien intenta borrarlo es un Administrador común, bloquea la acción.
   * Si supera estas reglas, dispara el evento `swal:confirm` al frontend para mostrar la ventana emergente de confirmación de SweetAlert.
2. **`UserManager::delete($id)`**: Si el usuario pulsa "Confirmar" en la ventana de SweetAlert, se dispara este método en el servidor.
   * Realiza una doble comprobación de seguridad (para evitar solicitudes maliciosas directas).
   * Ejecuta `$user->delete()`.
   * Dispara una alerta de éxito confirmando que el usuario ha sido eliminado de la base de datos.

---

## 2. Flujo de Barberos y Clientes (Módulo Profesional)

Los barberos y los clientes tienen flujos especializados que combinan la creación de un perfil profesional/clínico (`Barber` / `Client`) con una cuenta de usuario asociada. Esto se gestiona a través de controladores estándar de Laravel.

### Archivos Clave:
* **Controlador Barberos:** [BarberController.php](file:///c:/xampp/htdocs/barber-shop/app/Http/Controllers/Admin/BarberController.php)
* **Controlador Clientes:** [ClientController.php](file:///c:/xampp/htdocs/barber-shop/app/Http/Controllers/Admin/ClientController.php)

---

### A. Proceso de Guardar en Controladores

Tomando como ejemplo el registro de un Barbero a través de `BarberController::store()`:

1. **Validación:** Comprueba que los campos profesionales y de contacto sean correctos.
2. **Transacción de Base de Datos (`DB::transaction`)**: Toda la creación se envuelve en una transacción. Si falla la creación del usuario o la del barbero, la base de datos revierte todo al estado original (rollback).
3. **Creación del Usuario:**
   ```php
   $user = User::create([
       'name' => $validated['name'],
       'email' => $validated['email'],
       'password' => Hash::make('password'), // Contraseña temporal por defecto
       'photo' => $photoPath,
   ]);
   ```
4. **Asignación de Rol:** Obtiene o crea el rol de "Barbero" y lo asocia al usuario.
5. **Creación del Perfil:** Crea el registro en la tabla `barbers` vinculándolo al usuario:
   ```php
   Barber::create([
       'user_id' => $user->id,
       'name' => $validated['name'],
       'specialty' => $validated['specialty'],
       'phone' => $validated['phone'],
       'email' => $validated['email'],
       'address' => $validated['address'],
       'photo' => $photoPath,
   ]);
   ```
6. **Redirección:** Redirige al panel de edición del barbero para que el administrador proceda a configurar sus horarios semanales o afinar detalles.

---

### B. Proceso de Edición en Controladores

En `BarberController::update()`:

1. Se valida la petición.
2. Dentro de una transacción, se actualizan los datos de la tabla `barbers`.
3. Si el barbero posee un `user_id` asociado, se realiza un update en la tabla `users` para mantener el nombre, el correo y la foto sincronizados en ambos lados:
   ```php
   User::where('id', $barber->user_id)->update([
       'name' => $validated['name'],
       'email' => $validated['email'],
       'photo' => $photoPath,
   ]);
   ```

---

### C. Proceso de Eliminación en Controladores

En `BarberController::destroy()`:

1. Dentro de una transacción, busca si el barbero tiene un `user_id` asociado y elimina el registro de la tabla `users`.
2. Llama a `$barber->delete()`. Como el modelo `Barber` utiliza **SoftDeletes** (eliminación suave), el barbero no se borra físicamente de la base de datos (para no perder el historial de citas), pero queda oculto e inactivo para el sistema de manera que ya no puede iniciar sesión ni ser agendado.
