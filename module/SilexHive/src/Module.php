<?php

namespace LukeZbihlyj\SilexHive;

use LukeZbihlyj\SilexPlus\Application;
use LukeZbihlyj\SilexPlus\ModuleInterface;

/**
 * @package LukeZbihlyj\SilexHive\Module
 */
class Module implements ModuleInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigFile()
    {
        return __DIR__ . '/../config/module.php';
    }

    /**
     * {@inheritDoc}
     */
    public function init(Application $app)
    {
        $app['hive'] = $app->share(function() use ($app) {
            $hive = new Hive();
            $hive->setRedis($app['hive.redis']);

            return $hive;
        });
    }
}
