<?php

namespace Climbx\Dotenv\Loader;

class Loader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(array $data): void
    {
        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $_ENV)) {
                $this->writeInEnv($key, $value);
            }

            if (!array_key_exists($key, $_SERVER)) {
                $this->writeInServer($key, $value);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function overLoad(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->writeInEnv($key, $value);
            $this->writeInServer($key, $value);
        }
    }

    /**
     * @param string $key
     * @param string $value
     */
    private function writeInEnv(string $key, string $value): void
    {
        $_ENV[$key] = $value;
    }

    /**
     * @param string $key
     * @param string $value
     */
    private function writeInServer(string $key, string $value): void
    {
        $_SERVER[$key] = $value;
    }
}
