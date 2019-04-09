<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads;

class ThreadFactory
{
    public static $classMap = [
        ChatThread::TYPE => ChatThread::class,
        CustomerThread::TYPE => CustomerThread::class,
        PhoneThread::TYPE => PhoneThread::class,
        ReplyThread::TYPE => ReplyThread::class,
        NoteThread::TYPE => NoteThread::class,
        '_default' => Thread::class,
    ];

    public function make(string $type, array $data): Thread
    {
        /** @var Thread $thread */
        $thread = array_key_exists($type, self::$classMap)
            ? new self::$classMap[$type]()
            : new self::$classMap['_default']();

        $thread->hydrate($data);

        return $thread;
    }
}
