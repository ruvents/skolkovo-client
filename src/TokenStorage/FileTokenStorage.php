<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\TokenStorage;

use Ruwork\SkolkovoClient\Exception\TokenStorageException;
use Ruwork\SkolkovoClient\Token;

class FileTokenStorage implements TokenStorageInterface
{
    private $file;

    private $loaded = false;

    /**
     * @var null|Token
     */
    private $token;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function has(): bool
    {
        $this->load();

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
        if (false === @file_put_contents($this->file, $this->serialize($token))) {
            throw new TokenStorageException(sprintf('Failed to write token to file "%s".', $this->file));
        }

        $this->loaded = true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        @unlink($this->file);

        $this->loaded = true;
    }

    private function load(): void
    {
        if ($this->loaded) {
            return;
        }

        $this->loaded = true;

        if (false === $serialized = @file_get_contents($this->file)) {
            return;
        }

        try {
            $this->token = $this->unserialize($serialized);
        } catch (\Throwable $exception) {
            $this->clear();

            throw new TokenStorageException(sprintf('Failed to unserialize token from string "%s".', $serialized));
        }
    }

    private function serialize(Token $token): string
    {
        return json_encode([
            'access_token' => $token->getAccessToken(),
            'refresh_token' => $token->getRefreshToken(),
            'expires_at' => $token->getExpiresAt()->format('c'),
        ]);
    }

    private function unserialize(string $serialized): Token
    {
        $data = json_decode($serialized, true);

        return new Token($data['access_token'], $data['refresh_token'], new \DateTimeImmutable($data['expires_at']));
    }
}
