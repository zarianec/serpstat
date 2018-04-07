<?php
declare(strict_types=1);

/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace App\Console\Commands;


use Api\Console\CommandInterface;

abstract class AbstractCommand implements CommandInterface
{
    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @inheritdoc
     */
    public function getDefinitions(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    /**
     * @inheritdoc
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @inheritdoc
     */
    public function getArgument(string $name)
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * @inheritdoc
     */
    abstract public function run(): void;
}