<?php

declare(strict_types=1);

namespace gift\appli\utils;

use Illuminate\Database\Capsule\Manager as Capsule;

class Eloquent
{
    private static bool $isInitialized = false;

    public static function init(): void
    {
        if (self::$isInitialized) {
            return;
        }

        $capsule = new Capsule;

        
        $db_host = getenv('DB_HOST') ?: 'localhost';
        $db_database = getenv('DB_DATABASE') ?: 'giftbox';
        $db_user = getenv('DB_USER') ?: 'root';
        $db_password = getenv('DB_PASSWORD') ?: '';

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $db_host,
            'database'  => $db_database,
            'username'  => $db_user,
            'password'  => $db_password,
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        self::$isInitialized = true;
    }
}