<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\NoteThread;
use HelpScout\Api\Conversations\Threads\Support\HasUser;
use HelpScout\Api\Users\User;
use PHPUnit\Framework\TestCase;

class NoteThreadTest extends TestCase
{
    public function testHasExpectedType()
    {
        $this->assertEquals('note', NoteThread::TYPE);
    }

    public function testExtractsType()
    {
        $thread = new NoteThread();
        $data = $thread->extract();
        $this->assertArrayHasKey('type', $data);
        $this->assertEquals(NoteThread::TYPE, $data['type']);
    }

    public function testHasExpectedResourceUrl()
    {
        $this->assertEquals('/v2/conversations/123/notes', NoteThread::resourceUrl(123));
    }

    public function testUsesExpectedTraits()
    {
        $classUses = class_uses(NoteThread::class);

        $this->assertTrue(in_array(HasUser::class, $classUses));
    }

    public function testCanSetUserId()
    {
        $userId = 4839;
        $thread = new NoteThread();
        $thread->setUserId($userId);

        $this->assertEquals($userId, $thread->getUserId());
    }

    public function testCanSetUser()
    {
        $userId = 4839;
        $user = new User();
        $user->setId($userId);

        $thread = new NoteThread();
        $thread->setUser($user);

        $this->assertEquals($userId, $thread->getUserId());
    }

    public function testCanExtractUser()
    {
        $thread = new NoteThread();
        $thread->setUserId(94320);

        $data = $thread->extract();

        $this->assertArrayHasKey('user', $data);
        $this->assertEquals(94320, $data['user']);
    }
}
