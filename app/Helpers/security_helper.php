<?php

/**
 * Security Helper
 *
 * Provides additional security functions for input validation,
 * XSS protection, SQL injection prevention, and CSRF validation.
 */

if (!function_exists('sanitize_input')) {
    /**
     * Sanitize user input to prevent XSS attacks
     *
     * @param string|array $input
     * @return string|array
     */
    function sanitize_input($input)
    {
        if (is_array($input)) {
            return array_map('sanitize_input', $input);
        }

        // Remove HTML tags and encode special characters
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        return $input;
    }
}

if (!function_exists('validate_nisn')) {
    /**
     * Validate NISN (Nomor Induk Siswa Nasional)
     * Must be 10 digits
     *
     * @param string $nisn
     * @return bool
     */
    function validate_nisn($nisn)
    {
        return preg_match('/^[0-9]{10}$/', $nisn) === 1;
    }
}

if (!function_exists('validate_nik')) {
    /**
     * Validate NIK (Nomor Induk Kependudukan)
     * Must be 16 digits
     *
     * @param string $nik
     * @return bool
     */
    function validate_nik($nik)
    {
        return preg_match('/^[0-9]{16}$/', $nik) === 1;
    }
}

if (!function_exists('validate_phone')) {
    /**
     * Validate Indonesian phone number
     *
     * @param string $phone
     * @return bool
     */
    function validate_phone($phone)
    {
        // Remove spaces, dashes, and parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        // Check if it's a valid Indonesian phone number
        return preg_match('/^(0|62|\+62)[0-9]{8,12}$/', $phone) === 1;
    }
}

if (!function_exists('validate_email_safe')) {
    /**
     * Validate email address with additional security checks
     *
     * @param string $email
     * @return bool
     */
    function validate_email_safe($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Additional check: prevent disposable email domains (optional)
        $disposable_domains = ['tempmail.com', 'throwaway.email', '10minutemail.com'];
        $domain = substr(strrchr($email, "@"), 1);

        return !in_array($domain, $disposable_domains);
    }
}

if (!function_exists('prevent_sql_injection')) {
    /**
     * Check for SQL injection patterns in input
     *
     * @param string $input
     * @return bool Returns true if potential SQL injection detected
     */
    function prevent_sql_injection($input)
    {
        $sql_patterns = [
            '/(\bUNION\b.*\bSELECT\b)/i',
            '/(\bDROP\b.*\bTABLE\b)/i',
            '/(\bINSERT\b.*\bINTO\b)/i',
            '/(\bUPDATE\b.*\bSET\b)/i',
            '/(\bDELETE\b.*\bFROM\b)/i',
            '/(\bSELECT\b.*\bFROM\b)/i',
            '/(\'|\"|;|--)/i',
        ];

        foreach ($sql_patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true; // Potential SQL injection detected
            }
        }

        return false;
    }
}

if (!function_exists('detect_xss')) {
    /**
     * Detect XSS (Cross-Site Scripting) attempts
     *
     * @param string $input
     * @return bool Returns true if potential XSS detected
     */
    function detect_xss($input)
    {
        $xss_patterns = [
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/<iframe\b[^>]*>(.*?)<\/iframe>/is',
            '/javascript:/i',
            '/on\w+\s*=/i', // onclick, onload, etc.
            '/<embed\b/i',
            '/<object\b/i',
        ];

        foreach ($xss_patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true; // Potential XSS detected
            }
        }

        return false;
    }
}

if (!function_exists('secure_file_upload')) {
    /**
     * Validate file upload security
     *
     * @param array $file $_FILES array element
     * @param array $allowed_types Allowed MIME types
     * @param int $max_size Maximum file size in bytes
     * @return array ['valid' => bool, 'error' => string]
     */
    function secure_file_upload($file, $allowed_types = [], $max_size = 2097152)
    {
        $result = ['valid' => false, 'error' => ''];

        // Check if file was uploaded
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $result['error'] = 'File tidak valid atau tidak diupload dengan benar';
            return $result;
        }

        // Check file size
        if ($file['size'] > $max_size) {
            $result['error'] = 'Ukuran file terlalu besar. Maksimal ' . ($max_size / 1024 / 1024) . ' MB';
            return $result;
        }

        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!empty($allowed_types) && !in_array($mime_type, $allowed_types)) {
            $result['error'] = 'Tipe file tidak diizinkan';
            return $result;
        }

        // Check file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $dangerous_extensions = ['php', 'exe', 'bat', 'sh', 'cmd', 'jar'];

        if (in_array($extension, $dangerous_extensions)) {
            $result['error'] = 'Ekstensi file berbahaya';
            return $result;
        }

        $result['valid'] = true;
        return $result;
    }
}

if (!function_exists('rate_limit_check')) {
    /**
     * Simple rate limiting check
     *
     * @param string $identifier Unique identifier (IP, user ID, etc.)
     * @param int $max_attempts Maximum attempts allowed
     * @param int $time_window Time window in seconds
     * @return bool Returns true if rate limit exceeded
     */
    function rate_limit_check($identifier, $max_attempts = 5, $time_window = 60)
    {
        $cache = \Config\Services::cache();
        $key = 'rate_limit_' . md5($identifier);

        $attempts = $cache->get($key);

        if ($attempts === null) {
            $cache->save($key, 1, $time_window);
            return false;
        }

        if ($attempts >= $max_attempts) {
            return true; // Rate limit exceeded
        }

        $cache->save($key, $attempts + 1, $time_window);
        return false;
    }
}

if (!function_exists('generate_secure_token')) {
    /**
     * Generate a secure random token
     *
     * @param int $length Token length
     * @return string
     */
    function generate_secure_token($length = 32)
    {
        return bin2hex(random_bytes($length));
    }
}

if (!function_exists('hash_password_secure')) {
    /**
     * Hash password using bcrypt
     *
     * @param string $password
     * @return string
     */
    function hash_password_secure($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}

if (!function_exists('verify_password_secure')) {
    /**
     * Verify password against hash
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    function verify_password_secure($password, $hash)
    {
        return password_verify($password, $hash);
    }
}

if (!function_exists('log_security_event')) {
    /**
     * Log security-related events
     *
     * @param string $event_type
     * @param string $message
     * @param array $context
     */
    function log_security_event($event_type, $message, $context = [])
    {
        log_message('warning', "[SECURITY] {$event_type}: {$message}", $context);
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * Get client IP address securely
     *
     * @return string
     */
    function get_client_ip()
    {
        $request = \Config\Services::request();

        // Check for proxy headers
        $ip = $request->getServer('HTTP_X_FORWARDED_FOR')
            ?? $request->getServer('HTTP_CLIENT_IP')
            ?? $request->getServer('REMOTE_ADDR');

        // Validate IP
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }

        return '0.0.0.0';
    }
}
