<?php

class Log_simple_code_return
{

    public $name;
    public $type;
    public $default;
    public $size_max;
    public $required = true;
    public $false_value = false;
    public $null_value = '';
    public $empty_value = array();
    public $final_state = false;
    public $value;

    public function __construct(string $name, string $type, $default, int $size_max, bool $required = true,
                                bool $false_value = false, string $null_value = '', array $empty_value = array(), bool $final_state = false)
    {

        $this->name = $name;
        $this->type = $type;
        $this->default = $default;
        $this->size_max = $size_max;
        $this->required = $required;
        $this->false_value = $false_value;
        $this->null_value = $null_value;
        $this->empty_value = $empty_value;
        $this->final_state = $final_state;
    }

    public function stack_verif($var)
    {

        if (gettype($var) !== $this->type) return false;
        if (sizeof($var) !== $this->size_max) return false;
        if ($this->required === true && $var === $this->null_value) return false;

        return true;
    }
}
