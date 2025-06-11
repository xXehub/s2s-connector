<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class InitializeRabbitMQQueues extends Command
{
    protected $signature = 'rabbitmq:initialize';
    protected $description = 'Initialize RabbitMQ queues and exchanges';

    public function handle()
    {
        $this->info('Initializing RabbitMQ queues...');

        try {
            $connection = new AMQPStreamConnection(
                env('RABBITMQ_HOST', 'rabbitmq'),
                env('RABBITMQ_PORT', 5672),
                env('RABBITMQ_USER', 'admin'),
                env('RABBITMQ_PASSWORD', 'admin123'),
                env('RABBITMQ_VHOST', '/')
            );

            $channel = $connection->channel();

            // Declare exchange
            $exchangeName = env('RABBITMQ_EXCHANGE_NAME', 'order_exchange');
            $channel->exchange_declare(
                $exchangeName,
                AMQPExchangeType::DIRECT,
                false,
                true,
                false
            );

            // Declare and bind order_processing queue
            $orderQueue = env('RABBITMQ_QUEUE', 'order_processing');
            $channel->queue_declare(
                $orderQueue,
                false,
                true,
                false,
                false
            );
            $channel->queue_bind($orderQueue, $exchangeName, $orderQueue);

            // Declare and bind stock_reduction queue
            $stockQueue = 'stock_reduction';
            $channel->queue_declare(
                $stockQueue,
                false,
                true,
                false,
                false
            );
            $channel->queue_bind($stockQueue, $exchangeName, $stockQueue);

            $channel->close();
            $connection->close();

            $this->info('RabbitMQ queues initialized successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to initialize RabbitMQ queues: ' . $e->getMessage());
            return 1;
        }
    }
}