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
    /**
     * @var string
     */
    protected static $defaultName = 'csv:import';

    /**
     * @var CalculateService
     */
    private $calculateService;

    /**
     * @var FileHandler
     */
    private $fileHandler;

    /**
     * @var CurrencyHandler
     */
    private $currencyHandler;

    /**
     * CsvImportCommand constructor.
     *
     * @param CalculateService $calculateService
     * @param FileHandler $fileHandler
     * @param CurrencyHandler $currencyHandler
     */
    public function __construct(CalculateService $calculateService, FileHandler $fileHandler, CurrencyHandler $currencyHandler)
    {
        parent::__construct();

        $this->calculateService = $calculateService;
        $this->fileHandler = $fileHandler;
        $this->currencyHandler = $currencyHandler;
    }

    /**
     * Configure the command arguments
     */
    protected function configure()
    {
        $this
            ->setDescription('Imports a mock csv data and calculates the the sum of all the documents')
            ->addArgument('file_path',InputArgument::REQUIRED, 'path to csv file') //{src/Data/data.csv}
            ->addArgument('currencies', InputArgument::REQUIRED, 'currencies')
            ->addArgument('output_currency',InputArgument::REQUIRED, 'output currency')
            ->addOption('vat', null,InputOption::VALUE_OPTIONAL, 'Option description')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fileData =  $this->fileHandler->getCsvData($input->getArgument('file_path'));
        $currencyData = $this->currencyHandler->getCurrencies($input->getArgument('currencies'));
        $this->calculateService->setData($fileData);
        $this->calculateService->setCurrencies($currencyData);
        $calculations = $this->calculateService->getCalculationResult($input->getOption('vat'), $input->getArgument('output_currency'));

        $io->success($this->displayCalculatedResult($calculations));

        return 0;
    }

    /**
     * @param $calculations
     * @return string
     */
    private function displayCalculatedResult($calculations)
    {
        $output = '';

        foreach ($calculations as $customer => $total) {
            $output =  sprintf('%s - %d', $customer, $total);
        }

        return $output;
    }
}
