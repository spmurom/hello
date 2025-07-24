<?php
require_once 'email_validator.php';

/**
 * Тестирование функций проверки email
 */

echo "=== Тестирование функций для проверки email ===\n\n";

// Тестовые email адреса
$testEmails = [
    'user@example.com',
    'test.email+tag@domain.co.uk',
    'invalid.email',
    'user@',
    '@domain.com',
    '',
    'very.long.email.address.that.might.be.too.long@very.long.domain.name.that.exceeds.limits.com',
    'user@domain',
    'user name@domain.com',
    'user@domain.c'
];

echo "1. Простая проверка с помощью isValidEmail():\n";
foreach ($testEmails as $email) {
    $isValid = isValidEmail($email);
    echo "Email: '$email' - " . ($isValid ? "✓ Валидный" : "✗ Невалидный") . "\n";
}

echo "\n2. Расширенная проверка с помощью validateEmailExtended():\n";
foreach (array_slice($testEmails, 0, 5) as $email) {
    $result = validateEmailExtended($email, ['check_mx' => false]);
    echo "Email: '$email'\n";
    echo "  Статус: " . ($result['valid'] ? "✓ Валидный" : "✗ Невалидный") . "\n";
    if (!empty($result['errors'])) {
        echo "  Ошибки: " . implode(', ', $result['errors']) . "\n";
    }
    echo "\n";
}

echo "3. Очистка email адресов:\n";
$dirtyEmails = [' User@EXAMPLE.COM ', 'TEST@domain.COM '];
foreach ($dirtyEmails as $email) {
    $cleaned = sanitizeEmail($email);
    echo "Исходный: '$email' -> Очищенный: '$cleaned'\n";
}
?>