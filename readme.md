Time spent working on the assignment: **4 hours**.

## Quick Start

```
git clone https://github.com/adiachenko/newsroom.git
composer install
```

Run test suite to ensure everything works as expected:

```sh
./vendor/bin/phpunit
```

## Test Assignment

Реализовать систему поста новостей:
- Возможность вылаживать новости (Заголовок, сама новость).
- Возможность оставлять комментарии
- Возможность регистрации пользователя (пользователи делятся на 2 типа: автор статей и комментатор. Автор статей может только создать новость и редактировать ее, комментатор может только оставлять комментарии под новостью).
- Возможность просмотра списка новостей.
- Поиск по заголовку новости, промежутку дат
- При добавлении комментария к новости, автору на почту приходит уведомление.

