# API Catálogos

Este proyecto es la API backend del sistema **Censo Alameda**, desarrollado en **Symfony 6.4**.
Pero se puede aplicar para cualquier caso de catálogos.

Su función es gestionar los datos de los distintos catálogos y módulos del sistema de censo o catálogos,
proveyendo servicios RESTfull para el frontend.

---

## 🚀 Tecnologías utilizadas

- **Symfony 6.4** - Framework PHP moderno y robusto
- **Doctrine ORM** - Mapeo objeto-relacional para base de datos
- **API Platform (parcial)** - Diseño de rutas API REST manualmente
- **JWT Authentication** (opcional si implementado)
- **MySQL / MariaDB** - Base de datos relacional
- **Composer** - Gestión de dependencias PHP

---

## 📦 Instalación

1. Clonar el repositorio:

```bash
git clone https://github.com/gerMdz/api_catalogos
```

2. Instalar dependencias PHP:

```bash
composer install
```

3. Configurar el archivo `.env`:

```dotenv
DATABASE_URL="mysql://usuario:contraseña@127.0.0.1:3306/censo_alameda"
```

4. Crear base de datos:

```bash
php bin/console doctrine:database:create
```

5. Ejecutar migraciones:

```bash
php bin/console doctrine:migrations:migrate
```

---

## 📚 Estructura de carpetas importante

| Carpeta                  | Descripción                                              |
|--------------------------|----------------------------------------------------------|
| `src/Controller/Api/`    | Controladores para las rutas API REST                    |
| `src/Entity/`            | Entidades Doctrine mapeadas a tablas de base de datos    |
| `src/Repository/`        | Repositorios personalizados de consultas a base de datos |
| `migrations/`            | Archivos de migraciones para estructura de la base       |
| `config/routes/api.yaml` | Configuración de rutas de la API                         |

---

## 📋 Principales endpoints

Cada entidad del sistema expone operaciones CRUD simples a través de rutas bajo el prefijo `/api/`.

Por ejemplo:

| Método | Endpoint             | Descripción                   |
|--------|----------------------|-------------------------------|
| GET    | `/api/services`      | Listar todos los servicios    |
| POST   | `/api/services`      | Crear nuevo servicio          |
| PUT    | `/api/services/{id}` | Actualizar servicio existente |
| DELETE | `/api/services/{id}` | Borrado lógico de servicio    |

> *La mayoría de las entidades siguen el mismo patrón.*

---

## 🔒 Seguridad

Actualmente, el sistema utiliza **token JWT** para proteger las rutas privadas.  
El token debe ser enviado en el header de cada request:

```
Authorization: Bearer <tu_token>
```

---

## 👨‍💻 Desarrollo

Servidor local:

```bash
symfony serve -d
```

O utilizando directamente PHP:

```bash
php -S 127.0.0.1:8000 -t public
```

La API estará disponible en:

```
https://127.0.0.1:8000/api/
```

---

## 📜 Convenciones

- Todas las entidades tienen campos de auditoría:
    - `audi_user` → ID del usuario que creó/modificó/borró
    - `audi_date` → Fecha de la acción
    - `audi_action` → Acción (`I`=Insert, `U`=Update, `D`=Delete)

- Los "borrados" son **borrados lógicos**, nunca físicos, marcando el registro con `audi_action = 'D'`.

---

## 👥 Autores

Proyecto desarrollado y mantenido por:

- **Gerardo J. Montivero** | [gerardo.montivero@gmail.com](gerardo.montivero@gmail.com)

## 📝 Licencia

Este proyecto está licenciado bajo la **Licencia MIT**.

Puedes usar, copiar, modificar, fusionar, publicar, distribuir, sublicenciar y/o vender copias del Software, siempre que
mantengas el aviso de copyright y la licencia.

Ver el archivo [LICENSE](LICENSE) para más detalles.

