<?php

/**
 * Specify application-specific configuration. These settings can be over-ridden
 * by the local environmental settings, so it's safe to specify default values
 * here.
 */
return [
    /**
     * Define the Redis server's connection information for Hive.
     */
    'hive.redis' => '127.0.0.1:6379',

    /**
     * Define a list of commands that should be added to the console on initialisation.
     */
    'console.commands' => [
        'LukeZbihlyj\SilexHive\Console\HiveBeeCommand',
        'LukeZbihlyj\SilexHive\Console\HiveQueueCommand',
        'LukeZbihlyj\SilexHive\Console\HiveStatusCommand'
    ],
];
