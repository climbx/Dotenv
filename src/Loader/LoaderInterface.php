<?php

namespace Climbx\Dotenv\Loader;

interface LoaderInterface
{
    /**
     * Loads .env parsed data into $_ENV & $_SERVER global variables.
     *
     * If a key already exists in $_ENV or $_SERVER, it's not overridden.
     *
     * @param array $data
     */
    public function load(array $data): void;

    /**
     * Loads .env parsed data into $_ENV & $_SERVER global variables.
     *
     * If a key already exists in $_ENV or $_SERVER, it is overridden.
     *
     * @param array $data
     */
    public function overLoad(array $data): void;
}
