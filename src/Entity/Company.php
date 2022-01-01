<?php

declare(strict_types=1);

namespace App\Entity;

use JsonSerializable;

class Company implements JsonSerializable
{
    private string $name;
    private string $label;
    private string $code;
    private string $price;
    private string $uri;
    private string $latestPrice = '';
    private string $previousClose = '';
    private string $low = '';
    private string $high = '';
    private string $plusMinus = '';
    private string $percentage = '';
    private string $time;
    private string $date;
    private string $m3plusMinus = '';
    private string $m3percentage = '';
    private string $m6plusMinus = '';
    private string $m6percentage = '';
    private string $m12plusMinus = '';
    private string $m12percentage = '';
    private string $pe = '';
    private string $weeks52Low = '';
    private string $weeks52High = '';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Company
     */
    public function setName(string $name): Company
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Company
     */
    public function setLabel(string $label): Company
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Company
     */
    public function setCode(string $code): Company
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return (float)$this->price;
    }

    /**
     * @param string $price
     * @return Company
     */
    public function setPrice(string $price): Company
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return Company
     */
    public function setUri(string $uri): Company
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatestPrice(): float
    {
        return (float)$this->latestPrice;
    }

    /**
     * @param string $latestPrice
     * @return Company
     */
    public function setLatestPrice(string $latestPrice): Company
    {
        $this->latestPrice = $latestPrice;
        return $this;
    }

    /**
     * @return float
     */
    public function getPreviousClose(): float
    {
        return (float)$this->previousClose;
    }

    /**
     * @param string $previousClose
     * @return Company
     */
    public function setPreviousClose(string $previousClose): Company
    {
        $this->previousClose = $previousClose;
        return $this;
    }

    /**
     * @return float
     */
    public function getLow(): float
    {
        return (float)$this->low;
    }

    /**
     * @param string $low
     * @return Company
     */
    public function setLow(string $low): Company
    {
        $this->low = $low;
        return $this;
    }

    /**
     * @return float
     */
    public function getHigh(): float
    {
        return (float)$this->high;
    }

    /**
     * @param string $high
     * @return Company
     */
    public function setHigh(string $high): Company
    {
        $this->high = $high;
        return $this;
    }

    /**
     * @return float
     */
    public function getPlusMinus(): float
    {
        return (float)$this->plusMinus;
    }

    /**
     * @param string $plusMinus
     * @return Company
     */
    public function setPlusMinus(string $plusMinus): Company
    {
        $this->plusMinus = $plusMinus;
        return $this;
    }

    /**
     * @return float
     */
    public function getPercentage(): float
    {
        return (float)$this->percentage;
    }

    /**
     * @param string $percentage
     * @return Company
     */
    public function setPercentage(string $percentage): Company
    {
        $this->percentage = $percentage;
        return $this;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * @param string $time
     * @return Company
     */
    public function setTime(string $time): Company
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     * @return Company
     */
    public function setDate(string $date): Company
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return float
     */
    public function getM3plusMinus(): float
    {
        return (float)$this->m3plusMinus;
    }

    /**
     * @param string $m3plusMinus
     * @return Company
     */
    public function setM3plusMinus(string $m3plusMinus): Company
    {
        $this->m3plusMinus = $m3plusMinus;
        return $this;
    }

    /**
     * @return float
     */
    public function getM3percentage(): float
    {
        return (float)$this->m3percentage;
    }

    /**
     * @param string $m3percentage
     * @return Company
     */
    public function setM3percentage(string $m3percentage): Company
    {
        $this->m3percentage = $m3percentage;
        return $this;
    }

    /**
     * @return float
     */
    public function getM6plusMinus(): float
    {
        return (float)$this->m6plusMinus;
    }

    /**
     * @param string $m6plusMinus
     * @return Company
     */
    public function setM6plusMinus(string $m6plusMinus): Company
    {
        $this->m6plusMinus = $m6plusMinus;
        return $this;
    }

    /**
     * @return float
     */
    public function getM6percentage(): float
    {
        return (float)$this->m6percentage;
    }

    /**
     * @param string $m6percentage
     * @return Company
     */
    public function setM6percentage(string $m6percentage): Company
    {
        $this->m6percentage = $m6percentage;
        return $this;
    }

    /**
     * @return float
     */
    public function getM12plusMinus(): float
    {
        return (float)$this->m12plusMinus;
    }

    /**
     * @param string $m12plusMinus
     * @return Company
     */
    public function setM12plusMinus(string $m12plusMinus): Company
    {
        $this->m12plusMinus = $m12plusMinus;
        return $this;
    }

    /**
     * @return float
     */
    public function getM12percentage(): float
    {
        return (float)$this->m12percentage;
    }

    /**
     * @param string $m12percentage
     * @return Company
     */
    public function setM12percentage(string $m12percentage): Company
    {
        $this->m12percentage = $m12percentage;
        return $this;
    }

    /**
     * @return float
     */
    public function getPe(): float
    {
        return (float)$this->pe;
    }

    /**
     * @param string $pe
     * @return Company
     */
    public function setPe(string $pe): Company
    {
        $this->pe = $pe;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeeks52Low(): float
    {
        return (float)$this->weeks52Low;
    }

    /**
     * @param string $weeks52Low
     * @return Company
     */
    public function setWeeks52Low(string $weeks52Low): Company
    {
        $this->weeks52Low = $weeks52Low;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeeks52High(): float
    {
        return (float)$this->weeks52High;
    }

    /**
     * @param string $weeks52High
     * @return Company
     */
    public function setWeeks52High(string $weeks52High): Company
    {
        $this->weeks52High = $weeks52High;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'code' => $this->getCode(),
            'uri' => $this->getUri(),
            'price' => $this->getPrice(),
            'latestPrice' => $this->getLatestPrice(),
            'previousClose' => $this->getPreviousClose(),
            'time' => $this->getTime(),
            'date' => $this->getDate(),
            'low' => $this->getLow(),
            'high' => $this->getHigh(),
            'plusMinus' => $this->getPlusMinus(),
            'percentage' => $this->getPercentage(),
            'm3plusMinus' => $this->getM3plusMinus(),
            'm3percentage' => $this->getM3percentage(),
            'm6plusMinus' => $this->getM6plusMinus(),
            'm6percentage' => $this->getM6percentage(),
            'm12plusMinus' => $this->getM12plusMinus(),
            'm12percentage' => $this->getM12percentage(),
            'pe' => $this->getPe(),
            'weeks52Low' => $this->getWeeks52Low(),
            'weeks52High' => $this->getWeeks52High(),
        ];
    }

    /**
     * @param float $currencyRate
     * @return array
     */
    public function getOutputArray(float $currencyRate = 0)
    {
        $price = $this->getPrice();

        if($currencyRate) {
            $price = round($price * $currencyRate, 2);
        }

        return [
            'code' => $this->getCode(),
            'name' => $this->getLabel(),
            'price' => $price,
            'P/E' => $this->getPe(),
            'growth' => $this->getM12percentage(),
            'potential profit' => $this->calculateProfit(),
        ];
    }

    /**
     * @return float|int
     */
    public function calculateProfit()
    {
        if ($this->getWeeks52High() && $this->getWeeks52Low()) {
            return round((($this->getWeeks52High() - $this->getWeeks52Low()) / $this->getWeeks52Low()) * 100, 2);
        }

        return 0;
    }
}