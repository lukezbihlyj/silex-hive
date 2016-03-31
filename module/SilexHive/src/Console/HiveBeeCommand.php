<?php

namespace LukeZbihlyj\SilexHive\Console;

use Silex\Application;
use LukeZbihlyj\SilexPlus\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Psr\Log\LogLevel;
use Resque_Event;
use Resque_Job;
use Resque_Worker;

/**
 * @package LukeZbihlyj\SilexHive\Command\HiveBeeCommand
 */
class HiveBeeCommand extends ConsoleCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('hive:bee')
            ->setDescription('Run a new worker that processes incoming commands from the Hive queue.')
            ->setDefinition([
                new InputArgument('queues', InputArgument::REQUIRED, 'Comma separated list of queues to process'),
                new InputOption('interval', null, InputOption::VALUE_REQUIRED, 'Time between each task (seconds)', 5)
            ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApp();

        $queues = explode(',', $input->getArgument('queues'));
        $interval = $input->getOption('interval');

        Resque_Event::listen('beforePerform', [$this, 'beforePerform']);

        $logger = new ConsoleLogger($output, [
            LogLevel::EMERGENCY => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::ALERT => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::CRITICAL => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::ERROR => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::WARNING => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::INFO => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::DEBUG => OutputInterface::VERBOSITY_VERBOSE
        ]);

        $worker = new Resque_Worker($queues);
        $worker->setLogger($logger);
        $worker->work($interval);
    }

    /**
     * @param Resque_Job $job
     * @return void
     */
    public function beforePerform(Resque_Job $job)
    {
        $job->getInstance()->app = $this->getSilexApp();
    }
}
