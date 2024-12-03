<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['rabbitmq'] = [
    'host' => 'localhost',  // RabbitMQ host
    'port' => 5672,         // RabbitMQ port
    'user' => 'guest',      // RabbitMQ username
    'password' => 'guest',  // RabbitMQ password
    'vhost' => '/'          // RabbitMQ virtual host
];