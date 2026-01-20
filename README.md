# wp-doctors-test

Тестовое задание для WordPress 6.x: CPT «Доктора» + архив с GET‑фильтрами и сортировками + демо‑генератор.

## Что сделано
- Плагин `doctors-cpt`:
  - CPT `doctors` (`/doctors/`)
  - таксономии `specialization` (иерархическая) и `city` (неиерархическая)
  - мета‑поля: `experience_years`, `price_from`, `rating`
  - фильтрация/сортировка архива через GET + пагинация с сохранением параметров
  - генерация демо‑данных (Tools → Generate Doctors Demo)
- Тема `doctors-theme`:
  - `archive-doctors.php` (сетка 9/страница)
  - `single-doctors.php`
  - `template-parts/doctor-card.php`, `template-parts/filters.php`

## Установка (Docker)
```bash
cp .env.example .env
docker compose up -d
```

Открыть `http://localhost:8080` и пройти стандартную установку WordPress.

Далее в админке:
- Активировать плагин **Doctors CPT**
- Активировать тему **Doctors Theme**

### Быстрая установка через WP-CLI (опционально)
```bash
docker compose --profile cli run --rm wpcli core install \\
  --url=http://localhost:8080 \\
  --title=\"WP Doctors Test\" \\
  --admin_user=admin \\
  --admin_password=admin \\
  --admin_email=admin@example.com \\
  --skip-email

docker compose --profile cli run --rm wpcli plugin activate doctors-cpt
docker compose --profile cli run --rm wpcli theme activate doctors-theme
docker compose --profile cli run --rm wpcli eval \"doctors_cpt_generate_demo_data(20);\"
```

## Генерация демо‑данных
Админка → **Tools → Generate Doctors Demo** → кнопка «Создать 20 докторов».

## URL для проверки
- Архив: `/doctors/`
- Пример single: `/doctors/ivan-ivanov/` (после генерации)

Примеры фильтров:
- `/doctors/?specialization=hirurg`
- `/doctors/?city=moskva`
- `/doctors/?sort=rating_desc`
- Комбинация + пагинация: `/doctors/?specialization=hirurg&city=moskva&sort=price_asc&paged=2`

## Пояснения по решениям
### Почему `city` — non-hierarchical
Города обычно не требуют иерархии. В формате «тегов» проще назначать, фильтровать и поддерживать без лишней вложенности.

### Почему фильтрация через `pre_get_posts`
Это позволяет изменять **main query** архива:
- корректно работает пагинация
- меньше побочных эффектов, чем отдельный `WP_Query` в шаблоне
- поведение ближе к стандартным архивам WP/Woo

## Без ACF
По умолчанию мета‑поля реализованы нативно (metabox + `save_post` + `register_post_meta`). При желании можно вынести поля в ACF, но это не требуется для теста.
