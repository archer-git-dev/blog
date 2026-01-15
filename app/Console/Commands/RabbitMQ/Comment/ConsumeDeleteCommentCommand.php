<?php

namespace App\Console\Commands\RabbitMQ\Comment;

use App\Jobs\DeleteCommentNotificationJob;
use App\Services\RabbitMQService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ConsumeDeleteCommentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume-delete-comment
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
            $comment = json_decode($msg->body, true);

            $this->info("📩 Комментарий пользователя {$comment['user_id']} удаляется");

            DeleteCommentNotificationJob::dispatch($comment);

            // Подтверждаем обработку
            $msg->ack();
        });
    }
}
