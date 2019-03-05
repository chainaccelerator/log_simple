<?php

Trait Log_simple_storage
{

    use compress_simple;

    private static $log_storage_dir = '../data/storage/';

    private $log_storage_ref;
    private $log_storage_data_to_store;
    private $log_storage_data;
    private $log_storage_sign_list;

    public function put(string $ref, string $data, array $access_list = array())
    {

        $this->log_storage_ref = $ref;
        $this->log_storage_data_to_store = $this->compres(base64_encode(json_encode($this)));

        foreach ($access_list as $public_key => $right_level) {

            $sign = new Log_simple_storage_signed($this->log_storage_data_to_store, $public_key, $right_level);
            $sign_list[$sign->public_key_hashed] = $sign;
        }
        return $this->log_storage_write();
    }

    private function log_storage_write()
    {

        return file_put_contents(self::$log_storage_dir . $this->log_storage_ref, $this->log_storage_data_to_store);
    }

    public function log_storage_extract(string $ref, string $signature, $public_key = false)
    {

        $this->log_storage_ref = $ref;
        $data = $this->log_storage_read();
        $verif = $this->sign_verify($data, $signature, $public_key);

        if ($verif === false) return false;

        $this->log_storage_data = json_decode(base64_decode($this->uncompres($data)));

        return true;
    }

    private function log_storage_read()
    {

        return file_get_contents(self::$log_storage_dir . $this->log_storage_ref);
    }
}

class Log_simple_storage_signed
{

    use Sign_simple;

    private static $log_storage_public_key_hash_algo = OPENSSL_ALGO_SHA1;

    public $public_key_hashed;
    public $right_level = 1;
    public $sign;

    public function __construct(string $data_to_store, string $public_key, int $right_level)
    {

        $this->sign_init();

        $this->public_key_hashed = hash(self::$log_storage_public_key_hash_algo, $public_key);
        $this->right_level = $right_level;
        $this->sign = $this->sign($data_to_store . $this->right_level);
    }

}