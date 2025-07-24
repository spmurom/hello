# PHP Email Validator

Набор PHP функций для проверки и валидации email адресов с различными уровнями строгости и дополнительными возможностями.

## Функции

### `isValidEmail($email)`
Простая функция для базовой проверки email адреса с использованием встроенного фильтра PHP.

**Параметры:**
- `$email` (string) - Email адрес для проверки

**Возвращает:** `boolean` - true если email валидный, false если нет

### `validateEmailExtended($email, $options = [])`
Расширенная функция для проверки email с дополнительными правилами и опциями.

**Параметры:**
- `$email` (string) - Email адрес для проверки
- `$options` (array) - Дополнительные опции:
  - `check_mx` (bool) - Проверять MX записи домена
  - `blocked_domains` (array) - Список заблокированных доменов

**Возвращает:** `array` - Результат с полной информацией о проверке

### `isValidEmailRegex($email)`
Проверка email с использованием регулярного выражения (RFC 5322 совместимое).

### `sanitizeEmail($email)`
Очистка и нормализация email адреса (удаление пробелов, приведение к нижнему регистру).

### `validateEmailBatch($emails)`
Массовая проверка массива email адресов.

## Класс EmailValidator

Объектно-ориентированная обертка для работы с валидацией email.

```php
$validator = new EmailValidator([
    'blocked_domains' => ['spam.com', 'temp-mail.org'],
    'check_mx' => false
]);

$isValid = $validator->isValid('user@example.com');
```

## Примеры использования

### Базовая проверка
```php
if (isValidEmail('user@example.com')) {
    echo "Email валидный";
}
```

### Расширенная проверка
```php
$result = validateEmailExtended('user@example.com', [
    'blocked_domains' => ['temp-mail.org'],
    'check_mx' => true
]);

if ($result['valid']) {
    echo "Email принят";
} else {
    echo "Ошибки: " . implode(', ', $result['errors']);
}
```

### Очистка email
```php
$cleanEmail = sanitizeEmail(' User@EXAMPLE.COM ');
// Результат: 'user@example.com'
```

## Файлы

- `email_validator.php` - Основные функции валидации
- `email_examples.php` - Практические примеры использования
- `README.md` - Документация

## Запуск примеров

```bash
php email_validator.php    # Базовые примеры
php email_examples.php     # Практические сценарии
```

## Возможности

- ✅ Базовая валидация через filter_var()
- ✅ Проверка через регулярные выражения
- ✅ Очистка и нормализация email адресов
- ✅ Проверка MX записей домена
- ✅ Черные списки доменов
- ✅ Массовая обработка email списков
- ✅ Объектно-ориентированный интерфейс
- ✅ Подробная информация об ошибках
- ✅ Практические примеры использования
