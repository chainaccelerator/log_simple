<?php



/**
 * Trait Block_simple
 */
trait Block_simple {

    /**
     * @var Block_simple_data
     */
    private $block_data;

    /**
     * @return bool
     */
    public function block_init() {

        $this->block_data = new Block_simple_data();

        return true;
    }
}