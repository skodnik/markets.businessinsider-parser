<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Command\Go;
use App\Service\CompanyService;
use App\Service\HttpCbrService;
use App\Service\HttpCompanyService;
use App\Service\ParserCompanyService;
use Symfony\Component\Console\Application;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->set('cli_app', Application::class)
        ->call('add', [service('go')])
        ->call('run', []);

    $services->set('http_client', HttpClient::class);

    $services->set('crawler', Crawler::class);

    $services->set('go', Go::class)
        ->args(
            [
                service('parser_service'),
                service('http_company_service'),
                service('http_cbr_service'),
                service('company_service'),
                service('filesystem'),
            ]
        );

    $services->set('http_company_service', HttpCompanyService::class)
        ->args(
            [
                service('http_client'),
                service('parser_service'),
                service('filesystem'),
            ]
        );

    $services->set('http_cbr_service', HttpCbrService::class)
        ->args(
            [
                service('http_client'),
                service('filesystem'),
            ]
        );

    $services->set('company_service', CompanyService::class)
        ->args(
            [
                service('http_client'),
                service('filesystem'),
            ]
        );

    $services->set('parser_service', ParserCompanyService::class)
        ->args(
            [
                service('crawler')
            ]
        );

    $services->set('filesystem', Filesystem::class);
};