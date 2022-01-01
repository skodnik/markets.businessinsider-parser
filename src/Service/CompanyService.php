<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Company;

class CompanyService
{
    /**
     * Возвращает массив компаний отсортированных по price.
     *
     * @param array $companies
     * @return array
     */
    public function sortCompaniesByPrice(array $companies): array
    {
        $result = $companies;

        usort($result, function ($a, $b) {
            /**
             * @var Company $a
             * @var Company $b
             */
            return $b->getPrice() <=> $a->getPrice();
        });

        return $result;
    }

    /**
     * Возвращает массив компаний отсортированных по P/E.
     *
     * @param array $companies
     * @return array
     */
    public function sortCompaniesByPe(array $companies): array
    {
        $result = $companies;

        usort($result, function ($a, $b) {
            /**
             * @var Company $a
             * @var Company $b
             */
            return $a->getPe() <=> $b->getPe();
        });

        return $result;
    }

    /**
     * Возвращает массив компаний отсортированных по M12Percentage.
     *
     * @param array $companies
     * @return array
     */
    public function sortCompaniesByM12Percentage(array $companies): array
    {
        $result = $companies;

        usort($result, function ($a, $b) {
            /**
             * @var Company $a
             * @var Company $b
             */
            return $b->getM12Percentage() <=> $a->getM12Percentage();
        });

        return $result;
    }

    /**
     * Возвращает массив компаний отсортированных по profit.
     *
     * @param array $companies
     * @return array
     */
    public function sortCompaniesByProfit(array $companies): array
    {
        $result = $companies;

        usort($result, function ($a, $b) {
            /**
             * @var Company $a
             * @var Company $b
             */
            return $b->calculateProfit() <=> $a->calculateProfit();
        });

        return $result;
    }

    /**
     * Возвращает массив компаний заданной длины и структуры.
     *
     * @param array $companies
     * @param int $resultArrayLength
     * @param float $usdRate
     * @return array
     */
    public function getReport(array $companies, int $resultArrayLength, float $usdRate = 0): array
    {
        $output = [];
        $companies = array_slice($companies, 0, $resultArrayLength);

        foreach ($companies as $company) {
            $output[] = $company->getOutputArray($usdRate);
        }

        return $output;
    }
}