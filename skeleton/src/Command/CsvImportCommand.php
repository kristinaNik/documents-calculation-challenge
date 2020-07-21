<?php

namespace App\Command;

use App\Entity\Currency;
use App\Handlers\CurrencyHandler;
use App\Handlers\FileHandler;
use App\Services\CalculateService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CsvImportCommand extends Command
{
    protected static $defaultName = 'csv:import';

    const EUR = 'EUR:';
    const USD = 'USD:';
    const GBP = 'GBP:';



    /**
     * @var CalculateService
     */
    private $calculateService;

    private $fileHandler;

    private $currencyHandler;

    /**
     * CsvImportCommand constructor.
     *
     * @param CalculateService $calculateService
     */
    public function __construct(CalculateService $calculateService, FileHandler $fileHandler, CurrencyHandler $currencyHandler)
    {
        parent::__construct();

        $this->calculateService = $calculateService;
        $this->fileHandler = $fileHandler;
        $this->currencyHandler = $currencyHandler;
    }

    protected function configure()
    {

        $this
            ->setDescription('Imports a mock csv data and calculate')
            ->addArgument('file_path',InputArgument::REQUIRED, 'path to csv file')
            ->addArgument('currencies', InputArgument::REQUIRED, 'currencies')
            ->addArgument('output_currency',InputArgument::REQUIRED, 'output currency')
            ->addOption('vat', null, InputOption::VALUE_OPTIONAL, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $fileData =  $this->fileHandler->getCsvData($input->getArgument('file_path'));
        $currencyData = $this->currencyHandler->getCurrencies($input->getArgument('currencies'));

        $this->calculateService->setData($fileData);
        $this->calculateService->setCurrencies($currencyData);
        $totals = $this->calculateService->getTotals($input->getOptions());

        $arg1 = $input->getOptions();

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('vat')) {

        }

        $this->displayCalculatedResult($totals,$input->getArgument('output currency'));
        $io->success('Successfully calculated ');

        return 0;
    }

    private function displayCalculatedResult($totals,$outputCurrency)
    {

    }
}
