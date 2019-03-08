<?php

/**
 * Trait log_simple
 */
trait Log_simple {

    use Timestamp_simple;

    /**
     * @var Log_simple_data
     */
    private $log_data;

    /**
     * @return bool
     */
    public function log_init(){

        $this->log_data = new Log_simple_data();

        return true;
    }

    /**
     * @param string $data
     * @param string $workflow_name
     * @param string $workflow_state
     * @param string $data_ref
     * @return mixed
     */
    public function log_build(string $data, string $workflow_name, string $workflow_state, string $data_ref = '') {

        $this->log_data->build($data, $workflow_name, $workflow_state, $data_ref);

        return $this->log_data;
    }
}
