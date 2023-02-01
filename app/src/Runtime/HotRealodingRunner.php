<?php

declare(strict_types=1);

namespace App\Runtime;

use Runtime\Swoole\ServerFactory;
use Runtime\Swoole\SymfonyHttpBridge;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\Runtime\RunnerInterface;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

class HotRealodingRunner implements RunnerInterface
{
    /** @var ServerFactory */
    private $serverFactory;

    /** @var HttpKernelInterface */
    private $application;

    private static $server;

    public function __construct(ServerFactory $serverFactory, HttpKernelInterface $application)
    {
        $this->serverFactory = $serverFactory;
        $this->application = $application;
    }

    /**
     * @return mixed
     */
    public static function getServer()
    {
        return self::$server;
    }

    public function run(): int
    {
        $server = $this->serverFactory->createServer([$this, 'handle']);
        self::$server = $server;
        $server->start();

        return 0;
    }

    public function handle(Request $request, Response $response): void
    {
        $sfRequest = SymfonyHttpBridge::convertSwooleRequest($request);

        $sfResponse = $this->application->handle($sfRequest);
        SymfonyHttpBridge::reflectSymfonyResponse($sfResponse, $response);

        if ($this->application instanceof TerminableInterface) {
            $this->application->terminate($sfRequest, $sfResponse);
        }
    }
}
