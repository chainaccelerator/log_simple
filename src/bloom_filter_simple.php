<?php
/*
Copyright (c) 2012, Da Xue
All rights reserved.
Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
3. The name of the author nor the names of its contributors may be used
   to endorse or promote products derived from this software without
   specific prior written permission.
THIS SOFTWARE IS PROVIDED BY DA XUE ''AS IS'' AND ANY
EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL DA XUE BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
/* https://github.com/dsx724/php-bloom-filter */

/**
 * Trait Bloom_filter_simple
 */
Trait Bloom_filter_simple {

    /**
     * @var int
     */
    private $bloom_filter_n = 0; // # of entries
    /**
     * @var int
     */
    private $bloom_filter_m; // # of bits in array
    /**
     * @var int
     */
    private $bloom_filter_k; // # of hash functions
    /**
     * @var string
     */
    private $bloom_filter_hash;
    /**
     * @var int
     */
    private $bloom_filter_mask;
    /**
     * @var int
     */
    private $bloom_filter_chunk_size; // # of bytes to push off hash to generate an address
    /**
     * @var string
     */
    private $bloom_filter_bit_array; // data structure
    /**
     * @var int
     */
    private $bloom_filter_hash_times;

    /**
     * @param $bf1
     * @param $bf2
     * @param $bfout
     * @param bool $union
     * @return bool
     */
    private static function bloom_filter_merge($bf1, $bf2, $bfout, $union = false){
        if ($bf1->bloom_filter_m != $bf2->bloom_filter_m) return false;
        if ($bf1->bloom_filter_k != $bf2->bloom_filter_k) return false;
        if ($bf1->bloom_filter_hash != $bf2->bloom_filter_hash) return false;
        // $length = strlen($bfout->bloom_filter_bit_array);
        if ($union){
            $bfout->bloom_filter_bit_array = $bf1->bloom_filter_bit_array | $bf2->bloom_filter_bit_array;
            $bfout->bloom_filter_n = $bf1->bloom_filter_n + $bf2->bloom_filter_n;
        } else {
            $bfout->bloom_filter_bit_array = $bf1->bloom_filter_bit_array & $bf2->bloom_filter_bit_array;
            $bfout->bloom_filter_n = abs($bf1->bloom_filter_n - $bf2->bloom_filter_n);
        }
        return true;
    }

    /**
     * @param $n
     * @param $p
     * @return Bloom_filter_simple|bool
     */
    public static function bloom_filter_createFromProbability($n, $p){
        if ($p <= 0 || $p >= 1)  return false;
        if ($n <= 0)  return false;
        $k = floor(log(1/$p,2));
        $m = pow(2,ceil(log(-$n*log($p)/pow(log(2),2),2))); //approximate estimator method
        return new self($m,$k);
    }

    /**
     * @param $bf1
     * @param $bf2
     * @return Bloom_filter_simple
     */
    public static function bloom_filter_getUnion($bf1, $bf2){
        $bf = new self($bf1->bloom_filter_m,$bf1->bloom_filter_k,$bf1->bloom_filter_hash);
        self::bloom_filter_merge($bf1,$bf2,$bf,true);
        return $bf;
    }

    /**
     * @param $bf1
     * @param $bf2
     * @return Bloom_filter_simple
     */
    public static function bloom_filter_getIntersection($bf1, $bf2){
        $bf = new self($bf1->bloom_filter_m,$bf1->bloom_filter_k,$bf1->bloom_filter_hash);
        self::bloom_filter_merge($bf1,$bf2,$bf,false);
        return $bf;
    }

    /**
     * Bloom_filter_simple constructor.
     * @param $m
     * @param $k
     * @param string $h
     */
    public function __construct($m, $k, $h='md5'){
        if ($m < 8)  return false;
        if (($m & ($m - 1)) !== 0)  return false;
        if ($m > 8589934592)  return false;
        $this->bloom_filter_m = $m; //number of bits
        $this->bloom_filter_k = $k;
        $this->bloom_filter_hash = $h;
        $address_bits = (int)log($m,2);
        $this->bloom_filter_mask = (1 << $address_bits) - 8;
        $this->bloom_filter_chunk_size = (int)ceil($address_bits / 8);
        $this->bloom_filter_hash_times = ((int)ceil($this->bloom_filter_chunk_size * $this->bloom_filter_k / strlen(hash($this->bloom_filter_hash,null,true)))) - 1;
        $this->bloom_filter_bit_array = (binary)(str_repeat("\0",$this->bloom_filter_getArraySize(true)));

        return true;
    }

    /**
     * @param int $n
     * @return float|int
     */
    public function bloom_filter_calculateProbability($n = 0){
        return pow(1-pow(1-1/$this->bloom_filter_m,$this->bloom_filter_k*($n ?: $this->bloom_filter_n)),$this->bloom_filter_k);
        // return pow(1-exp($this->bloom_filter_k*($n ?: $this->bloom_filter_n)/$this->bloom_filter_m),$this->bloom_filter_k); //approximate estimator
    }

    /**
     * @param $p
     * @return float
     */
    public function bloom_filter_calculateCapacity($p){
        return floor($this->bloom_filter_m*log(2)/log($p,1-pow(1-1/$this->bloom_filter_m,$this->bloom_filter_m*log(2))));
    }

    /**
     * @return int
     */
    public function bloom_filter_getElementCount(){
        return $this->bloom_filter_n;
    }

    /**
     * @param bool $bytes
     * @return int
     */
    public function bloom_filter_getArraySize($bytes = false){
        return $this->bloom_filter_m >> ($bytes ? 3 : 0);
    }

    /**
     * @return mixed
     */
    public function bloom_filter_getHashCount(){
        return $this->bloom_filter_k;
    }

    /**
     * @param null $p
     * @return string
     */
    public function bloom_filter_getInfo($p = null){
        $units = array('','K','M','G','T','P','E','Z','Y');
        $M = $this->bloom_filter_getArraySize(true);
        $magnitude = intval(floor(log($M,1024)));
        $unit = $units[$magnitude];
        $M /= pow(1024,$magnitude);
        return 'Allocated '.$this->bloom_filter_getArraySize().' bits ('.$M.' '.$unit.'Bytes)'.PHP_EOL.
            'Using '.$this->bloom_filter_getHashCount(). ' ('.($this->bloom_filter_chunk_size << 3).'b) hashes'.PHP_EOL.
            'Contains '.$this->bloom_filter_getElementCount().' elements'.PHP_EOL.
            (isset($p) ? 'Capacity of '.number_format($this->bloom_filter_calculateCapacity($p)).' (p='.$p.')'.PHP_EOL : '');
    }

    /**
     * @param $key
     */
    public function Bloom_filter_add($key){
        $hash = hash($this->bloom_filter_hash,$key,true);
        for ($i = 0; $i < $this->bloom_filter_hash_times; $i++) $hash .= hash($this->bloom_filter_hash,$hash,true);
        for ($index = 0; $index < $this->bloom_filter_k; $index++){
            $hash_sub = hexdec(unpack('H*',substr($hash,$index*$this->bloom_filter_chunk_size,$this->bloom_filter_chunk_size))[1]);
            $word = ($hash_sub & $this->bloom_filter_mask) >> 3;
            $this->bloom_filter_bit_array[$word] = $this->bloom_filter_bit_array[$word] | chr(1 << ($hash_sub & 7));
        }
        $this->bloom_filter_n++;
    }

    /**
     * @param $key
     * @return bool
     */
    public function bloom_filter_contains($key){
        $hash = hash($this->bloom_filter_hash,$key,true);
        for ($i = 0; $i < $this->bloom_filter_hash_times; $i++) $hash .= hash($this->bloom_filter_hash,$hash,true);
        for ($index = 0; $index < $this->bloom_filter_k; $index++){
            $hash_sub = hexdec(unpack('H*',substr($hash,$index*$this->bloom_filter_chunk_size,$this->bloom_filter_chunk_size))[1]);
            if ((ord($this->bloom_filter_bit_array[($hash_sub & $this->bloom_filter_mask) >> 3]) & (1 << ($hash_sub & 7))) === 0) return false;
        }
        return true;
    }

    /**
     * @param $bf
     */
    public function bloom_filter_unionWith($bf){
        self::bloom_filter_merge($this,$bf,$this,true);
    }

    /**
     * @param $bf
     */
    public function bloom_filter_intersectWith($bf){
        self::bloom_filter_merge($this,$bf,$this,false);
    }
}
