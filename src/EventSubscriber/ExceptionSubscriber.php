<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $dbLogger)
    {
        $this->logger = $dbLogger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['processException']
            ],
        ];
    }

    public function processException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            switch (true) {
            case ($exception->getStatusCode() > 400 && $exception->getStatusCode() < 500):
                $this->logger->error($exception->getMessage());
                break;

            case ($exception->getStatusCode() > 200 && $exception->getStatusCode() < 250):
                $this->logger->info($exception->getMessage());
                break;
        }
        } else {
            $this->logger->info($exception->getMessage());
        }
        
        
    }

}
