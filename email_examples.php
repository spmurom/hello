<?php
require_once 'email_validator.php';

/**
 * Практические примеры использования функций для проверки email
 */

echo "=== Практические примеры использования функций проверки email ===\n\n";

// Пример 1: Проверка email при регистрации пользователя
echo "1. Проверка email при регистрации:\n";
$userEmails = [
    'john.doe@gmail.com',
    'invalid-email',
    'admin@company.org',
    'user@temp-mail.org'
];

foreach ($userEmails as $email) {
    $result = validateEmailExtended($email, [
        'blocked_domains' => ['temp-mail.org', '10minutemail.com'],
        'check_mx' => false // отключаем MX проверку для примера
    ]);
    
    echo "Email: $email\n";
    if ($result['valid']) {
        echo "  ✓ Email принят для регистрации\n";
    } else {
        echo "  ✗ Email отклонен: " . implode(', ', $result['errors']) . "\n";
    }
    echo "\n";
}

// Пример 2: Массовая проверка email списка
echo "2. Массовая проверка email списка:\n";
$emailList = [
    'contact@example.com',
    'support@company.co.uk',
    'invalid.format',
    'info@domain.net',
    'test@'
];

$results = validateEmailBatch($emailList);
$validCount = 0;
$invalidCount = 0;

foreach ($results as $result) {
    if ($result['valid']) {
        $validCount++;
        echo "✓ {$result['email']} -> {$result['sanitized']}\n";
    } else {
        $invalidCount++;
        echo "✗ {$result['email']} (невалидный)\n";
    }
}

echo "\nИтого: $validCount валидных, $invalidCount невалидных email адресов\n\n";

// Пример 3: Проверка с различными методами
echo "3. Сравнение методов проверки:\n";
$testEmail = 'Test.Email+Tag@Example.COM';

echo "Тестовый email: $testEmail\n";
echo "filter_var(): " . (isValidEmail($testEmail) ? "✓ Валидный" : "✗ Невалидный") . "\n";
echo "regex: " . (isValidEmailRegex($testEmail) ? "✓ Валидный" : "✗ Невалидный") . "\n";
echo "Очищенный: " . sanitizeEmail($testEmail) . "\n";

$extendedResult = validateEmailExtended($testEmail);
echo "Расширенная проверка: " . ($extendedResult['valid'] ? "✓ Валидный" : "✗ Невалидный") . "\n\n";

// Пример 4: Функция для формы обратной связи
echo "4. Проверка email для формы обратной связи:\n";

function validateContactFormEmail($email) {
    // Очищаем email
    $cleanEmail = sanitizeEmail($email);
    
    // Проверяем базовую валидность
    if (!isValidEmail($cleanEmail)) {
        return [
            'valid' => false,
            'message' => 'Пожалуйста, введите корректный email адрес',
            'email' => $cleanEmail
        ];
    }
    
    // Дополнительные проверки
    $result = validateEmailExtended($cleanEmail, [
        'blocked_domains' => ['example.com', 'test.com'] // блокируем тестовые домены
    ]);
    
    if (!$result['valid']) {
        return [
            'valid' => false,
            'message' => 'Email адрес не может быть использован: ' . implode(', ', $result['errors']),
            'email' => $cleanEmail
        ];
    }
    
    return [
        'valid' => true,
        'message' => 'Email адрес принят',
        'email' => $cleanEmail
    ];
}

$contactEmails = [
    ' User@Gmail.COM ',
    'contact@example.com',
    'support@company.org',
    'invalid-email-format'
];

foreach ($contactEmails as $email) {
    $result = validateContactFormEmail($email);
    echo "Email: '$email'\n";
    echo "  Результат: " . ($result['valid'] ? "✓" : "✗") . " {$result['message']}\n";
    echo "  Очищенный: {$result['email']}\n\n";
}

// Пример 5: Класс для работы с email валидацией
class EmailValidator {
    private $blockedDomains = [];
    private $checkMX = false;
    
    public function __construct($options = []) {
        if (isset($options['blocked_domains'])) {
            $this->blockedDomains = $options['blocked_domains'];
        }
        if (isset($options['check_mx'])) {
            $this->checkMX = $options['check_mx'];
        }
    }
    
    public function validate($email) {
        $cleanEmail = sanitizeEmail($email);
        
        return validateEmailExtended($cleanEmail, [
            'blocked_domains' => $this->blockedDomains,
            'check_mx' => $this->checkMX
        ]);
    }
    
    public function isValid($email) {
        $result = $this->validate($email);
        return $result['valid'];
    }
    
    public function addBlockedDomain($domain) {
        if (!in_array($domain, $this->blockedDomains)) {
            $this->blockedDomains[] = $domain;
        }
    }
    
    public function getBlockedDomains() {
        return $this->blockedDomains;
    }
}

echo "5. Использование класса EmailValidator:\n";

$validator = new EmailValidator([
    'blocked_domains' => ['spam.com', 'fake.org'],
    'check_mx' => false
]);

$testEmails = [
    'user@gmail.com',
    'test@spam.com',
    'contact@company.net'
];

foreach ($testEmails as $email) {
    $isValid = $validator->isValid($email);
    echo "Email: $email - " . ($isValid ? "✓ Валидный" : "✗ Невалидный") . "\n";
}

$validator->addBlockedDomain('company.net');
echo "\nПосле добавления 'company.net' в черный список:\n";

foreach ($testEmails as $email) {
    $isValid = $validator->isValid($email);
    echo "Email: $email - " . ($isValid ? "✓ Валидный" : "✗ Невалидный") . "\n";
}

echo "\nЗаблокированные домены: " . implode(', ', $validator->getBlockedDomains()) . "\n";
?>