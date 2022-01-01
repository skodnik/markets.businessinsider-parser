<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class HttpCompanyService
{
    private HttpClient $httpClient;
    private ParserCompanyService $parserService;
    private Filesystem $filesystem;

    public function __construct(HttpClient $httpClient, ParserCompanyService $parserService, Filesystem $filesystem)
    {
        $this->httpClient = $httpClient;
        $this->parserService = $parserService;
        $this->filesystem = $filesystem;
    }

    /**
     * Возвращает массив компаний полученный по алфавитному списку.
     *
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getCompaniesAlphabetically(): array
    {
        $companies = $this->parserService->getCompaniesFromTable(
            $this->getHtmlFromPageLetter('0-9')
        );

        foreach (range('A', 'Z') as $letter) {
            $companyIteration = $this->parserService->getCompaniesFromTable(
                $this->getHtmlFromPageLetter($letter)
            );

            $companies = array_merge($companies, $companyIteration);
        }

        return $companies;
    }

    /**
     * Возвращает массив компаний полученный итеративно.
     *
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getCompaniesIteratively(): array
    {
        $htmlStartPage = $this->getHtmlFromPageId(1);
        $companies = $this->parserService->getCompaniesFromTable($htmlStartPage);

        $lastPageId = $this->parserService->getPagesCount($htmlStartPage);

        for ($i = 2; $i <= $lastPageId; $i++) {
            $companies = array_merge(
                $companies,
                $this->parserService->getCompaniesFromTable(
                    $this->getHtmlFromPageId($i)
                )
            );
        }

        return $companies;
    }

    /**
     * Возвращает html содержание страницы списка компаний по букве.
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function getHtmlFromPageLetter(string $letter): bool|string
    {
        $uri = env('MARKET_BASE_URI') . env('SANDP_PATH') . '/' . $letter;
        $tmpHtmlFile = __DIR__ . '/../../data/tmp/' . date(env('CACHE_DIR_TMP')) . '/letters/sp_500_l-' . $letter . '.html';

        return $this->getHtml(
            $tmpHtmlFile,
            $uri,
            (bool)env('USE_FILE_CACHE')
        );
    }

    /**
     * Возвращает html содержание страницы списка компаний по индексу.
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function getHtmlFromPageId(int $pageId): bool|string
    {
        $uri = env('MARKET_BASE_URI') . env('SANDP_PATH') . '?p=' . $pageId;
        $htmlFilePath = __DIR__ . '/../../data/tmp/' . date(env('CACHE_DIR_TMP')) . '/indexes/sp_500_p' . $pageId . '.html';

        return $this->getHtml(
            $htmlFilePath,
            $uri,
            (bool)env('USE_FILE_CACHE')
        );
    }

    /**
     * Возвращает html содержимое страницы описания компании.
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getHtmlFromCompanyPage(string $companyPageUri): bool|string
    {
        $uri = env('MARKET_BASE_URI') . $companyPageUri;
        $name = preg_replace('/[^A-z,0-9\-.]/', '', str_replace('stocks', '', $companyPageUri));
        $htmlFilePath = __DIR__ . '/../../data/tmp/' . date(env('CACHE_DIR_TMP')) . '/companies/company_' . $name . '.html';

        return $this->getHtml(
            $htmlFilePath,
            $uri,
            (bool)env('USE_FILE_CACHE')
        );
    }

    /**
     * Возвращает содержимое запрашиваемой компании с учетом кеша.
     * Кеширует содержимое запрашиваемых страниц.
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function getHtml(string $htmlFilePath, string $uri, bool $withCache = true): bool|string
    {
        if ($withCache && file_exists($htmlFilePath)) {
            return file_get_contents($htmlFilePath);
        }

        $html = $this->httpClient::create()->request('GET', $uri)->getContent();
        $withCache ? $this->filesystem->dumpFile($htmlFilePath, $html) : false;

        return $html;
    }
}