<?php
require_once __DIR__ . '/../inc/require.php';

class Utils
{
    public static function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrfToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    //converte una data da formato "aaaa-mm-gg" a "gg/mm/aaaa"
    public static function raw2date($raw)
    {
        if ($raw == '0000-00-00' || empty($raw)) {
            return "";
        }

        $d = explode('-', $raw);
        $g = $d[2];
        $m = $d[1];
        $a = $d[0];
        return "$g/$m/$a";
    }
}