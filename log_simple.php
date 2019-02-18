<?php

class Log_simple_data {

    private static $log_hash_algo = 'sha512';
    private static $log_timestamp_sep = '------ TIMESTAMP ------';
    private static $log_hash_sep = '------ HASH ------';

    use timestamp_simple;

    private $date;
    private $data_hash;
    private $data_ref;
    private $workflow_name;
    private $workflow_state;

    public function __construct(){

        self::timestamp_init();
        $this->date = self::timestamp_get();
    }

    public function build(string $data, string $workflow_name, string $workflow_state, string $data_ref = '') {

        $this->workflow_name = $workflow_name;
        $this->workflow_state = $workflow_state;
        $this->data_ref = $data_ref;
        $data = $this->timestamp_data($data);
        $this->data_ref = hash(self::$log_hash_algo, $this->to_json());

        return $data;
    }

    public function build_from_std(stdClass $obj){

        $this->date = $obj->date;
        $this->data_ref = $obj->data_ref;
        $this->data_hash = $obj->data_hash;
        $this->workflow_name = $obj->workflow_name;
        $this->workflow_state = $obj->workflow_state;
        $this->data_ref = self::timestamp_data($this->to_json);

        return true;
    }

    public function to_json(){

        return json_encode($this);
    }
}

trait log_simple {

    use timestamp_simple;

    private $log_data;

    public function log_init(){

        $this->log_data = new Log_simple_data();

        return true;
    }

    public function log_build(string $data, string $workflow_name, string $workflow_state, string $data_ref = '') {

        $this->log_data->build($data, $workflow_name, $workflow_state, $data_ref);

        return $this->log_data;
    }
}