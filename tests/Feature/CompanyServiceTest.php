<?php

namespace Feature;

use App\Entity\Company;
use App\Service\CompanyService;
use PHPUnit\Framework\TestCase;

class CompanyServiceTest extends TestCase
{
    private static CompanyService $service;
    private static array $companies = [];
    private static string $companiesJson;
    private static string $topByPrice;
    private static string $topByProfit;
    private static string $topByGrowth;
    private static string $topByPe;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$service = new CompanyService();

        self::$companiesJson = file_get_contents(__DIR__ . '/../samples/original/s&p_500.json');
        self::$topByPrice = file_get_contents(__DIR__ . '/../samples/original/top10_by_price.json');
        self::$topByProfit = file_get_contents(__DIR__ . '/../samples/original/top10_by_profit.json');
        self::$topByGrowth= file_get_contents(__DIR__ . '/../samples/original/top10_by_growth.json');
        self::$topByPe = file_get_contents(__DIR__ . '/../samples/original/top10_by_pe.json');

        $companies = json_decode(self::$companiesJson, true);

        foreach ($companies as $company) {
            self::$companies[] = (new Company())
                ->setName($company['name'])
                ->setLabel($company['label'])
                ->setCode($company['code'])
                ->setUri($company['uri'])
                ->setPrice($company['price'])
                ->setLatestPrice($company['latestPrice'])
                ->setPreviousClose($company['previousClose'])
                ->setTime($company['time'])
                ->setDate($company['date'])
                ->setLow($company['low'])
                ->setHigh($company['high'])
                ->setPlusMinus($company['plusMinus'])
                ->setPercentage($company['percentage'])
                ->setM3plusMinus($company['m3plusMinus'])
                ->setM3percentage($company['m3percentage'])
                ->setM6plusMinus($company['m6plusMinus'])
                ->setM6percentage($company['m6percentage'])
                ->setM12plusMinus($company['m12plusMinus'])
                ->setM12percentage($company['m12percentage'])
                ->setPe($company['pe'])
                ->setWeeks52Low($company['weeks52Low'])
                ->setWeeks52High($company['weeks52High']);
        }
    }

    public function test_sortCompaniesByPrice()
    {
        $expected = self::$topByPrice;
        $intermediate = array_slice(
            self::$service->sortCompaniesByPrice(self::$companies),
            0,
            10

        );

        $actual = json_encode(self::$service->getReport($intermediate, 10, 74.2926));

        self::assertEquals($expected, $actual);
    }

    public function test_sortCompaniesByProfit()
    {
        $expected = self::$topByProfit;
        $intermediate = array_slice(
            self::$service->sortCompaniesByProfit(self::$companies),
            0,
            10

        );

        $actual = json_encode(self::$service->getReport($intermediate, 10));

        self::assertEquals($expected, $actual);
    }

    public function test_sortCompaniesByM12Percentage()
    {
        $expected = self::$topByGrowth;
        $intermediate = array_slice(
            self::$service->sortCompaniesByM12Percentage(self::$companies),
            0,
            10

        );

        $actual = json_encode(self::$service->getReport($intermediate, 10));

        self::assertEquals($expected, $actual);
    }

    public function test_sortCompaniesByPe()
    {
        $expected = self::$topByPe;
        $intermediate = array_slice(
            self::$service->sortCompaniesByPe(self::$companies),
            0,
            10

        );

        $actual = json_encode(self::$service->getReport($intermediate, 10));

        self::assertEquals($expected, $actual);
    }
}
