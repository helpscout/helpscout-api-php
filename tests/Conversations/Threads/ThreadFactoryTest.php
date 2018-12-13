<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\ChatThread;
use HelpScout\Api\Conversations\Threads\CustomerThread;
use HelpScout\Api\Conversations\Threads\NoteThread;
use HelpScout\Api\Conversations\Threads\PhoneThread;
use HelpScout\Api\Conversations\Threads\ReplyThread;
use HelpScout\Api\Conversations\Threads\Thread;
use HelpScout\Api\Conversations\Threads\ThreadFactory;
use PHPUnit\Framework\TestCase;

class ThreadFactoryTest extends TestCase
{
    /** @var ThreadFactory */
    private $factory;

    public function setUp()
    {
        parent::setUp();

        $this->factory = new ThreadFactory();
    }

    public function testMakesAndHydratesChatThreads()
    {
        $id = rand(1, 5000);
        $data = [
            'id' => $id,
        ];
        $thread = $this->factory->make(ChatThread::TYPE, $data);

        $this->assertInstanceOf(ChatThread::class, $thread);
        $this->assertSame($id, $thread->getId());
    }

    public function testMakesAndHydratesCustomerThreads()
    {
        $id = rand(1, 5000);
        $data = [
            'id' => $id,
        ];
        $thread = $this->factory->make(CustomerThread::TYPE, $data);

        $this->assertInstanceOf(CustomerThread::class, $thread);
        $this->assertSame($id, $thread->getId());
    }

    public function testMakesAndHydratesPhoneThreads()
    {
        $id = rand(1, 5000);
        $data = [
            'id' => $id,
        ];
        $thread = $this->factory->make(PhoneThread::TYPE, $data);

        $this->assertInstanceOf(PhoneThread::class, $thread);
        $this->assertSame($id, $thread->getId());
    }

    public function testMakesAndHydratesReplyThreads()
    {
        $id = rand(1, 5000);
        $data = [
            'id' => $id,
        ];
        $thread = $this->factory->make(ReplyThread::TYPE, $data);

        $this->assertInstanceOf(ReplyThread::class, $thread);
        $this->assertSame($id, $thread->getId());
    }

    public function testMakesAndHydratesNoteThreads()
    {
        $id = rand(1, 5000);
        $data = [
            'id' => $id,
        ];
        $thread = $this->factory->make(NoteThread::TYPE, $data);

        $this->assertInstanceOf(NoteThread::class, $thread);
        $this->assertSame($id, $thread->getId());
    }

    public function testMakesAndHydratesDefaultThread()
    {
        $id = rand(1, 5000);
        $data = [
            'id' => $id,
        ];

        $thread = $this->factory->make(uniqid(), $data);

        $this->assertInstanceOf(Thread::class, $thread);
        $this->assertSame($id, $thread->getId());
    }
}
