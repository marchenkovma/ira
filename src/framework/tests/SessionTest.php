<?php

namespace Aruka\Tests;

use Aruka\Sessions\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{

    protected function setUp(): void
    {
        unset($_SESSION);
    }

    public function test_setAndGetFlash()
    {
        $session = new Session();
        $session->setFlash('success', 'Успешно');
        $session->setFlash('error', 'Ошибка');
        $this->assertTrue($session->hasFlash('success'));
        $this->assertTrue($session->hasFlash('error'));
        $this->assertEquals(['Успешно'], $session->getFlash('success'));
        $this->assertEquals(['Ошибка'], $session->getFlash('error'));
        $this->assertEquals([], $session->getFlash('warning'));
    }
}
