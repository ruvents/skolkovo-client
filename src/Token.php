<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient;

final class Token
{
    private $accessToken;

    private $refreshToken;

    private $expiresAt;

    public function __construct(string $accessToken, string $refreshToken, \DateTimeImmutable $expiresAt)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expiresAt = $expiresAt;
    }

    public static function createFromData(array $data): self
    {
        $expiresAt = (new \DateTimeImmutable())
            ->add(new \DateInterval('PT'.$data['expires_in'].'S'));

        return new self($data['access_token'], $data['refresh_token'], $expiresAt);
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getExpiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function isFresh(): bool
    {
        return new \DateTimeImmutable() < $this->expiresAt;
    }
}
