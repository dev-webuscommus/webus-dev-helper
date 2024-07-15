<?php

namespace Webus;

use DI\ContainerBuilder;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class WebusTestCase extends TestCase
{
    /**
     * framework 에서 DI 를 활용하고 있기에, DI 관련 테스트 코드 중복 제거
     */
    protected ContainerInterface $container;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $containerBuilder = new ContainerBuilder();
        $this->container = $containerBuilder->build();
    }
}