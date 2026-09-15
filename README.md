<div align="center">
  <img src="https://raw.githubusercontent.com/lucide-icons/lucide/main/icons/calendar-days.svg" width="100" height="100" alt="Alondra Pro Icon">
  <h1 align="center">Alondra Pro ✨</h1>
  <p align="center">
    <strong>Tu planificador estudiantil inteligente, impulsado por IA.</strong>
    <br />
    De un prototipo escolar a una experiencia <i>SaaS Premium</i>.
  </p>
</div>

---

> **Atención:** Este repositorio ha sido actualizado radicalmente siguiendo la metodología **SDD (Spec-Driven Development)**. El proyecto monolítico original en PHP ha sido migrado a un stack moderno de vanguardia bajo el directorio `/alondra-pro`.

## 🚀 ¿Qué es Alondra Pro?

Alondra Pro es una plataforma interactiva diseñada para revolucionar la organización académica. No es solo un calendario; es tu asistente personal de estudio que te sugiere los mejores horarios para prepararte para tus exámenes utilizando Inteligencia Artificial.

### 🌟 Características Estrella
- 🖱️ **Calendario Interactivo (Drag & Drop):** Reprograma tareas y exámenes arrastrando y soltando, sin recargar la página.
- 🧠 **IA Planificador Integrado:** Algoritmo inteligente que sugiere sesiones de estudio basándose en tus fechas de entrega.
- 📊 **Panel de Rendimiento (Analytics):** Gráficos interactivos y métricas para medir tu productividad.
- 🌓 **Tema Adaptativo (Glassmorphism):** Diseño premium translúcido con alternancia dinámica entre Modo Oscuro y Claro.
- 🧪 **Alta Fiabilidad:** Entorno protegido y testeado automatizadamente usando `Jest` y `React Testing Library`.

---

## 🛠 Stack Tecnológico Moderno

Nuestra arquitectura ha sido rediseñada para cumplir con estándares de la industria (Enterprise-grade):

- **Framework Core:** [Next.js](https://nextjs.org/) (React) + App Router.
- **Estilos e Interfaz:** [TailwindCSS v4](https://tailwindcss.com/) + UI en Glassmorphism.
- **Animaciones fluidas:** [Framer Motion](https://www.framer.com/motion/).
- **Base de Datos & ORM:** [Prisma](https://www.prisma.io/) con SQLite (Listos para escalar a PostgreSQL).
- **QA & Testing:** Jest + React Testing Library.

---

## 💻 Guía de Inicio Rápido (Local)

Sigue estos pasos para arrancar el entorno de desarrollo ultra-moderno de Alondra Pro en tu máquina local.

### 1. Clonar e Instalar
```bash
# Entra a la nueva carpeta del proyecto
cd alondra-pro

# Instala todas las dependencias
npm install
```

### 2. Configuración de Base de Datos
Prisma maneja todo el modelo de datos. Empuja el esquema a tu base de datos local SQLite:
```bash
npx prisma db push
```

### 3. ¡Arrancar los Motores!
```bash
npm run dev
```
> 👉 Abre tu navegador en [http://localhost:3000](http://localhost:3000) y déjate sorprender por las animaciones en cascada.

---

## 🧪 Pruebas Unitarias

Nos tomamos la calidad del código muy en serio. Para ejecutar los tests automáticos (UI/UX) que verifican el correcto renderizado de las nuevas pantallas:
```bash
npm run test
```

---

## 📚 Documentación Adicional
- 📖 [Manual de Usuario Oficial](./User_Manual.md) (Ubicado en los artefactos de desarrollo)
- 📝 [Especificación SDD y Roadmap](./implementation_plan.md)
- 🛤️ [Registro de Cambios y Ejecución](./walkthrough.md)

---
<div align="center">
  <i>Construido con 💙 para elevar la productividad de cada estudiante.</i>
</div>
