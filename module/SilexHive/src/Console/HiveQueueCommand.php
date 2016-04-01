<?php

namespace LukeZbihlyj\SilexHive\Console;

use Silex\Application;
use LukeZbihlyj\SilexPlus\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package LukeZbihlyj\SilexHive\Command\HiveQueueCommand
 */
class HiveQueueCommand extends ConsoleCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('hive:queue')
            ->setDescription('Add a job to the specified queue.')
            ->setDefinition([
                new InputArgument('queue', InputArgument::REQUIRED, 'Which queue to add the job to'),
                new InputArgument('class', InputArgument::REQUIRED, 'The job class to execute'),
                new InputArgument('args', InputArgument::OPTIONAL, 'JSON encoded arguments to send with the job')
            ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApp();
        $hive = $app->getHive();

        $queue = $input->getArgument('queue');
        $class = $input->getArgument('class');
        $args = json_decode($input->getArgument('args'), true);

        $jobId = $hive->queue($queue, $class, $args);

        $output->writeln('<info>Successfully queued job with ID: ' . $jobId . '</info>');
    }
}
