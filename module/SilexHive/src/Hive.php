<?php

namespace LukeZbihlyj\SilexHive;

use Resque;
use Resque_Job_Status;

/**
 * @package LukeZbihlyj\SilexHive\Hive
 */
class Hive
{
    /**
     * @param string $dsn
     * @return mixed
     */
    public function setRedis($dsn)
    {
        return Resque::setBackend($dsn);
    }

    /**
     * @param string $jobId
     * @return integer
     */
    public function getJobStatus($jobId)
    {
        $jobStatus = new Resque_Job_Status($jobId);

        return $jobStatus->get();
    }

    /**
     * @param string $queue
     * @param string $class
     * @param mixed $args
     * @return string
     */
    public function queue($queue, $class, $args)
    {
        return Resque::enqueue($queue, $class, $args, true);
    }

    /**
     * @param string $queue
     * @param array $jobs
     * @return integer
     */
    public function dequeue($queue, $jobs)
    {
        return Resque::dequeue($queue, $jobs);
    }
}
