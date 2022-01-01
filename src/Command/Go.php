<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\CompanyService;
use App\Service\HttpCbrService;
use App\Service\HttpCompanyService;
use App\Service\ParserCompanyService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class Go extends Command
{
    protected static $defaultName = 'app:go';

    private ParserCompanyService $parserService;
    private HttpCompanyService $httpCompanyService;
    private HttpCbrService $httpCbrService;
    private Filesystem $filesystem;
    private CompanyService $companyService;
    private string $filesDir;
    private int $resultArrayLength;

    public function __construct(
        ParserCompanyService $parserService,
        HttpCompanyService $httpCompanyService,
        HttpCbrService $httpCbrService,
        CompanyService $companyService,
        Filesystem $filesystem,
        string $name = null
    ) {
        parent::__construct($name);

        $this->parserService = $parserService;
        $this->httpCompanyService = $httpCompanyService;
        $this->httpCbrService = $httpCbrService;
        $this->companyService = $companyService;
        $this->filesystem = $filesystem;

        $this->filesDir = __DIR__ . '/../../data/result/' . date(env('CACHE_DIR_TMP'));
        $this->resultArrayLength = (int)env('OUTPUT_RESULT_LENGTH');
    }

    protected function configure()
    {
        $this
            ->setDescription('Run application')
            ->setHelp(
                ''
            );

        $this
            ->addOption(
                'iteratively',
                'i',
                InputOption::VALUE_NONE,
                'Get companies iteratively'
            );
    }

    /**
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title($this->getDescription());

        if ($input->getOption('iteratively')) {
            $io->section('Step 1. Prepare companies iteratively');
            $companies = $this->httpCompanyService->getCompaniesIteratively();
        } else {
            $io->section('Step 1. Prepare companies alphabetically');
            $companies = $this->httpCompanyService->getCompaniesAlphabetically();
        }
        $io->text(count($companies) . ' companies found');

        $io->section('Step 2. Filling companies data from company pages');
        $progressBar = new ProgressBar($output, count($companies));
        $progressBar->start();

        foreach ($companies as $company) {
            $this->parserService->fillCompanyFromCompanyPageHtml(
                $company,
                $this->httpCompanyService->getHtmlFromCompanyPage($company->getUri())
            );

            $progressBar->advance();
        }

        $progressBar->finish();

        $io->newLine(2);
        $io->section('Step 3. Save the result as a json file');
        $filePath = $this->filesDir . '/s&p_500.json';
        $this->filesystem->dumpFile(
            $filePath,
            json_encode($companies, JSON_PRETTY_PRINT)
        );
        $io->text('Successfully stored in: ' . $filePath);

        $io->section('Step 4. Getting usd rate');
        $usdRate = $this->httpCbrService->getUSDRate();
        $io->text('USD rate: 1 USD - ' . $usdRate . ' RUB');

        $io->section('Step 5. Sorting companies');

        // Sorting by price
        $byPrice = $this->companyService->getReport(
            $this->companyService->sortCompaniesByPrice($companies),
            $this->resultArrayLength,
            $usdRate
        );
        $this->storeFile('_by_price.json', $byPrice);

        $io->text('Top' . $this->resultArrayLength . ' ordered by price in RUB:');

        foreach ($byPrice as $index => $output) {
            $io->text($index + 1 . '. ' . $output['name'] . ': ' . $output['price']);
        }

        // Sorting by P/E
        $byPe = $this->companyService->getReport(
            $this->companyService->sortCompaniesByPe($companies),
            $this->resultArrayLength
        );
        $this->storeFile('_by_pe.json', $byPe);

        $io->newLine();
        $io->text('Top' . $this->resultArrayLength . ' ordered by P/E ASC:');

        foreach ($byPe as $index => $output) {
            $io->text($index + 1 . '. ' . $output['name'] . ': ' . $output['P/E']);
        }

        // Sorting by growth
        $byGrowth = $this->companyService->getReport(
            $this->companyService->sortCompaniesByM12Percentage($companies),
            $this->resultArrayLength
        );
        $this->storeFile('_by_growth.json', $byGrowth);

        $io->newLine();
        $io->text('Top' . $this->resultArrayLength . ' ordered by growth:');

        foreach ($byGrowth as $index => $output) {
            $io->text($index + 1 . '. ' . $output['name'] . ': ' . $output['growth']);
        }

        // Sorting by profit
        $byProfit = $this->companyService->getReport(
            $this->companyService->sortCompaniesByProfit($companies),
            $this->resultArrayLength
        );
        $this->storeFile('_by_profit.json', $byProfit);

        $io->newLine();
        $io->text('Top' . $this->resultArrayLength . ' ordered by profit:');
        foreach ($byProfit as $index => $output) {
            $io->text($index + 1 . '. ' . $output['name'] . ': ' . $output['potential profit']);
        }

        // Final accord
        $io->newLine();
        $io->success(count($companies) . ' companies processed.');

        return Command::SUCCESS;
    }

    private function storeFile(string $fileNameSuffix, array $data)
    {
        $this->filesystem->dumpFile(
            $this->filesDir . '/top' . $this->resultArrayLength . $fileNameSuffix,
            json_encode($data)
        );
    }
}