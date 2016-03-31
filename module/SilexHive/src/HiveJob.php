<?php

namespace LukeZbihlyj\SilexHive;

use Silex\Application;

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
        return $this->execute($this->app, $this->args);
    }

    /**
     * @param Application $app
     * @param array $args
     * @return void
     */
    abstract protected function execute(Application $app, array $args);
}
