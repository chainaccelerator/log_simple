<?php

/**
 * Class Log_simple_code_Interface_call
 */
class Log_simple_code_Interface_call
{

    /**
     * @var string
     */
    public $class;
    /**
     * @var string
     */
    public $method_static;
    /**
     * @var array
     */
    public $arg_list = array();

    /**
     * Log_simple_code_Interface_call constructor.
     * @param string $class
     * @param string $method_static
     * @param array $arg_list
     */
    public function __construct(string $class, $method_static = '', array $arg_list = array())
    {
        $this->class = $class;
        $this->method_static = $method_static;
        $this->arg_list = $arg_list;
    }

}

