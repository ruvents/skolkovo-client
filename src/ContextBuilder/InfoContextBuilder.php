<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\ContextBuilder;

class InfoContextBuilder extends AbstractContextBuilder
{
    public $context = [
        'endpoint' => '/info.json',
        'method' => 'GET',
    ];
}
