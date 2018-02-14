<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\TokenStorage;

use Ruwork\SkolkovoClient\Exception\TokenStorageException;
use Ruwork\SkolkovoClient\Token;

class InstantTokenStorage implements TokenStorageInterface
{
    private $token;

    public function __construct(Token $token = null)
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function has(): bool
    {
        return null !== $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): Token
    {
        if (!$this->has()) {
            throw new TokenStorageException('Token storage is empty.');
        }

        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function set(Token $token): void
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->token = null;
    }
}
