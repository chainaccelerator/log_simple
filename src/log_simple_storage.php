<?php

/**
 * Trait Log_simple_storage
 */
Trait Log_simple_storage
{

    use Compress_simple;
    use Sign_simple;

    /**
     * @var string
     */
    private static $log_storage_dir = '../data/storage/';

    /**
     * @var
     */
    private $log_storage_ref;
    /**
     * @var
     */
    private $log_storage_data_to_store;
    /**
     * @var
     */
    private $log_storage_data;
    /**
     * @var
     */
    private $log_storage_interface;
    /**
     * @var
     */
    private $log_storage_sign_list;

    /**
     * @param string $ref
     * @param array $access_list
     * @return bool|int
     */
    public function put(string $ref, array $access_list = array())
    {

        $this->log_storage_ref = $ref;
        $this->log_storage_data_to_store = $this->compres(base64_encode(json_encode($this)));

        foreach ($access_list as $public_key => $right_level) {

            $sign = new Log_simple_storage_signed($this->log_storage_data_to_store, $public_key, $right_level);
            $sign_list[$sign->public_key_hashed] = $sign;
        }
        return $this->log_storage_write();
    }

    /**
     * @return bool|int
     */
    private function log_storage_write()
    {

        return file_put_contents(self::$log_storage_dir . $this->log_storage_ref, $this->log_storage_data_to_store);
    }

    /**
     * @param string $ref
     * @param string $signature
     * @param bool $public_key
     * @return bool
     */
    public function log_storage_extract()
    {

        $this->log_storage_ref = $this->interface->ref;
        $data = $this->log_storage_read();
        $verif = $this->sign_verify($data, $this->interface->signature, $this->interface->public_key);

        if ($verif === false) return false;

        $this->log_storage_data = json_decode(base64_decode($this->uncompres($data)));

        return true;
    }

    /**
     * @return false|string
     */
    private function log_storage_read()
    {

        return file_get_contents(self::$log_storage_dir . $this->log_storage_ref);
    }
}
