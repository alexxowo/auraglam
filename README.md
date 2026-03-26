# AuraGlam

**AuraGlam** es un ecosistema de gestión empresarial (ERP) boutique, diseñado para transformar la complejidad operativa en una experiencia visualmente cautivadora y altamente funcional.

---

## 💎 Propósito: ¿Qué busca resolver?
En mercados dinámicos y volátiles, la gestión de inventarios y ventas suele ser una tarea árida y visualmente densa. AuraGlam busca resolver tres problemas críticos:
1.  **Operatividad en Multi-moneda**: Automatización real de tasas de cambio (DolarAPI) y conversión instantánea a Bolívares en todo el flujo de venta.
2.  **Gestión de Inventario Inteligente**: Control de stock con alertas visuales de "Stock Bajo" y herramientas de importación/exportación masiva vía Excel.
3.  **Experiencia de Usuario Premium**: Eliminar la fatiga visual de los ERPs tradicionales mediante un sistema de diseño "Editorial", permitiendo que el administrador se enfoque en el crecimiento del negocio, no solo en los datos.

---

## 🎨 Sistema de Diseño: "The Ethereal Professional"

AuraGlam no sigue las reglas industriales de los dashboards comunes. Se rige por los principios de la **Alta Costura Digital**:

-   **Capas Tonales**: Evitamos las líneas de 1px. La estructura se define por cambios sutiles en los fondos, creando una jerarquía de "papel sobre papel".
-   **Aesthetics**: Paleta sofisticada basada en rosas vibrantes (`#be004c`) y grises clínicos sobre un fondo aireado (`#faf9f9`).
-   **Status Bloom**: En lugar de simples puntos de color, utilizamos efectos de resplandor difuminado (Bloom) para indicar estados críticos.
-   **Tipografía Editorial**: Pairing de **Manrope** para titulares con autoridad y **Inter** para legibilidad máxima en datos complejos.

---

## 🚀 Instalación y Configuración

### Requisitos Previos
-   **PHP 8.4**
-   **Composer**
-   **Node.js 20+ & NPM**
-   **MySQL 8.0+**

### Guía de Inicio Rápido
1.  **Clonar el repositorio:**
    ```bash
    git clone https://github.com/tu-usuario/auraglam.git
    cd auraglam
    ```

2.  **Instalar dependencias:**
    ```bash
    composer install
    npm install
    ```

3.  **Configuración de Entorno:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Configura tus credenciales de base de datos en el archivo `.env`.*

4.  **Base de Datos y Datos Iniciales:**
    ```bash
    php artisan migrate --seed
    ```

5.  **Compilar Assets y Servir:**
    ```bash
    npm run build
    php artisan serve
    ```

---

## 🏗️ Arquitectura y Funcionamiento

### Gestión de Tasas de Cambio
El proyecto incluye un comando programado (`app:fetch-exchange-rates`) que se ejecuta cada 6 horas para sincronizar los precios con **DolarAPI**. Esto asegura que cada pedido de venta creado esté vinculado a la tasa oficial exacta del momento.

### Flujo de Pedidos
-   Búsqueda de productos rápida con **Select2**.
-   Cálculo dinámico de subtotales en USD y su equivalente en BsS.
-   Generación de documentos con numeración secuencial automatizada por día.

### Despliegue con Docker
AuraGlam está optimizado para **Dokploy** mediante un `Dockerfile` multi-stage:
-   **Puerto de exposición**: 7010
-   **Servidor**: Apache2 con `mod_rewrite` habilitado.
-   **Optimización**: Incluye configuración de **OPcache** para rendimiento de grado producción.

---

## 🛠️ Tecnologías Core
-   **Backend**: Laravel 13 (Framework del Futuro)
-   **Frontend**: Tailwind CSS 4 (Styling Engine), Vanilla JS
-   **Automatización**: Laravel Scheduler (Cron Jobs)
-   **DevOps**: Docker, Dokploy

---
<p align="center">Diseñado con ❤️ por el equipo de Advanced Agentic Coding de Google Deepmind.</p>
