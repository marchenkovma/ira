<?php

namespace Aruka\Tests;

use Aruka\Container\Container;
use Aruka\Container\Exceptions\ContainerException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{

    public function test_getServiceFromContainer()
    {
        $container = new Container();

        $container->add('aruka-class', ArukaClass::class);

        $this->assertInstanceOf(ArukaClass::class, $container->get('aruka-class'));
    }

    public function test_containerHasContainerException()
    {
        $container = new Container();

        $this->expectException(ContainerException::class);

        $container->add('not-existing-class');
    }

}
