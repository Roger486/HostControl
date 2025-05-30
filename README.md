# 🏕️ HostControl - Grupo 13

**HostControl** es una plataforma web diseñada para la gestión integral de complejos turísticos como campings, casas rurales o bungalows. Ofrece herramientas modernas que mejoran la experiencia del cliente y optimizan las operaciones internas del complejo.

---

## 📌 Índice

- [Descripción del Proyecto](#descripción-del-proyecto)
- [Tecnologías](#tecnologías)
- [Arquitectura del Sistema](#arquitectura-del-sistema)
- [Funcionalidades Clave](#funcionalidades-clave)
- [Instalación y Despliegue](#instalación-y-despliegue)
- [Backups y Monitorización](#backups-y-monitorización)
- [Testing](#testing)
- [Equipo](#equipo)
- [Mejoras Futuras](#mejoras-futuras)
- [Licencia](#licencia)

---

## 📖 Descripción del Proyecto

HostControl digitaliza y centraliza la gestión de alojamientos, reservas y servicios adicionales (actividades, alquiler de material, late check-out, etc.). A diferencia de plataformas centralizadas como Booking, HostControl permite total personalización y control al establecimiento.

---

## 💻 Tecnologías

### Frontend
- **Angular** (SPA, TypeScript)
- **Bootstrap 5** (maquetación responsive)

### Backend
- **Laravel** (API RESTful, MVC)
- **Sanctum** (autenticación)
- **MariaDB** (base de datos relacional)

### Infraestructura
- **Google Cloud Platform**
  - Compute Engine
  - Cloud SQL
  - Cloud Storage
- **Terraform** (IaC)
- **Ansible** (automatización)

---

## 🏗️ Arquitectura del Sistema

- **Servidor Web**: Angular + Laravel
- **Cloud SQL**: Base de datos en MariaDB
- **Servidor Backups**: Copias automáticas vía scripts/Ansible
- **Monitorización**: Google Cloud Monitoring + alertas
- **Backup Bucket**: backups-grupo13 (Cloud Storage)
- **Dominio**: [hostcontrol.es](https://hostcontrol.es)

---

## 🔑 Funcionalidades Clave

- Registro/login de usuarios con token (Sanctum)
- Consultar disponibilidad y reservar alojamientos
- Gestión de reservas y servicios (cliente/admin)
- Panel de administración: CRUD de usuarios, alojamientos, reservas
- Logs de cambios y notificaciones internas
- Soporte multidioma en API (Accept-Language)
- Gestión de imágenes por alojamiento
- Validaciones avanzadas en frontend/backend

---

## 🚀 Instalación y Despliegue

### Requisitos
- Node.js + Angular CLI
- PHP + Laravel + Composer
- MariaDB
- Google Cloud SDK (opcional)

### Backend
```bash
git clone <repo>
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
```

### Frontend
```bash
cd frontend
npm install
ng build --configuration=production
```

### Despliegue automático
Script disponible mediante Cloud Build + Ansible + Terraform.

---

## 🔐 Backups y Monitorización

- Copias diarias de:
  - Base de datos Cloud SQL (script bash)
  - Archivos del servidor web (Ansible)
- Verificación automática de backups
- Restauración manual/script
- Monitorización por métricas y alertas (caída, CPU, espacio...)

---

## 🧪 Testing

Se realizaron 37 tests cubriendo:
- Funciones de usuario (registro, login, reservas)
- Funciones admin (modificación, eliminación, auditoría)
- Infraestructura (conexiones, certificados, backup)
- Monitorización (alertas activadas, estado de recursos)

---

## 👥 Equipo

**Grupo 13** - Desarrollo del Proyecto HostControl

- 🎯 Roger Navarro: Back-end, API, BBDD, documentación, control de versiones
- 🌐 Vicente Nuñez: Front-end Angular, estructura, rutas, diseño responsive
- 🧠 Rubén Tena: Infraestructura GCP, script de despliegue, dominio
- 🔐 Hugo Moreno: Backups, Ansible, monitorización, verificación y restauración

---

## 🔧 Mejoras Futuras

- Panel para gestión de empleados
- Valoraciones de clientes
- Dashboard con estadísticas
- CI/CD con GitHub Actions
- Soporte completo multidioma en frontend
- Soft-deletes en usuarios
- Sistema de pagos

---

## 📄 Licencia

Este proyecto es de uso académico y no posee actualmente una licencia comercial. Para más información, contactar con el equipo desarrollador.

---
