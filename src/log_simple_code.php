<?php

/**
 * Class Log_simple_code
 */
class Log_simple_code extends Log_simple_data
{

    use Log_simple_storage;

    /**
     * @var Log_simple_code_Interface
     */
    public $interface;

    /**
     * @param Log_simple_code_Interface $log_code_Interface
     * @return bool
     */
    public function interface_set(Log_simple_code_Interface $log_code_Interface)
    {

        $this->interface = $log_code_Interface;

        return true;
    }

    /**
     * @return mixed
     */
    public function interface_get()
    {

        return $this->interface;
    }

    /**
     * @param Log_simple_code_Interface $log_code_Interface
     * @param Log_simple_code_Interface_call $log_code_Interface_call
     * @return mixed
     */
    public function run(Log_simple_code_Interface_call $log_code_Interface_call)
    {

        $this->load($this->log_storage_interface->ref, $this->log_storage_interface->signature, $this->log_storage_interface->public_key);

        eval($this->log_storage_data);

        $class = $log_code_Interface_call->class;
        $method_static = $log_code_Interface_call->method_static;
        $arg_list = $log_code_Interface_call->arg_list;

        return $class::$method_static($arg_list);
    }

    /**
     * @param string $ref
     * @param string $signature
     * @param bool $public_key
     * @return bool
     */
    private function load(string $ref, string $signature, $public_key = false)
    {

        return $this->log_storage_extract($ref, $signature, $public_key);
    }


}

