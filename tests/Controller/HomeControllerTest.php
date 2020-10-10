<?php

namespace App\Tests\Controller;

use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use App\Controller\HomeController;

class HomeControllerTest extends TestCase
{
    public function testIndex()
    {

        $logger = $this->getMockBuilder('Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();
         
        $homeController = new HomeController($logger);
    }
}
