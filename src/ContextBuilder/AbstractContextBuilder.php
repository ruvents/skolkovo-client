<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\ContextBuilder;

use Ruvents\AbstractApiClient\ContextBuilder\AbstractContextBuilder as BaseAbstractContextBuilder;
use Ruwork\SkolkovoClient\SkolkovoClient;
use Ruwork\SkolkovoClient\TokenStorage\TokenStorageInterface;

abstract class AbstractContextBuilder extends BaseAbstractContextBuilder
{
    /**
     * @var SkolkovoClient
     */
    protected $client;

    public function __construct(SkolkovoClient $client)
    {
        parent::__construct($client);
    }

    public function setParam(string $name, $value)
    {
        $this->context['data'][$name] = $value;

        return $this;
    }

    public function setParams(array $params)
    {
        if (!isset($this->context['data'])) {
            $this->context['data'] = [];
        }

        $this->context['data'] = array_replace($this->context['data'], $params);

        return $this;
    }

    public function setTokenStorage(?TokenStorageInterface $tokenStorage)
    {
        $this->context['token_storage'] = $tokenStorage;

        return $this;
    }

    public function getResult()
    {
        return $this->client->request($this->context);
    }

    protected function applySetter($name, $value)
    {
        return $this->setParam($this->convertParamName($name), $value);
    }

    protected function convertParamName(string $name): string
    {
        return $name;
    }
}
