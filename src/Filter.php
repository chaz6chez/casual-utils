<?php
declare(strict_types=1);

namespace Until;

abstract class Filter extends \Structure\Filter {
    abstract public function filter($var);
}