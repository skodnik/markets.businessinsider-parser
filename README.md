# Парсер markets.businessinsider.com
Вариант исполнения задачи на php. Основан на компонентах [Symfony](https://symfony.com/).

## Запуск

Подготовка к работе:
```bash
git clone git@github.com:skodnik/markets.businessinsider-parser.git biparser
cd biparser
cp .env.example .env
composer install
sudo chmod +x cli
```

Запуск с получением списка компаний по алфавиту:
```bash
./cli app:go
```

Запуск с получением списка компаний из пагинатора:
```bash
./cli app:go --iteratively
```

Пример вывода результата:
```bash
Run application
===============

Step 1. Prepare companies alphabetically
----------------------------------------

 505 companies found

Step 2. Filling companies data from company pages
-------------------------------------------------

 505/505 [============================] 100%

Step 3. Save the result as a json file
--------------------------------------

 Successfully stored in: ../../data/result/2022-01-01/s&p_500.json

Step 4. Getting usd rate
------------------------

 USD rate: 1 USD - 74.2926 RUB

Step 5. Sorting companies
-------------------------

 Top10 ordered by price in RUB:
 1. NVR Inc.: 438638.37
 2. Amazon: 247716.79
 3. Alphabet A (ex Google): 215228.63
 4. Alphabet C (ex Google): 214972.32
 5. Booking Holdings Inc Registered Shs: 178245.03
 6. AutoZone Inc.: 155726.2
 7. Chipotle Mexican Grill Inc.: 129931.81
 8. Mettler-Toledo International Inc.: 125989.85
 9. Tesla: 78510.93
 10. BlackRock Inc.: 68009.67

 Top10 ordered by P/E ASC:
 1. Vornado Realty Trust: -893.45
 2. Chevron Corp.: -427.45
 3. Devon Energy Corp.: -167.13
 4. ExxonMobil Corp. (Exxon Mobil): -125.36
 5. Phillips 66: -76.53
 6. Under Armour Inc.: -64.5
 7. Incyte Corp.: -63.09
 8. Twitter: -62.1
 9. Moderna Inc: -59.95
 10. Under Armour Inc When Issued: -55.67

 Top10 ordered by growth:
 1. Devon Energy Corp.: 173.75
 2. Fortinet Inc: 147.76
 3. Bath & Body Works: 144.13
 4. Marathon Oil Corp.: 143.5
 5. Ford Motor Co.: 131.91
 6. NVIDIA Corp.: 128.22
 7. Moderna Inc: 123.05
 8. Diamondback Energy Inc: 120.64
 9. Nucor Corp.: 116.98
 10. Gartner Inc.: 111.98

 Top10 ordered by profit:
 1. Merck Co.: 496.61
 2. Moderna Inc: 384.29
 3. Discovery Communications Inc  (A): 260.76
 4. ViacomCBS: 260.45
 5. Penn National Gaming Inc.: 227.26
 6. Discovery Communications Inc  (C): 219.6
 7. NVIDIA Corp.: 199.44
 8. Devon Energy Corp.: 191.12
 9. Fortinet Inc: 170.73
 10. Nucor Corp.: 168.69

 [OK] 505 companies processed.
```

[![asciicast](https://asciinema.org/a/UJGxBZ1aHm55Xg8IxxJsCnCfB.svg)](https://asciinema.org/a/UJGxBZ1aHm55Xg8IxxJsCnCfB)

Результат работы в форме требуемых `json` файлов будет сохранен в директории `./data/result/`.

## Тесты
```bash
composer tests
```

## TODO
1. Добавить обработку исключений
2. Увеличить покрытие кода тестами

## Текст задания
Ваша задача спарсить информацию о компаниях, находящихся в индексе S&P 500 с данного сайта: <br>
https://markets.businessinsider.com/index/components/s&p_500

Для каждой компании собрать следующую информацию:
* Текущая стоимость в рублях (конвертацию производить по текущему курсу, взятому с сайта [центробанка РФ](http://www.cbr.ru/development/sxml/))
* Код компании (справа от названия компании на странице компании)
* P/E компании (информация находится справа от графика на странице компании)
* Годовой рост/падение компании в процентах (основная таблица)
* Высчитать какую прибыль принесли бы акции компании (в процентах), если бы они были куплены на уровне 52 Week Low и проданы на уровне 52 Week High (справа от графика на странице компании)

Сохранить итоговую информацию в 4 JSON файла:
1. Топ 10 компаний с самими дорогими акциями в рублях.
2. Топ 10 компаний с самым низким показателем P/E.
3. Топ 10 компаний, которые показали самый высокий рост за последний год
4. Топ 10 комппаний, которые принесли бы наибольшую прибыль, если бы были куплены на самом минимуме и проданы на самом максимуме за последний год.
<br>Пример формата:
```
[
{
    "code": "MMM",
    "name": "3M CO.",
    "price" | "P/E" | "growth" | "potential profit" : value,
},
...
]
```
