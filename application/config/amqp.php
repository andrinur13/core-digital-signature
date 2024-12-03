<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['amqp'] = [
    'host' => '127.0.0.1',  // Replace with your RabbitMQ server's IP or hostname
    'port' => '5672',         // Default RabbitMQ port
    'user' => 'guest',      // RabbitMQ username
    'password' => 'guest',  // RabbitMQ password
];
