<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testCanSetAndGetTitle(): void
    {
        $task = new Task();
        $task->setTitle('Ma tâche test');
        $this->assertSame('Ma tâche test', $task->getTitle());
    }

    public function testCanSetAndGetContent(): void
    {
        $task = new Task();
        $task->setContent('Contenu test');
        $this->assertSame('Contenu test', $task->getContent());
    }

    public function testDefaultIsDoneIsFalse(): void
    {
        $task = new Task();
        $this->assertFalse($task->isDone());
    }

    public function testCanMarkTaskAsDone(): void
    {
        $task = new Task();
        $task->setIsDone(true);
        $this->assertTrue($task->isDone());
    }

    public function testTitleCanBeEmptyString(): void
    {
        $task = new Task();
        $task->setTitle('');
        $this->assertSame('', $task->getTitle());
    }
}
