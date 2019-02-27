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

class Log_simple_code_function
{

    public $name;
    public $class_name;
    public $argument_list = array();
    public $return_list = array();
    public $stack_type_list = array();

    public function __construct(string $name, string $class_name)
    {
        $this->name = $name;
        $this->class_name = $class_name;
    }

    public static function stack_get()
    {

        return apc_fetch();
    }

    public function argument_add(Log_simple_code_argument $log_code_argument)
    {

        $this->argument_list[$log_code_argument->name] = $log_code_argument;

        return true;
    }

    public function return_add(Log_simple_code_return $log_code_return)
    {

        $this->return_list[$log_code_return->name] = $log_code_return;

        return true;
    }

    public function stack_add($var, string $return_name)
    {

        if ($this->return_list[$return_name]->stack_verif($var)) return false;

        return apc_add($var);
    }

    public function call()
    {

        $class = $this->class_name;
        $method_static = $this->name;

        $obj = $class::$method_static($this->argument_list);
        $res = $this->stack_verif($obj->stack);

        if ($res === false) return false;

        return $obj->return;
    }

    public function stack_verif(array $stack = array())
    {

        foreach ($stack as $key => $value_type) {

            if ($value_type !== $this->stack_type_list[$key]) return false;
        }
        return true;
    }
}

class Log_simple_code_Interface
{

    public $ref;
    public $signature;
    public $public_key;
    public $function_list = array();

    public function __construct(string $ref, string $signature, string $public_key)
    {
        $this->ref = $ref;
        $this->signature = $signature;
        $this->public_key = $public_key;
    }

    public function function_add(Log_simple_code_function $log_function_argument)
    {

        $this->function_list[] = $log_function_argument;

        return true;
    }
}

class Log_simple_code_Interface_call
{

    public $class;
    public $method_static;
    public $arg_list = array();

    public function __construct(string $class, $method_static = '', array $arg_list = array())
    {
        $this->class = $class;
        $this->method_static = $method_static;
        $this->arg_list = $arg_list;
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
