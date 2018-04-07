<?php
/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace Api\Console;


interface CommandInterface
{
    /**
     * Run command
     *
     * @return void
     */
    public function run(): void;

    /**
     * Command arguments definitions
     *
     * @return array
     */
    public function getDefinitions(): array;

    /**
     * @param array $args
     */
    public function setArguments(array $args): void;

    /**
     * @return array
     */
    public function getArguments(): array;

    /**
     * @param string $name
     * @return mixed
     */
    public function getArgument(string $name);
}