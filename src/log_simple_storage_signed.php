<?php


/**
 * Class Log_simple_storage_signed
 */
class Log_simple_storage_signed
{

    use Sign_simple;

    /**
     * @var int
     */
    private static $log_storage_public_key_hash_algo = OPENSSL_ALGO_SHA1;

    /**
     * @var string
     */
    public $public_key_hashed;
    /**
     * @var int
     */
    public $right_level = 1;
    /**
     * @var bool
     */
    public $sign;

    /**
     * Log_simple_storage_signed constructor.
     * @param string $data_to_store
     * @param string $public_key
     * @param int $right_level
     */
    public function __construct(string $data_to_store, string $public_key, int $right_level)
    {

        $this->public_key_hashed = hash(self::$log_storage_public_key_hash_algo, $public_key);
        $this->right_level = $right_level;
        $this->sign = $this->sign($data_to_store . $this->right_level);
    }
}

