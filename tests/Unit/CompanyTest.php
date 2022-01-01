<?php

declare(strict_types=1);

namespace Unit;

use App\Entity\Company;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    private static Company $company;
    private static array $companySample;
    private static array $companyOutputArrayUSD;
    private static array $companyOutputArrayRUB;
    private static string $companyJson;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$company = new Company();
        self::$companySample = include_once(__DIR__ . '/../samples/company.php');
        self::$companyOutputArrayUSD = include_once(__DIR__ . '/../samples/company-output-usd-price.php');
        self::$companyOutputArrayRUB = include_once(__DIR__ . '/../samples/company-output-rub-price.php');
        self::$companyJson = file_get_contents(__DIR__ . '/../samples/company.json');
    }

    public function test_companyInstance()
    {
        self::assertInstanceOf(Company::class, self::$company);
    }

    public function test_setNameGetName()
    {
        self::$company->setName(self::$companySample['name']);

        self::assertEquals(self::$companySample['name'], self::$company->getName());
    }

    public function test_setLabelGetLabel()
    {
        self::$company->setLabel(self::$companySample['label']);

        self::assertEquals(self::$companySample['label'], self::$company->getLabel());
    }

    public function test_setCodeGetCode()
    {
        self::$company->setCode(self::$companySample['code']);

        self::assertEquals(self::$companySample['code'], self::$company->getCode());
    }

    public function test_setPriceGetPrice()
    {
        self::$company->setPrice(self::$companySample['price']);

        self::assertSame((float)self::$companySample['price'], self::$company->getPrice());
    }

    public function test_setUriGetUri()
    {
        self::$company->setUri(self::$companySample['uri']);

        self::assertEquals(self::$companySample['uri'], self::$company->getUri());
    }

    public function test_setLatestPriceGetLatestPrice()
    {
        self::$company->setLatestPrice(self::$companySample['latestPrice']);

        self::assertSame((float)self::$companySample['latestPrice'], self::$company->getLatestPrice());
    }

    public function test_setPreviousCloseGetPreviousClose()
    {
        self::$company->setPreviousClose(self::$companySample['previousClose']);

        self::assertSame((float)self::$companySample['previousClose'], self::$company->getPreviousClose());
    }

    public function test_setLowGetLow()
    {
        self::$company->setLow(self::$companySample['low']);

        self::assertSame((float)self::$companySample['low'], self::$company->getLow());
    }

    public function test_setHighGetHigh()
    {
        self::$company->setHigh(self::$companySample['high']);

        self::assertSame((float)self::$companySample['high'], self::$company->getHigh());
    }

    public function test_setPlusMinusGetPlusMinus()
    {
        self::$company->setPlusMinus(self::$companySample['plusMinus']);

        self::assertSame((float)self::$companySample['plusMinus'], self::$company->getPlusMinus());
    }

    public function test_setPercentageGetPercentage()
    {
        self::$company->setPercentage(self::$companySample['percentage']);

        self::assertSame((float)self::$companySample['percentage'], self::$company->getPercentage());
    }

    public function test_setTimeGetTime()
    {
        self::$company->setTime(self::$companySample['time']);

        self::assertEquals(self::$companySample['time'], self::$company->getTime());
    }

    public function test_setDateGetDate()
    {
        self::$company->setDate(self::$companySample['date']);

        self::assertEquals(self::$companySample['date'], self::$company->getDate());
    }

    public function test_setM3plusMinusGetM3plusMinus()
    {
        self::$company->setM3plusMinus(self::$companySample['m3plusMinus']);

        self::assertSame((float)self::$companySample['m3plusMinus'], self::$company->getM3plusMinus());
    }

    public function test_setM3percentageGetM3percentage()
    {
        self::$company->setM3percentage(self::$companySample['m3percentage']);

        self::assertSame((float)self::$companySample['m3percentage'], self::$company->getM3percentage());
    }

    public function test_setM6plusMinusGetM6plusMinus()
    {
        self::$company->setM6plusMinus(self::$companySample['m6plusMinus']);

        self::assertSame((float)self::$companySample['m6plusMinus'], self::$company->getM6plusMinus());
    }

    public function test_setM6percentageGetM6percentage()
    {
        self::$company->setM6percentage(self::$companySample['m6percentage']);

        self::assertSame((float)self::$companySample['m6percentage'], self::$company->getM6percentage());
    }

    public function test_setM12plusMinusGetM12plusMinus()
    {
        self::$company->setM12plusMinus(self::$companySample['m12plusMinus']);

        self::assertSame((float)self::$companySample['m12plusMinus'], self::$company->getM12plusMinus());
    }

    public function test_setM12percentageGetM12percentage()
    {
        self::$company->setM12percentage(self::$companySample['m12percentage']);

        self::assertSame((float)self::$companySample['m12percentage'], self::$company->getM12percentage());
    }

    public function test_setPeGetPe()
    {
        self::$company->setPe(self::$companySample['pe']);

        self::assertSame((float)self::$companySample['pe'], self::$company->getPe());
    }

    public function test_setWeeks52LowGetWeeks52Low()
    {
        self::$company->setWeeks52Low(self::$companySample['weeks52Low']);

        self::assertSame((float)self::$companySample['weeks52Low'], self::$company->getWeeks52Low());
    }

    public function test_setWeeks52HighGetWeeks52High()
    {
        self::$company->setWeeks52High(self::$companySample['weeks52High']);

        self::assertSame((float)self::$companySample['weeks52High'], self::$company->getWeeks52High());
    }

    public function test_calculateProfit()
    {
        self::assertSame(self::$companyOutputArrayUSD['potential profit'], self::$company->calculateProfit());
    }

    public function test_jsonSerialize()
    {
        self::assertEquals(self::$companyJson, json_encode(self::$company));
    }

    public function test_getOutputArrayRub()
    {
        self::assertEquals(self::$companyOutputArrayRUB, self::$company->getOutputArray(74.2926));
    }

    public function test_getOutputArrayUsd()
    {
        self::assertEquals(self::$companyOutputArrayUSD, self::$company->getOutputArray());
    }
}