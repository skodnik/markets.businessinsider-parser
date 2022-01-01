<?php

declare(strict_types=1);

namespace App;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class App
{
    private $container;

    /**
     * @throws Exception
     */
    public function __invoke(): ContainerBuilder
    {

        if (!$this->container) {
            $this->container = new ContainerBuilder();
            (new PhpFileLoader($this->container, new FileLocator(__DIR__)))->load('services.php');
        }

        return $this->container;
    }

    public static function env($key, $if_not_exist = false)
    {
        return $_ENV[$key] ?? $if_not_exist;
    }
}