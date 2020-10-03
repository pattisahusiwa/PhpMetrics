<?php declare(strict_types=1);

namespace Phrozer;

/** @codeCoverageIgnore */
final class AppExit implements ExitInterface
{

    public function onExit(int $exitCode = 0)  : void
    {
        exit($exitCode);
    }
}