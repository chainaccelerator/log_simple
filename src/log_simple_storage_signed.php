<?php


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

