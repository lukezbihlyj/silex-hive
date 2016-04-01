<?php

namespace LukeZbihlyj\SilexHive\Console;

use Silex\Application;
use LukeZbihlyj\SilexPlus\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Resque;

/**
 * @package LukeZbihlyj\SilexHive\Command\HiveStatusCommand
 */
class HiveStatusCommand extends ConsoleCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('hive:status')
            ->setDescription('Check how many jobs are currently pending in each queue');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApp();
        $hive = $app->getHive();

        $queues = $hive->getQueues();

        if (empty($queues)) {
            $output->writeln('<comment>No queues were found on the Hive server</comment>');
        } else {
            foreach ($queues as $queue) {
                $output->writeln('<info>Queue "' . $queue . '" has ' . $hive->getQueueSize($queue) . ' jobs</info>');
            }
        }
    }
}
