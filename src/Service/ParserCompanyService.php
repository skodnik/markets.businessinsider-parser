<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Company;
use DOMElement;
use Symfony\Component\DomCrawler\Crawler;

class ParserCompanyService
{
    private Crawler $crawler;

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Возвращает индекс последней страницы из пагинатора.
     * Необходим для получения перечня всех компаний.
     *
     * @param string $html
     * @return int
     */
    public function getPagesCount(string $html): int
    {
        $crawler = $this->getCrawler($html);

        $anchors = $crawler->filter('.finando_paging a')->extract(['_text']);

        return (int)array_reverse($anchors)[1];
    }

    /**
     * Возвращает массив компаний с заполненными метаданными взятыми со страницы списка компаний.
     *
     * @param string $html
     * @return array
     */
    public function getCompaniesFromTable(string $html): array
    {
        $crawler = $this->getCrawler($html);

        $tableRows = $crawler->filter('.table__tbody tr');

        $companies = [];
        foreach ($tableRows as $tableRow) {
            $companies[] = $this->prepareCompany($tableRow);
        }

        return $companies;
    }

    /**
     * @param $tableRow
     * @return Company
     */
    private function prepareCompany($tableRow): Company
    {
        $crawler = $this->getCrawler($tableRow);

        $firstCellAnchor = $crawler->filter('td a')->first();

        $company = new Company();
        $company->setUri($firstCellAnchor->attr('href'));

        $allCells = $crawler->filter('td');

        return $this->parseCells($allCells, $company);
    }

    /**
     * @param $allCells
     * @param Company $company
     * @return Company
     */
    private function parseCells($allCells, Company $company): Company
    {
        foreach ($allCells as $key => $cell) {
            $data = explode(PHP_EOL, $cell->textContent);

            match ($key) {
                0 => $company->setName($data[1]),
                1 => $company
                    ->setLatestPrice(self::cleanupStringWithFloat($data[1]))
                    ->setPreviousClose(self::cleanupStringWithFloat($data[2])),
                2 => $company
                    ->setLow(self::cleanupStringWithFloat($data[1]))
                    ->setHigh(self::cleanupStringWithFloat($data[2])),
                3 => $company
                    ->setplusMinus(self::cleanupStringWithFloat($data[1]))
                    ->setPercentage(self::cleanupStringWithFloat($data[2])),
                4 => $company
                    ->setTime($data[1])
                    ->setDate($data[2]),
                5 => $company
                    ->setM3plusMinus(self::cleanupStringWithFloat($data[1]))
                    ->setM3percentage(self::cleanupStringWithFloat($data[2])),
                6 => $company
                    ->setM6plusMinus(self::cleanupStringWithFloat($data[1]))
                    ->setM6percentage(self::cleanupStringWithFloat($data[2])),
                7 => $company
                    ->setM12plusMinus(self::cleanupStringWithFloat($data[1]))
                    ->setM12percentage(self::cleanupStringWithFloat($data[2]))
            };
        }

        return $company;
    }

    /**
     * @param Company $company
     * @param string $html
     * @return Company
     */
    public function fillCompanyFromCompanyPageHtml(Company $company, string $html): Company
    {
        $crawler = $this->getCrawler($html);

        $company
            ->setLabel(
                self::extractFirstTextRow($crawler, 'span.price-section__label')
            )
            ->setCode(
                self::getCode(self::extractFirstTextRow($crawler, 'span.price-section__category span'))
            )
            ->setPrice(
                self::cleanupStringWithFloat(self::extractFirstTextRow($crawler, 'span.price-section__current-value'))
            );

        $infoBlocks = $crawler->filter('div.snapshot__data-item');

        foreach ($infoBlocks as $block) {
            $content = explode(PHP_EOL, $block->textContent);

            if (strpos($content[2], 'P/E Ratio')) {
                $company->setPe(self::cleanupStringWithFloat($content[1]));
            } elseif (strpos($content[2], '52 Week Low')) {
                $company->setWeeks52Low(self::cleanupStringWithFloat($content[1]));
            } elseif (strpos($content[2], '52 Week High')) {
                $company->setWeeks52High(self::cleanupStringWithFloat($content[1]));
            }
        }

        return $company;
    }

    /**
     * Возвращает текстовое содержимое элемента по указанному селектору.
     *
     * @param $crawler
     * @param $cssSelector
     * @return string
     */
    private static function extractFirstTextRow($crawler, $cssSelector): string
    {
        return trim(
            $crawler
                ->filter($cssSelector)
                ->first()
                ->extract(['_text'])[0]
        );
    }

    /**
     * Возвращает очищенное значение кода компании.
     *
     * @param string $string
     * @return string
     */
    private static function getCode(string $string): string
    {
        return str_replace(
            ', ',
            '',
            $string
        );
    }

    /**
     * Очищает строку содержащую float значение.
     *
     * @param string $string
     * @return string
     */
    private static function cleanupStringWithFloat(string $string): string
    {
        return trim(
            preg_replace(
                '/[^0-9\-.]/',
                '',
                $string
            )
        );
    }

    /**
     * Возвращает чистый или предзаполненный экземпляр Crawler.
     *
     * @param string|DOMElement $html
     * @return Crawler
     */
    public function getCrawler(string|DOMElement $html = ''): Crawler
    {
        $crawler = $this->crawler;
        $crawler->clear();

        $html ? $crawler->add($html) : false;

        return $crawler;
    }
}