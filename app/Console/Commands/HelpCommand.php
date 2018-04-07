<?php
declare(strict_types=1);

/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace App\Console\Commands;


class HelpCommand extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    public function run(): void
    {
        $message = "Это простой парсер сайтов." . PHP_EOL;
        $message .= "Для запуска парсера используйте команду:" . PHP_EOL;
        $message .= "php parser parse --url=<some-url>" . PHP_EOL . PHP_EOL;

        $message .= "Для получения отчета по проведенному ранее парсингу выполните команду:" . PHP_EOL;
        $message .= "php parser report --domain=<some-domain>" . PHP_EOL . PHP_EOL;

        $message .= "Для получения помощи выполните команду:" . PHP_EOL;
        $message .= "php parser help" . PHP_EOL . PHP_EOL;

        print $message;
    }
}