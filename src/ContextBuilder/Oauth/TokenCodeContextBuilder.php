<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\ContextBuilder\Oauth;

/**
 * @method $this setCode(string $code)
 * @method $this setRedirectUri(string $redirectUri)
 */
class TokenCodeContextBuilder extends AbstractTokenContextBuilder
{
    /**
     * {@inheritdoc}
     */
    protected function getGrantType(): string
    {
        return 'authorization_code';
    }
}
