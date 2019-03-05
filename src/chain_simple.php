<?php

/**
 * Trait Chain_simple
 */
trait Chain_simple {

    use Compress_simple;
    use Block_simple;

    /**
     * @var string
     */
    private static $chain_block_sep = '--------- BLOCK_JSON --------- ';
    /**
     * @var string
     */
    private static $chain_env = 'testnet';
    /**
     * @var
     */
    private static $chain_file;
    /**
     * @var string
     */
    private static $chain_block_file_ext = '.ch';
    /**
     * @var string
     */
    private static $chain_file_add_mode = 'a+';
    /**
     * @var string
     */
    private static $chain_dir = 'data/chain/';

    /**
     * @var string
     */
    private static $chain_sign_prefix = 'PHP';
    /**
     * @var int
     */
    private static $chain_complexity = 4;
    /**
     * @var int
     */
    private static $chain_complexity_fill = 0;

    /**
     * @var int
     */
    public static $chain_block_index = 1;
    /**
     * @var
     */
    public static $chain_block_previous;
    /**
     * @var string
     */
    public static $chain_block_hash_algo = 'sha256';

    /**
     * @var
     */
    protected static $chain_sign;
    /**
     * @var
     */
    private static $chain_hash;

    /**
     * @return string
     */
    public static function chain_init()
    {
        self::$chain_block_last_file = self::$chain_dir.self::$chain_env.self::$chain_block_last_file_ext;
        self::$chain_file = self::$chain_dir.self::$chain_env.self::$chain_block_file_ext;
        self::$chain_sign = self::$chain_sign_prefix;

        while(strlen(self::$chain_sign) < self::$chain_complexity) {

            self::$chain_sign .= self::$chain_complexity_fill;
        }
        return self::$chain_sign;
    }

    /**
     * @return bool
     */
    public function chain_block_next(){

        $timestamp = microtime(true);

        $this->block_data->build();
        $this->block_data->hash_previous = self::$chain_block_previous->hash;

        $this->chain_pow();

        $fp = fopen(self::$chain_file, self::$chain_file_add_mode);
        $data = json_encode($this->block_data);
        $data_compressed = self::compress($data);

        fwrite($fp, $data_compressed);
        fclose($fp);

        self::$chain_block_previous = $this->block_data;
        $this->block_init();
        self::$chain_block_index++;
        $this->block_data->index = self::$chain_block_index;
        $this->block_data->cost = $timestamp - microtime(true);

        return self::$chain_block_index;
    }

    /**
     * @return bool
     */
    public function chain_pow(){

        $nonce = 0;

        while(substr(self::$chain_hash, 0, self::$chain_complexity) !== self::$chain_sign) {

            self::$chain_hash = hash(self::$chain_block_hash_algo, $this->block_data->hash.self::$chain_block_previous->hash.$nonce);
            $nonce++;
        }
        $this->block_data->nonce = $nonce;

        return true;
    }
}