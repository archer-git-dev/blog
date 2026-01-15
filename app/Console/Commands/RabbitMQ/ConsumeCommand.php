<?php

namespace App\Console\Commands\RabbitMQ;

use App\Services\RabbitMQService;
use Illuminate\Console\Command;

class ConsumeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume
                            {queue : Имя очереди}
                            {--exchange= : Имя обменника (опционально)}
                            {--key= : Routing key для привязки (если указан exchange)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Слушать очередь RabbitMQ';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $queue = $this->argument('queue');
        $exchange = $this->option('exchange');
        $key = $this->option('key') ?? '';

        $service = new RabbitMQService();

        // Передаем кастомный callback, который будет использовать Laravel-логику
        $service->consume($queue, $exchange, $key, function ($msg) {
            $data = json_decode($msg->body, true);

            $this->info("📩 New message received:");
            $this->table(['Key', 'Value'], collect($data)->map(fn($v, $k) => [$k, $v])->toArray());

            // Здесь ты можешь вызвать любую бизнес-логику
            // например: User::create($data); или dispatch(new ProcessOrder($data));

            // Подтверждаем обработку
            $msg->ack();
        });
    }
}
