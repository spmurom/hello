<?php
/**
 * Функции для проверки email адресов
 */

/**
 * Основная функция для проверки email адреса
 * Использует встроенный фильтр PHP для валидации
 * 
 * @param string $email Email адрес для проверки
 * @return bool true если email валидный, false если нет
 */
function isValidEmail($email) {
    // Проверяем, что email не пустой
    if (empty($email)) {
        return false;
    }
    
    // Используем встроенный фильтр PHP для валидации email
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Расширенная функция для проверки email с дополнительными правилами
 * 
 * @param string $email Email адрес для проверки
 * @param array $options Дополнительные опции для проверки
 * @return array Результат проверки с деталями
 */
function validateEmailExtended($email, $options = []) {
    $result = [
        'valid' => false,
        'email' => $email,
        'errors' => []
    ];
    
    // Проверяем, что email не пустой
    if (empty($email)) {
        $result['errors'][] = 'Email не может быть пустым';
        return $result;
    }
    
    // Проверяем длину email
    if (strlen($email) > 254) {
        $result['errors'][] = 'Email слишком длинный (максимум 254 символа)';
    }
    
    // Основная валидация через встроенный фильтр
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result['errors'][] = 'Неверный формат email адреса';
        return $result;
    }
    
    // Разделяем email на локальную часть и домен
    $parts = explode('@', $email);
    if (count($parts) !== 2) {
        $result['errors'][] = 'Email должен содержать один символ @';
        return $result;
    }
    
    $localPart = $parts[0];
    $domain = $parts[1];
    
    // Проверяем локальную часть
    if (strlen($localPart) > 64) {
        $result['errors'][] = 'Локальная часть email слишком длинная (максимум 64 символа)';
    }
    
    if (empty($localPart)) {
        $result['errors'][] = 'Локальная часть email не может быть пустой';
    }
    
    // Проверяем домен
    if (empty($domain)) {
        $result['errors'][] = 'Домен не может быть пустым';
    }
    
    // Проверяем, что домен содержит точку
    if (strpos($domain, '.') === false) {
        $result['errors'][] = 'Домен должен содержать точку';
    }
    
    // Дополнительные проверки, если указаны в опциях
    if (isset($options['check_mx']) && $options['check_mx']) {
        if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
            $result['errors'][] = 'Домен не имеет MX или A записей в DNS';
        }
    }
    
    if (isset($options['blocked_domains']) && is_array($options['blocked_domains'])) {
        if (in_array(strtolower($domain), array_map('strtolower', $options['blocked_domains']))) {
            $result['errors'][] = 'Домен находится в списке заблокированных';
        }
    }
    
    // Если ошибок нет, email валидный
    if (empty($result['errors'])) {
        $result['valid'] = true;
    }
    
    return $result;
}

/**
 * Функция для проверки email с использованием регулярного выражения
 * 
 * @param string $email Email адрес для проверки
 * @return bool true если email валидный, false если нет
 */
function isValidEmailRegex($email) {
    // RFC 5322 совместимое регулярное выражение (упрощенная версия)
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($pattern, $email) === 1;
}

/**
 * Функция для очистки и нормализации email адреса
 * 
 * @param string $email Email адрес для очистки
 * @return string Очищенный email адрес
 */
function sanitizeEmail($email) {
    // Удаляем пробелы в начале и конце
    $email = trim($email);
    
    // Приводим к нижнему регистру
    $email = strtolower($email);
    
    // Удаляем недопустимые символы
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    return $email;
}

/**
 * Функция для массовой проверки email адресов
 * 
 * @param array $emails Массив email адресов для проверки
 * @return array Результаты проверки для каждого email
 */
function validateEmailBatch($emails) {
    $results = [];
    
    foreach ($emails as $email) {
        $results[] = [
            'email' => $email,
            'valid' => isValidEmail($email),
            'sanitized' => sanitizeEmail($email)
        ];
    }
    
    return $results;
}

// Функции готовы к использованию без вывода на экран
?>