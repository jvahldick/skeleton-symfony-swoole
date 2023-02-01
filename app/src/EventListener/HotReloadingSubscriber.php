<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Runtime\HotRealodingRunner;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

final class HotReloadingSubscriber implements EventSubscriberInterface
{
    private string $environment;

    public function __construct(KernelInterface $kernel)
    {
        $this->environment = $kernel->getEnvironment();
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::TERMINATE => 'onTerminate'
        ];
    }

    public function onTerminate(TerminateEvent $e): void
    {
        if ($this->environment === 'dev') {
            HotRealodingRunner::getServer()->reload();
        }
    }
}
