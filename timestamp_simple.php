<?php

trait timestamp_simple {

    private static $timestamp_timezone = 'UTC';

    public static function timestamp_init() {
        
        date_default_timezone_set($timestamp_timezone);
    }

    public static function timestamp_get(){

        return time();
    }

    public function timestamp_data(string $data){

        $data .= self::$timestamp_sep.$this->date;
        $this->hash = hash(self::$hash_algo, $data.$this->date);
        $data .= self::$hash_sep.$this->hash;

        return $data;
    }
}