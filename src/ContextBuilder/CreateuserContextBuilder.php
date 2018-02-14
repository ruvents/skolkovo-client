<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\ContextBuilder;

use Ruwork\SkolkovoClient\SkolkovoClient;

/**
 * @method $this setEmail(string $email)
 * @method $this setFirstname(string $firstname)
 * @method $this setLastname(string $lastname)
 * @method $this setPassword(string $password)
 * @method $this setUsername(string $username)
 */
class CreateuserContextBuilder extends AbstractContextBuilder
{
    public $context = [
        'endpoint' => '/createuser.ashx',
        'endpoint_prefix' => 'CreateUserService',
        'method' => 'POST',
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct(SkolkovoClient $client)
    {
        parent::__construct($client);
        $this->context['headers']['ClientId'] = $client->getDefaultContext()['client_id'];
    }

    /**
     * {@inheritdoc}
     */
    protected function convertParamName(string $name): string
    {
        return lcfirst($name);
    }
}
