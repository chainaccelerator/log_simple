<?php

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
