#!/usr/bin/env php
<?php

use App\Models\User;
use App\Services\MessageService;
use Illuminate\Contracts\Console\Kernel;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$messageService = $app->make(MessageService::class);

function sendTestMessage(MessageService $service, User $from, User $to, string $content): void
{
    /** @var \App\Models\Message $message */
    $message = $service->sendMessage($from, 'general', $content);

    echo sprintf(
        "[%s] sent from %s (id=%d) to %s (id=%d) -> message #%d: %s\n",
        now()->toDateTimeString(),
        $from->name,
        $from->id,
        $to->name,
        $to->id,
        $message->id,
        $message->content,
    );
}

$user1 = User::find(1);
$user2 = User::find(2);

if (!$user1 || !$user2) {
    echo "Please ensure users with ID 1 and 2 exist.\n";
    exit(1);
}

sendTestMessage($messageService, $user1, $user2, 'Test ping from user 1');
sleep(1);
sendTestMessage($messageService, $user2, $user1, 'Reply from user 2');
