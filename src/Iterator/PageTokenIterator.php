<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\Iterator;

use Ruvents\AbstractApiClient\ApiClientInterface;

class PageTokenIterator implements \Iterator, \Countable
{
    private $client;

    private $context;

    private $dataKey;

    private $index = 0;

    private $data = [];

    private $pageSize;

    private $pageIndex = 0;

    private $loaded = false;

    public function __construct(ApiClientInterface $client, array $context, string $dataKey, int $pageSize = 20)
    {
        $this->client = $client;
        $this->context = $context;
        $this->dataKey = $dataKey;
        $this->pageSize = $pageSize;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->data[$this->index];
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        ++$this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        if (!isset($this->data[$this->index])) {
            $this->loadData();
        }

        return isset($this->data[$this->index]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->loaded ? count($this->data) : iterator_count($this);
    }

    protected function loadData(): void
    {
        if ($this->loaded) {
            return;
        }

        // copy and modify context
        $context = array_replace_recursive($this->context, [
            'query' => [
                'PageSize' => $this->pageSize,
                'PageIndex' => $this->pageIndex,
            ],
        ]);

        // request raw data
        $raw = $this->client->request($context);

        ++$this->pageIndex;

        $this->data = array_merge($this->data, array_values($raw[$this->dataKey]));

        if ($raw['TotalCount'] === count($this->data)) {
            $this->loaded = true;
        }
    }
}
