<?php

declare(strict_types=1);

namespace App\Service;

use HttpRuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class HttpCbrService
{
    private HttpClient $httpClient;
    private Filesystem $filesystem;

    public function __construct(HttpClient $httpClient, Filesystem $filesystem)
    {
        $this->httpClient = $httpClient;
        $this->filesystem = $filesystem;
    }

    /**
     * Конвертирует и возвращает массив значений курсов валют.
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function getCurrentExchangeRate(): array
    {
        $xml = $this->getXmlRates();

        $json = json_encode(
            simplexml_load_string($xml),
            JSON_UNESCAPED_UNICODE
        );

        return json_decode($json, true);
    }

    /**
     * Возвращает курс USD.
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws HttpRuntimeException
     * @throws ClientExceptionInterface
     */
    public function getUSDRate(): float
    {
        $rates = $this->getCurrentExchangeRate();

        foreach ($rates['Valute'] as $item) {
            if ($item['CharCode'] === 'USD') {
                return (float)str_replace(',', '.', $item['Value']);
            }
        }

        throw new HttpRuntimeException;
    }

    /**
     * Возвращает xml с сайта cbr.
     *
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getXmlRates(): string
    {
        $withCache = (bool)env('USE_FILE_CACHE');
        $xmlFilePath = __DIR__ . '/../../data/tmp/' . date(env('CACHE_DIR_TMP')) . '/rates.xml';

        if ($withCache && file_exists($xmlFilePath)) {
            return file_get_contents($xmlFilePath);
        }

        $xml = $this->httpClient::create()->request('GET', env('CBR_API_URI'))->getContent();
        $withCache ? $this->filesystem->dumpFile($xmlFilePath, $xml) : false;

        return $xml;
    }
}