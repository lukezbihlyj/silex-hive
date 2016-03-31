<?php

namespace LukeZbihlyj\SilexHive;

use Silex\Application;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * @package LukeZbihlyj\SilexHive\HiveJob
 */
abstract class HiveJob
{
    /**
     * @var Application
     */
    public $app;

    /**
     * @var Resque_Job
     */
    public $job;

    /**
     * @var string
     */
    public $queue;

    /**
     * @var array
     */
    public $args;

    /**
     * @return mixed
     */
    final public function perform()
    {
        $this->output = new ConsoleOutput();
        $this->output->setFormatter(new OutputFormatter(true));

        return $this->execute($this->app, $this->output, $this->args);
    }

    /**
     * @param Application $app
     * @param OutputInterface $output
     * @param array $args
     * @return void
     */
    abstract protected function execute(Application $app, OutputInterface $output, array $args);
}
