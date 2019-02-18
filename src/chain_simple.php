<?php

trait chain_simple {

    use compress_simple;
    use block_simple;

    private static $chain_block_sep = '--------- BLOCK_JSON --------- ';
    private static $chain_env = 'testnet';
    private static $chain_file;
    private static $chain_block_file_ext = '.ch';
    private static $chain_file_add_mode = 'a+';
    private static $chain_dir = 'data/chain/';

    private static $chain_sign_prefix = 'PHP';
    private static $chain_complexity = 4;
    private static $chain_complexity_fill = 0;

    public static $chain_block_index = 1;
    public static $chain_block_previous;
    public static $chain_block_hash_algo = 'sha256';

    private static $chain_sign;
    private static $chain_hash;

    public static function chain_init()
    {
        self::$chain_block_last_file = self::$chain_dir.self::$chain_env.self::$chain_block_last_file_ext;
        self::$chain_file = self::$chain_dir.self::$chain_env.self::$chain_block_file_ext;
        self::$chain_sign = self::$chain_sign_prefix;

        while(strlen(self::$chain_sign) < self::$chain_complexity) {

            self::$chain_sign .= $chain_complexity_fill;
        }
        return self::$chain_sign;
    }

    public function chain_block_next(){

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

        return true;
    }

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