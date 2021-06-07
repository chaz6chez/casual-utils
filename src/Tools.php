<?php
declare(strict_types=1);

namespace Utils;

class Tools
{

    public static function log($module, $log, $dir, $tag = 'LOG')
    {
        $dir = "{$dir}/{$module}";
        $name = date('Y-m-d') . '.log';
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                return false;
            }
        }
        $log = is_scalar($log) ? (string)$log : json_encode($log, JSON_UNESCAPED_UNICODE);

        if (file_exists($path = "{$dir}/{$name}")) {
            return file_put_contents($path, date('H:i:s') . " [{$tag}] {$log}\n", FILE_APPEND | LOCK_EX);
        } else {
            return file_put_contents($path, date('H:i:s') . " [{$tag}] {$log}\n", LOCK_EX);
        }
    }

    /**
     * 判断REDIS是否超时
     * @param \RedisException $e
     * @return bool
     */
    public static function isRedisTimeout(\RedisException $e): bool
    {
        return ($e->getCode() == 10054 or $e->getMessage() == 10054);
    }

    /**
     * 是否是全局启动
     * @return bool
     */
    public static function isGlobalStart(): bool
    {
        if (
            defined('GLOBAL_START') and
            GLOBAL_START
        ) {
            return true;
        }
        return false;
    }

    /**
     * WinOs
     * @param bool $exit
     * @return bool
     */
    public static function isWinOs($exit = false): bool
    {
        if (strpos(strtolower(PHP_OS), 'win') === 0) {
            if ($exit) {
                exit('please use launcher.bat' . PHP_EOL);
            }
            return true;
        }
        return false;
    }

    /**
     * 是否在debug模式
     * @return bool
     */
    public static function isDebug(): bool
    {
        if (defined('DEBUG') and DEBUG) {
            return true;
        }
        return false;
    }

    /**
     * 判断grpc拓展是否支持
     * @param bool $master
     * @return array
     */
    public static function grpcExtensionSupport($master = true) : array
    {
        if (!extension_loaded('grpc')) {
            if ($master) {
                echo "no support grpc\n";
                exit;
            }
            return [false, "no support grpc\n"];
        }
        return [true, null];
    }

    /**
     * 判断grpc拓展是否支持
     * @param bool $master
     * @return array
     */
    public static function grpcForkSupport($master = true) : array
    {
        if (PHP_OS === 'Linux') {
            if (
                getenv('GRPC_ENABLE_FORK_SUPPORT') != '1' or
                getenv('GRPC_POLL_STRATEGY') != 'epoll1'
            ) {
                if ($master) {
                    echo "grpc extension environment variables not ready\n";
                    exit;
                }
                return [false, "grpc extension environment variables not ready\n"];
            }
        }
        return [true, null];
    }

    /**
     * @return int
     */
    public static function getNowTime() : int
    {
        return isset($GLOBALS['NOW_TIME']) ? $GLOBALS['NOW_TIME'] : time();
    }

    /**
     * @return float
     */
    public static function getMemoryUsed() : float
    {
        return round(memory_get_usage(false) / 1024 / 1024, 2);
    }

    /**
     * @param string $prefix
     * @return string
     */
    public static function randomString($prefix = ''): string
    {
        return md5(self::UUIDFake($prefix));
    }

    /**
     * @param string $prefix
     * @return string
     */
    public static function UUID($prefix = ''): string
    {
        if (extension_loaded('uuid') and function_exists('uuid_create')) {
            return $prefix . uuid_create(1);
        }
        return self::UUIDFake($prefix);
    }

    /**
     * @param string $uuid_a
     * @param string $uuid_b
     * @return bool
     */
    public static function UUIDCompare(string $uuid_a, string $uuid_b): bool
    {
        if (
            extension_loaded('uuid') and
            function_exists('uuid_compare')
        ) {
            return uuid_compare($uuid_a, $uuid_b);
        }
        throw new \RuntimeException('not support: uuid');
    }

    /**
     * @param $prefix
     * @return string
     */
    public static function UUIDFake($prefix = ''): string
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);
        return $prefix . $uuid;

    }

    public static function CamelToLower(string $str): string
    {
        return strtolower(trim(preg_replace('/[A-Z]/', '_\\0', $str), '_'));
    }

    public static function LowerToCamel(string $str): string
    {
        return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($match) {
            return strtoupper($match[1]);
        }, $str));
    }

    public static function ArrayToXml($arr) : string
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . array2xml($val) . "</" . $key . ">";
            } elseif (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}