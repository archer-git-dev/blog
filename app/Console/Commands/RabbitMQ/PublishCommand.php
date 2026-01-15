<?php

namespace App\Console\Commands\RabbitMQ;

use App\Services\RabbitMQService;
use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:publish
                            {exchange : Имя обменника}
                            {routing-key : Ключ маршрутизации}
                            {message : Сообщение (JSON строка или текст)}
                            {--type=direct : Тип обменника (direct, topic, fanout)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправить сообщение в RabbitMQ';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $exchange = $this->argument('exchange');
        $routingKey = $this->argument('routing-key');
        $messageRaw = $this->argument('message');
        $type = $this->option('type');

        // Пытаемся распарсить как JSON, если не получится — отправим как есть
        $message = json_decode($messageRaw, true) ?? $messageRaw;

        $service = new RabbitMQService();
        $service->publish($exchange, $routingKey, $message, $type);

        $this->info("Message published successfully!");
    }
}
