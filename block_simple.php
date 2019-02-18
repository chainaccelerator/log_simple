<?php

class Block_simple_data extends Log_simple_data {

    private static $workflow_name = 'block';
    private static $workflow_state_initial = 'initial';

    private $list = array();
    private $index;
    private $data;
    private $nonce;
    private $hash;
    private $previous_hash;

    public function block_build() {

        return parent::build(implode("\n", $this->list), self::$workflow_name, self::$workflow_state_initial);
    }

    function log_add(Log_simple_data $log_data){

        $this->list[] = $log_data;
    }
}

trait block_simple{

    private $block_data;

    public function block_init() {

        $this->block_data = new Block_simple_data();

        return true;
    }
}