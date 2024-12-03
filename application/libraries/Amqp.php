<?php
require_once APPPATH . 'third_party/php-amqplib/PhpAmqpLibAutoloader.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Amqp {
    private $connection;
    private $channel;

    public function __construct($config = []) {
        $CI =& get_instance();
        $CI->load->config('amqp', TRUE);
        

        // Merge default configuration with passed configuration
        $config = array_merge($CI->config->item('amqp'), $config);

        // Validate configuration
        // if (empty($config['host']) || empty($config['port']) || empty($config['user']) || empty($config['password'])) {
        //     throw new Exception('AMQP configuration is incomplete. Check your amqp.php config file.');
        // }

        // Establish connection
        $this->connection = new AMQPStreamConnection(
            '127.0.0.1',
            '5672',
            'guest',
            'guest'
        );
        $this->channel = $this->connection->channel();
    }

    public function publish($queue, $message) {
        $this->channel->queue_declare($queue, false, true, false, false);
        $msg = new AMQPMessage($message, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $this->channel->basic_publish($msg, '', $queue);
    }

    public function consume($queue, $callback) {
        $this->channel->queue_declare($queue, false, true, false, false);
        $this->channel->basic_consume($queue, '', false, true, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function close() {
        $this->channel->close();
        $this->connection->close();
    }
}
