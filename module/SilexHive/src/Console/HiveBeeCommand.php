<?php

namespace LukeZbihlyj\SilexHive\Console;

use Silex\Application;
use LukeZbihlyj\SilexPlus\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Resque_Event;
use Resque_Job;
use Resque_Worker;

/**
 * @package LukeZbihlyj\SilexHive\Command\HiveBeeCommand
 */
class HiveBeeCommand extends Command
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
                new InputOption('interval', null, InputOption::VALUE_REQUIRED, 'Time between each task (seconds)', 5),
                new InputOption('verbose', null, InputOption::VALUE_NONE, 'If set, logging will be verbose'),
                new InputOption('quiet', null, InputOption::VALUE_NONE, 'If set, no logging will be performed')
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
        $logLevel = Resque_Worker::LOG_NORMAL;

        if ($input->getOption('verbose')) {
            $logLevel = Resque_Worker::LOG_VERBOSE;
        }

        if ($input->getOption('quiet')) {
            $logLevel = Resque_Worker::LOG_NONE;
        }

        Resque_Event::listen('beforePerform', [$this, 'beforePerform']);

        $worker = new Resque_Worker($queues);
        $worker->logLevel = $logLevel;
        $worker->setLogger($output);
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
