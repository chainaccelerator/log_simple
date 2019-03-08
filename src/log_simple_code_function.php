<?php

class Log_simple_code_function
{

    public $name;
    public $class_name;
    public $argument_list = array();
    public $return_list = array();

    public function __construct(string $name, string $class_name)
    {
        $this->name = $name;
        $this->class_name = $class_name;
    }

    public static function stack_get(string $var_name)
    {

        return apc_fetch($var_name);
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

        return apc_add($return_name, $var);
    }

    public function call()
    {

        $class = $this->class_name;
        $method_static = $this->name;
        $function_return_final = $class::$method_static($this->argument_list);

        if ($function_return_final->method->stack_verif() === false) return false;

        return $function_return_final->return;
    }

    public function stack_verif()
    {

        foreach ($this->return_list as $return_name => $var) {

            $var_to_test = self::stack_get($return_name);

            if ($var->stack_verif($var_to_test) === false) return false;
        }
        return true;
    }
}
