<?php

/**
 * Class Block_simple_data
 */
class Block_simple_data extends Log_simple_data {

    /**
     * @var string
     */
    protected static $workflow_name = 'block';
    /**
     * @var string
     */
    protected static $workflow_state_initial = 'initial';

    /**
     * @var array
     */
    protected $list = array();
    /**
     * @var int
     */
    protected $index;
    /**
     * @var string
     */
    protected $data;
    /**
     * @var int
     */
    protected $nonce;
    /**
     * @var string
     */
    protected $hash;
    /**
     * @var
     */
    protected $hash_previous;
    /**
     * @var int
     */
    protected $cost;

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

/**
 * Trait Block_simple
 */
trait Block_simple {

    /**
     * @var Block_simple_data
     */
    private $block_data;

    /**
     * @return bool
     */
    public function block_init() {

        $this->block_data = new Block_simple_data();

        return true;
    }
}