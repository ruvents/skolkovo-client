<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\TokenStorage;

use Ruwork\SkolkovoClient\Token;

interface TokenStorageInterface
{
    public function has(): bool;

    public function get(): Token;

    public function set(Token $token): void;

    public function clear(): void;
}
