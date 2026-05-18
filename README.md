# Barber Shop✂️

Bienvenido al **Barber Shop**, una plataforma profesional diseñada para la gestión eficiente de una barbería moderna. Este sistema permite administrar citas, clientes, barberos y servicios con notificaciones automáticas y generación de tickets en PDF.

## 🚀 Requisitos del Sistema

*   **PHP:** 8.2 o superior
*   **MySQL:** 8.0 o superior
*   **Composer:** Gestor de dependencias de PHP
*   **Node.js & NPM:** Para compilar el frontend (Vite)

## 🛠️ Instalación y Configuración

Sigue estos pasos para poner en marcha el proyecto en tu entorno local:

### 1. Clonar y Preparar Dependencias
```bash
# Instalar dependencias de PHP
composer install

# Instalar dependencias de Frontend
npm install
```

### 2. Configuración de Entorno
Copia el archivo de ejemplo y genera la clave de la aplicación:
```bash
copy .env.example .env
php artisan key:generate
```
**Nota:** Asegúrate de configurar tus credenciales de base de datos y Mailtrap (SMTP) en el archivo `.env`.

### 3. Base de Datos e Inicialización
Ejecuta las migraciones y los seeders para poblar el sistema con datos de prueba:
```bash
php artisan migrate --seed
```

### 4. Compilación de Assets
Compila los estilos y scripts necesarios para la interfaz:
```bash
npm run dev
```

### 5. Iniciar Servidor
```bash
php artisan serve
```

## 🔐 Credenciales de Prueba (Admin)

Puedes acceder al panel administrativo con las siguientes credenciales:
*   **Email:** `admin@barber.com`
*   **Password:** `password`

## 📅 Automatización (Recordatorios)

El sistema cuenta con un comando programado para enviar recordatorios de citas automáticamente:
```bash
# Ejecutar manualmente
php artisan barber:reminders

# El comando está programado para ejecutarse diariamente de forma automática
```

## ✨ Características Principales
*   **Dashboard:** Estadísticas rápidas y vista general.
*   **Citas:** Gestión dinámica con Livewire.
*   **Tickets PDF:** Generación de comprobantes profesionales.
*   **Emails:** Confirmación de citas con enlaces firmados y recordatorios diarios.
*   **Responsive UI:** Interfaz elegante optimizada para cualquier dispositivo.

---
Desarrollado por **Rodrigo Alejandro Chi Catzim** usando **Laravel 11**, **Livewire 3** y **Tailwind CSS**.
