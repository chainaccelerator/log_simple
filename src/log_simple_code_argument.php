<?php

class Log_simple_code_argument
{

    public $name;
    public $type;
    public $default;
    public $size_max;
    public $required = true;
    public $value;

    public function __construct(string $name, string $type, $default, int $size_max, bool $required = true)
    {
        $this->name = $name;
        $this->type = $type;
        $this->default = $default;
        $this->size_max = $size_max;
        $this->required = $required;
    }
}
