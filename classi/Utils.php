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

    public static function raw2Year($raw)
    {
        if ($raw == '0000-00-00' || empty($raw)) {
            return "";
        }
        $d = explode('-', $raw);
        $a = $d[0];
        return $a;
    }
    /**
     * converte una data da formato "gg/mm/aaaa" a "aaaa-mm-gg"
     */
    public static function date2raw($date)
    {
        $d = explode('/', $date);
        $g = $d[0];
        $m = $d[1];
        $a = $d[2];
        return "$a-$m-$g";
    }

    /**
     * converte una data da formato "aaaa-mm-gg" a "gg/mm/aaaa"
     */
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

    public static function unix2date($unix)
    {
        if (empty($unix)) {
            return "";
        }
        return date("d/m/Y", $unix);
    }

    public static function unix2year($unix)
    {
        if (empty($unix)) {
            return "";
        }
        return date("Y", $unix);
    }

    public static function unix2rawDate($unix)
    {
        if (empty($unix)) {
            return "";
        }
        return date("Y-m-d", $unix);
    }

    public static function unix2timestamp($unix)
    {
        if (empty($unix)) {
            return "";
        }
        return date("d/m/Y H:i", $unix);
    }

    public static function unix2rawTimestamp($unix)
    {
        if (empty($unix)) {
            return "";
        }
        return date("Y-m-d H:i:s", $unix);
    }

    public static function date2unix($source)
    {
        if (empty($source)) {
            return 0;
        }

        $data = explode("/", $source);

        $giorno = intval($data[0]);
        $mese = intval($data[1]);
        $anno = intval($data[2]);

        return mktime(0, 0, 0, $mese, $giorno, $anno);
    }

    public static function rawdate2unix($source)
    {
        if (empty($source)) {
            return 0;
        }

        $data = explode("-", $source);

        $giorno = $data[2];
        $mese = $data[1];
        $anno = $data[0];

        return mktime(0, 0, 0, $mese, intval($giorno), $anno);
    }

    public static function datetime2unix($source)
    {
        if (empty($source)) {
            return 0;
        }

        $time = explode(" ", $source);
        $data = explode("-", $time[0]);
        $orario = explode(":", $time[1]);
        $giorno = $data[2];
        $mese = $data[1];
        $anno = $data[0];
        $ore = $orario[0];
        $minuti = $orario[1];
        $secondi = $orario[2];

        return mktime($ore, $minuti, $secondi, $mese, intval($giorno), $anno);
    }
}