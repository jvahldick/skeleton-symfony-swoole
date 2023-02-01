<?php

declare(strict_types=1);

namespace App\Runtime;

use Runtime\Swoole\CallableRunner;
use Runtime\Swoole\ServerFactory;
use Runtime\Swoole\SymfonyRunner;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Runtime\RunnerInterface;
use Symfony\Component\Runtime\SymfonyRuntime;

class SwooleRuntime extends SymfonyRuntime
{
    private ?ServerFactory $serverFactory;

    public function __construct(array $options, ?ServerFactory $serverFactory = null)
    {
        $this->serverFactory = $serverFactory ?? new ServerFactory($options);
        parent::__construct($this->serverFactory->getOptions());
    }

    public function getRunner(?object $application): RunnerInterface
    {
        if (is_callable($application)) {
            return new CallableRunner($this->serverFactory, $application);
        }

        if ($application instanceof HttpKernelInterface) {
            return new HotRealodingRunner($this->serverFactory, $application);
        }

        return parent::getRunner($application);
    }
}
