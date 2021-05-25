<?php
declare(strict_types=1);

namespace Utils\JsonRpc2\Format;

use Structure\Struct;

class BaseFmt extends Struct {
    protected $_special_error;
    protected $_special_code;

    /**
     * 有致命错误
     * @return bool
     */
    public function hasFatalError() : bool
    {
        if(boolval(!$this->_special_error)){
            return boolval(mb_strrpos($this->_special_error, '0x') !== false);
        }
        return false;
    }

    /**
     * 有特殊错误
     * @return bool
     */
    public function hasSpecialError() : bool
    {
        return boolval($this->_special_error);
    }

    /**
     * 获取特殊错误
     * @return string|null
     */
    public function getSpecialError() : ?string
    {
        return $this->_special_error ?? null;
    }

    /**
     * @param string|null $error
     */
    public function setSpecialError(?string $error) : void
    {
        $this->_special_error = $error;
    }

    /**
     * 获取特殊错误码
     * @return string|null
     */
    public function getSpecialCode() : ?string
    {
        return $this->_special_code ? array_values($this->_special_code)[0] : null;
    }

    /**
     * 设置特殊code
     * @param $code
     */
    public function setSpecialCode(?string $code) : void
    {
        $this->_special_code = $code;
    }
}