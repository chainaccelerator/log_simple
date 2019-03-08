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

    /**
     * @var string $step_name
     * @var string $height_new_push_address
     * @return Block_simple_data
     */
    public function block_get(string $step_name, string $height_new_push_address) {

        $response = $this->socket_client_push_broadcast_request_count('/'.$step_name.'/address/'.$height_new_push_address.'/block');

        $block_data = $response->data->block_data;

        return $block_data;
    }


}