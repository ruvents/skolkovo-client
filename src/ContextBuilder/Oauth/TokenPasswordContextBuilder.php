<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\ContextBuilder\Oauth;

/**
 * @method $this setUsername(string $username)
 * @method $this setPassword(string $password)
 */
class TokenPasswordContextBuilder extends AbstractTokenContextBuilder
{
    /**
     * {@inheritdoc}
     */
    protected function getGrantType(): string
    {
        return 'password';
    }
}
