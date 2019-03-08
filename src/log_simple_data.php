<?php


/**
 * Class Log_simple_data
 */
class Log_simple_data {

    /**
     * @var string
     */
    private static $log_hash_algo = 'sha512';
    /**
     * @var string
     */
    private static $log_timestamp_sep = '------ TIMESTAMP ------';
    /**
     * @var string
     */
    private static $log_hash_sep = '------ HASH ------';

    use timestamp_simple;

    /**
     * @var int
     */
    private $date;
    /**
     * @var
     */
    private $data_hash;
    /**
     * @var
     */
    private $data_ref;
    /**
     * @var
     */
    private $workflow_name;
    /**
     * @var
     */
    private $workflow_state;

    /**
     * Log_simple_data constructor.
     */
    public function __construct(){

        self::timestamp_init();
        $this->date = self::timestamp_get();
    }

    /**
     * @param string $data
     * @param string $workflow_name
     * @param string $workflow_state
     * @param string $data_ref
     * @return string
     */
    public function build(string $data, string $workflow_name, string $workflow_state, string $data_ref = '') {

        $this->workflow_name = $workflow_name;
        $this->workflow_state = $workflow_state;
        $this->data_ref = $data_ref;
        $data = $this->timestamp_data($data);
        $this->data_ref = hash(self::$log_hash_algo, $this->to_json());

        return $data;
    }

    /**
     * @param stdClass $obj
     * @return bool
     */
    public function build_from_std(stdClass $obj){

        $this->date = $obj->date;
        $this->data_ref = $obj->data_ref;
        $this->data_hash = $obj->data_hash;
        $this->workflow_name = $obj->workflow_name;
        $this->workflow_state = $obj->workflow_state;
        $this->data_ref = self::timestamp_data($this->to_json);

        return true;
    }

    /**
     * @return false|string
     */
    public function to_json(){

        return json_encode($this);
    }
}

