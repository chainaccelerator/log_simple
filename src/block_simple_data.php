<?php

/**
 * Class Block_simple_data
 */
class Block_simple_data extends Log_simple_data {

    /**
     * @var string
     */
    public static $workflow_name = 'block';
    /**
     * @var string
     */
    public static $workflow_state_initial = 'initial';

    /**
     * @var array
     */
    public $list = array();
    /**
     * @var int
     */
    public $index;
    /**
     * @var string
     */
    public $data;
    /**
     * @var int
     */
    public $nonce;
    /**
     * @var string
     */
    public $hash;
    /**
     * @var
     */
    public $hash_previous;
    /**
     * @var int
     */
    public $cost;

    /**
     * @return string
     */
    public function build() {

        return parent::build(implode("\n", $this->list), self::$workflow_name, self::$workflow_state_initial);
    }

    /**
     * @param Log_simple_data $log_data
     */
    function log_add(Log_simple_data $log_data){

        $this->list[] = $log_data;
    }
}
