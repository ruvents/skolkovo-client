<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient;

use Ruvents\AbstractApiClient\AbstractApiClient;
use Ruwork\SkolkovoClient\Definition\SkolkovoDefinition;

/**
 * @method ContextBuilder\AggregatetaggedcontentContextBuilder aggregatetaggedcontent()
 * @method ContextBuilder\CreateuserContextBuilder createuser()
 * @method ContextBuilder\InfoContextBuilder info()
 * @method ContextBuilder\UsersContextBuilder users()
 * @method ContextBuilder\Oauth\TokenCodeContextBuilder oauthTokenCode()
 * @method ContextBuilder\Oauth\TokenPasswordContextBuilder oauthTokenPassword()
 * @method ContextBuilder\Oauth\TokenRefreshContextBuilder oauthTokenRefresh()
 */
class SkolkovoClient extends AbstractApiClient
{
    public function __construct(array $defaultContext = [], $extensions = [], SkolkovoDefinition $definition = null)
    {
        parent::__construct($definition ?? new SkolkovoDefinition(), $defaultContext, $extensions);
    }

    public function generateLoginUrl(string $redirectUrl): string
    {
        $context = $this->getDefaultContext();

        $query = [
            'client_id' => $context['client_id'],
            'response_type' => 'code',
            'redirect_uri' => $redirectUrl,
        ];

        return $context['host'].$context['endpoint_prefix'].'/oauth/authorize?'.http_build_query($query);
    }
}
