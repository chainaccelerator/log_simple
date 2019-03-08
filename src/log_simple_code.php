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


class Log_simple_code_function_return_final
{

    public $return;
    public $method;

    public function __construct(Log_simple_code_return $return, Log_simple_code_function $method)
    {
        $this->return = $return;
        $this->method = $method;
    }
}


class Log_simple_code extends Log_simple_data
{

    use Log_simple_storage;

    private $interface;

    public function interface_set(Log_simple_code_Interface $log_code_Interface)
    {

        $this->interface = $log_code_Interface;

        return true;
    }

    public function run(Log_simple_code_Interface $log_code_Interface, Log_simple_code_Interface_call $log_code_Interface_call)
    {

        $this->load($log_code_Interface->ref, $log_code_Interface->signature, $log_code_Interface->public_key);

        eval($this->log_storage_data);

        $class = $log_code_Interface_call->class;
        $method_static = $log_code_Interface_call->method_static;
        $arg_list = $log_code_Interface_call->arg_list;

        return $class::$method_static($arg_list);
    }

    private function load(string $ref, string $signature, $public_key = false)
    {

        return $this->log_storage_extract($ref, $signature, $public_key);
    }


}
