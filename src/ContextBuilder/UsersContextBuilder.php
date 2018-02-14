<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\ContextBuilder;

/**
 * @method $this setEmailAddress(string $emailAddress)
 */
class UsersContextBuilder extends AbstractContextBuilder
{
    public $context = [
        'endpoint' => '/users.json',
        'method' => 'GET',
    ];
}