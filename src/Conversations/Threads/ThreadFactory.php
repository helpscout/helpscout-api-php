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

    /**
     * Attempt to map the incoming Thread to a typed class.  The only threads we type
     * are threads that can be created through the API.  We do not type any kind of
     * system threads such as a notice that a Workflow has run on a Conversation.
     */
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
