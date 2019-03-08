<?php

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

