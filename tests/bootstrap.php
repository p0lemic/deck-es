<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env.test');
}

exec('bin/console --env=test doctrine:database:drop --no-interaction');
exec('bin/console --env=test doctrine:database:create --no-interaction');
exec('bin/console --env=test doctrine:schema:create --no-interaction');
//exec('bin/console doctrine:migrations:migrate --env=test --no-interaction');
