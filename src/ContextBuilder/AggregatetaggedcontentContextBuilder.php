<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\ContextBuilder;

use Ruwork\SkolkovoClient\Iterator\PageTokenIterator;

/**
 * @method $this setTags(string $tags)
 * @method $this setSortBy(string $sortBy)
 * @method $this setSortOrder(string $sortOrder)
 */
class AggregatetaggedcontentContextBuilder extends AbstractContextBuilder
{
    public $context = [
        'endpoint' => '/aggregatetaggedcontent.json',
        'method' => 'GET',
    ];

    public function getIteratorResult(): PageTokenIterator
    {
        return new PageTokenIterator($this->client, $this->context, 'AggregateTaggedContent');
    }
}
