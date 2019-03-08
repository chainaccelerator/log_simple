<?php

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

    public function function_add(Log_simple_code_function $log_function)
    {

        $this->function_list[] = $log_function;

        return count($this->function_list);
    }

    public function function_get(Log_simple_code_function $function_index)
    {

        return $this->function_list[$function_index];
    }

    public function function_set(Log_simple_code_function $function_index, Log_simple_code_function $log_function)
    {

        $this->function_list[$function_index] = $log_function;

        return true;
    }
}
