<?php
declare(strict_types=1);

/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace App\Console;


use Api\Console\CommandInterface;
use App\Exceptions\Console\ArgumentMissedException;
use App\Exceptions\Console\CommandNotFoundException;

class Handler
{
    /**
     * Mapping for allowed CLI commands
     *
     * @var array
     */
    private $allowedCommands;

    /**
     * Handler constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->allowedCommands = $config['commands'];
    }

    /**
     * @param $argv
     * @throws ArgumentMissedException
     * @throws CommandNotFoundException
     */
    public function handle($argv)
    {
        // Remove script name
        array_shift($argv);

        $commandName = $argv[0];

        if (!in_array($commandName, array_keys($this->allowedCommands))) {
            throw new CommandNotFoundException("Command '$commandName' not found");
        }

        /** @var CommandInterface $command */
        $command = new $this->allowedCommands[$commandName];

        // Remove command name
        array_shift($argv);

        $arguments = $this->parseArgumentsForCommand($argv, $command->getDefinitions());
        $command->setArguments($arguments);
        $command->run();
    }

    /**
     * @param array $args
     * @param array $definitions
     * @return array
     * @throws ArgumentMissedException
     */
    private function parseArgumentsForCommand(array $args, array $definitions = [])
    {
        $argumentsByKeys = [];

        foreach ($args as $arg) {
            list($key, $value) = explode('=', $arg);
            $argumentsByKeys[$key] = $value;
        }

        $res = [];
        foreach ($definitions as $def) {
            if (!isset($argumentsByKeys[$def])) {
                throw new ArgumentMissedException("Argument '$def' required");
            }

            $res[ltrim($def, '--')] = $argumentsByKeys[$def];
        }

        return $res;
    }
}