<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\ContextBuilder\Oauth;

/**
 * @method $this setRefreshToken(string $refreshToken)
 */
class TokenRefreshContextBuilder extends AbstractTokenContextBuilder
{
    /**
     * {@inheritdoc}
     */
    protected function getGrantType(): string
    {
        return 'refresh_token';
    }
}
