<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use App\Entity\Log;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    /**
     *
     * @var Log
     */
    private $log;

    public function setUp(): void
    {
        $this->log = new Log();
    }

    public function testMessageIsString()
    {
        $this->log->setMessage("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.");
        $this->assertIsString($this->log->getMessage());
    }

    public function testContextIsArray()
    {
        $this->log->setContext(['test' => 'test']);
        $this->assertIsArray($this->log->getContext());
    }

    public function testLevelIsInt()
    {
        $this->log->setLevel(200);
        $this->assertIsInt($this->log->getLevel());
    }

    public function testLevelNameIsString()
    {
        $this->log->setLevelName('ERROR');
        $this->assertIsString($this->log->getLevelName());
    }

    public function testExtraIsArray()
    {
        $this->log->setExtra(['test' => 'test']);
        $this->assertIsArray($this->log->getExtra());
    }

    public function testCreatedAtIsValid()
    {
        $this->log->setCreatedAt(new \DateTime());
        $this->assertInstanceOf(\DateTime::class, $this->log->getCreatedAt());
    }

    public function testUserIsValid()
    {
        $this->log->setUser(new User());
        $this->assertInstanceOf(User::class, $this->log->getUser());
    }

}
