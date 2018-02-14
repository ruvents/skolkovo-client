<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\ContextBuilder\Oauth;

use Ruwork\SkolkovoClient\ContextBuilder\AbstractContextBuilder;
use Ruwork\SkolkovoClient\SkolkovoClient;
use Ruwork\SkolkovoClient\Token;

abstract class AbstractTokenContextBuilder extends AbstractContextBuilder
{
    /**
     * {@inheritdoc}
     */
    public function __construct(SkolkovoClient $client)
    {
        parent::__construct($client);

        $this->context = [
            'authenticate' => false,
            'endpoint' => '/oauth/token',
            'method' => 'POST',
            'data' => [
                'client_id' => $this->client->getDefaultContext()['client_id'],
                'client_secret' => $this->client->getDefaultContext()['client_secret'],
                'grant_type' => $this->getGrantType(),
            ],
        ];
    }

    public function getResult(): Token
    {
        return Token::createFromData(parent::getResult());
    }

    abstract protected function getGrantType(): string;

    /**
     * {@inheritdoc}
     */
    protected function convertParamName(string $name): string
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $name));
    }
}
