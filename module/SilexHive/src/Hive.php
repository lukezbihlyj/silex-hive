<?php

namespace LukeZbihlyj\SilexHive;

use Resque;
use Resque_Job;
use Resque_Job_Status;
use Resque_Job_DontCreate;
use Resque_Event;

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
     * @return array
     */
    public function getQueues()
    {
        return Resque::queues();
    }

    /**
     * @param string $queue
     * @return integer
     */
    public function getQueueSize($queue)
    {
        return Resque::size($queue);
    }

    /**
     * @param string $queue
     * @param string $class
     * @param mixed $args
     * @param string $jobId
     * @return string
     */
    public function queue($queue, $class, $args, $jobId = null)
    {
        if (!$args) {
            $args = [];
        }

        if (!$jobId) {
            $jobId = Resque::generateJobId();
        }

        $hookParams = [
            'class' => $class,
            'args' => $args,
            'queue' => $queue,
            'id' => $jobId
        ];

        try {
            Resque_Event::trigger('beforeEnqueue', $hookParams);
        } catch (Resque_Job_DontCreate $e) {
            return false;
        }

        Resque_Job::create($queue, $class, $args, true, $jobId);
        Resque_Event::trigger('afterEnqueue', $hookParams);

        return $jobId;
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
