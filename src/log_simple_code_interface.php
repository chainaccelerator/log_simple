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

    public function function_add(Log_simple_code_function $log_function_argument)
    {

        $this->function_list[] = $log_function_argument;

        return true;
    }
}
