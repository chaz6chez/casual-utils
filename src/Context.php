<?php
declare(strict_types=1);

namespace Utils;

class Context {

    public static $limit = 200;
    /**
     * @var string
     */
    protected static $_context = 'context';

    /**
     * @return array|null
     */
    public static function getContext(): ?array
    {
        return Cache::get(self::$_context,self::$_context,[]);
    }

    /**
     * @param array|null $context
     */
    public static function setContext(?array $context): void
    {
        Cache::set(self::$_context,self::$_context,$context);
    }

    /**
     * @param mixed $data
     */
    public static function add($data): void
    {
        if(count($context = self::getContext()) >= self::$limit){
            array_shift($context);
        }
        $context[] = $data;
        Cache::set(self::$_context,self::$_context,$context);
    }

    /**
     * @param bool $reset
     * @return array|null
     */
    public static function context(bool $reset = true) :?array
    {
        $res = self::getContext();
        if($reset){
            self::setContext(null);
        }
        return $res;
    }
}