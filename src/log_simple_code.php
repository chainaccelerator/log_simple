<?php

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

