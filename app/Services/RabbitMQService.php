<?php

namespace App\Services;

use Exception;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    private AMQPStreamConnection $connection;
    private AbstractChannel|AMQPChannel $channel;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        // Подключаемся к RabbitMQ (данные из .env)
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'rabbitmq'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'user'),
            env('RABBITMQ_PASSWORD', 'secret'),
            env('RABBITMQ_VHOST', '/')
        );

        $this->channel = $this->connection->channel();
    }

    /**
     * Отправка сообщения
     *
     * @param string $exchange Имя обменника
     * @param string $routingKey Ключ маршрутизации
     * @param mixed $message Данные (будут сериализованы в JSON)
     * @param string $exchangeType Тип обменника (direct, fanout, topic)
     */
    public function publish(
        string $exchange,
        string $routingKey,
        mixed  $message,
        string $exchangeType = 'direct'
    ): void
    {
        // Объявляем обменник (если не существует, создастся)
        $this->channel->exchange_declare(
            $exchange,        // Имя обменника
            $exchangeType,    // Тип (direct, topic, fanout, headers)
            false,            // Passive (не создавать, только проверить)
            true,             // Durable (переживет перезагрузку)
            false             // Auto-delete (не удалять автоматически)
        );

        // Преобразуем данные в JSON и создаем AMQP-сообщение
        $amqpMessage = new AMQPMessage(
            json_encode($message),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT] // Сохранить на диск
        );

        // Публикуем в обменник
        $this->channel->basic_publish($amqpMessage, $exchange, $routingKey);

        echo " [✓] Sent to exchange '{$exchange}' with routing key '{$routingKey}'\n";
    }

    /**
     * Получение сообщений (Consumer)
     *
     * @param string $queueName Имя очереди
     * @param string $exchange Имя обменника (если нужна привязка)
     * @param string $routingKey Ключ для привязки
     * @param callable|null $callback Функция, которая обработает сообщение
     */
    public function consume(
        string $queueName,
        string $exchange = '',
        string $routingKey = '',
        callable $callback = null
    ): void
    {
        // Объявляем очередь
        $this->channel->queue_declare(
            $queueName, // Имя очереди
            false,      // Passive
            true,       // Durable (переживет перезагрузку)
            false,      // Exclusive (не эксклюзивная)
            false       // Auto-delete
        );

        // Если указан обменник, делаем привязку (Binding)
        if ($exchange) {
            $this->channel->exchange_declare($exchange, 'direct', false, true, false);
            $this->channel->queue_bind($queueName, $exchange, $routingKey);
            echo " [*] Queue '{$queueName}' bound to exchange '{$exchange}' with key '{$routingKey}'\n";
        }

        echo " [*] Waiting for messages in queue '{$queueName}'. To exit press CTRL+C\n";

        // Если callback не передан, используем дефолтный (просто выводим данные)
        $defaultCallback = function ($msg) {
            $data = json_decode($msg->body, true);
            echo " [x] Received: " . print_r($data, true) . "\n";

            // ВАЖНО: Подтверждаем, что обработали (ACK)
            $msg->ack();
        };

        $this->channel->basic_consume(
            $queueName,              // Очередь
            '',                      // Consumer tag (автогенерация)
            false,                   // No local
            false,                   // No ack (мы будем ACK-ать вручную)
            false,                   // Exclusive
            false,                   // No wait
            $callback ?? $defaultCallback
        );

        // Бесконечный цикл ожидания сообщений
        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
