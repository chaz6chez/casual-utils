<?php
declare(strict_types=1);

namespace Utils;

class Cache {

    /**
     * @var array [id => [key => value] ...]
     */
    protected static $_caches = [];

    /**
     * @param string $id
     * @param string $key
     * @param $value
     */
    public static function set(string $id, string $key, $value): void
    {
        self::$_caches[$id][$key] = $value;
    }

    /**
     * @return string[]
     */
    public static function ids() : array
    {
        return array_keys(self::$_caches);
    }

    /**
     * @param string $id
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public static function get(string $id, string $key, $default = null)
    {
        return self::exists($id, $key) ? self::$_caches[$id][$key] : $default;
    }

    /**
     * @param string $id
     * @return array
     */
    public static function gets(string $id) : array
    {
        return self::exists($id) ? self::$_caches[$id] : [];
    }

    /**
     * @param string $id
     * @param string $key
     */
    public static function del(string $id, string $key): void
    {
        if(self::exists($id, $key)){
            unset(self::$_caches[$id][$key]);
        }
    }

    /**
     * @param string|null $id
     */
    public static function clear(?string $id = null): void
    {
        if($id === null){
            self::$_caches = [];
        }else if(self::exists($id)){
            unset(self::$_caches[$id]);
        }
    }

    /**
     * @param string $id
     * @param string|null $key
     * @return bool
     */
    public static function exists(string $id, ?string $key = null): bool
    {

        return $key === null ? boolval(isset(self::$_caches[$id])) : boolval(isset(self::$_caches[$id][$key]));
    }

    /**
     * @return string
     */
    public static function id() :string
    {
        if(extension_loaded('uuid') and function_exists('uuid_create')){
            return uuid_create(1);
        }
        $chars = md5(uniqid(mt_rand(), true));
        $uuid  = substr($chars,0,8) . '-';
        $uuid .= substr($chars,8,4) . '-';
        $uuid .= substr($chars,12,4) . '-';
        $uuid .= substr($chars,16,4) . '-';
        $uuid .= substr($chars,20,12);
        return $uuid;
    }
}