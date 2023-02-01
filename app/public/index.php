<?php

use App\Kernel;
use Runtime\Swoole\Runtime;
use Swoole\Constant;

if ($_ENV['SWOOLE_RUNTIME'] ?? false) {
//    $_SERVER['APP_RUNTIME'] = Runtime::class;
    $_SERVER['APP_RUNTIME'] = \App\Runtime\SwooleRuntime::class;

    $_SERVER['APP_RUNTIME_OPTIONS'] = [
        'host' => '0.0.0.0',
        'port' => 8000,
        // 'mode' => SWOOLE_BASE, // it can be commented out
        'settings' => [
            Constant::OPTION_WORKER_NUM => \OpenSwoole\Util::getCPUNum() * 2,
            Constant::OPTION_ENABLE_STATIC_HANDLER => true,
            Constant::OPTION_DOCUMENT_ROOT => dirname(__DIR__) . '/public'
        ],
    ];
}

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool)$context['APP_DEBUG']);
};
