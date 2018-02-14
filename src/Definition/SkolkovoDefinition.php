<?php

declare(strict_types=1);

namespace Ruwork\SkolkovoClient\Definition;

use Http\Message\RequestFactory;
use Http\Message\UriFactory;
use Ruvents\AbstractApiClient\ApiClientInterface;
use Ruvents\AbstractApiClient\Definition;
use Ruvents\AbstractApiClient\Exception\ServiceException;
use Ruwork\SkolkovoClient\SkolkovoClient;
use Ruwork\SkolkovoClient\TokenStorage\TokenStorageInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SkolkovoDefinition implements Definition\ApiDefinitionInterface
{
    use Definition\Response200Trait;
    use Definition\JsonDecodeTrait;

    private $defaultTokenStorage;

    public function __construct(TokenStorageInterface $defaultTokenStorage = null)
    {
        $this->defaultTokenStorage = $defaultTokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function configureDefaultContext(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired([
                'client_id',
                'client_secret',
            ])
            ->setDefaults([
                'endpoint_prefix' => '/api.ashx/v2',
                'host' => 'https://sk.ru',
                'token_storage' => $this->defaultTokenStorage,
            ])
            ->setAllowedTypes('client_id', 'string')
            ->setAllowedTypes('client_secret', 'string')
            ->setAllowedTypes('endpoint_prefix', 'string')
            ->setAllowedTypes('host', 'string')
            ->setAllowedTypes('token_storage', ['null', TokenStorageInterface::class])
            ->setNormalizer('endpoint_prefix', function (Options $context, $endpoint) {
                return '/'.trim($endpoint, '/');
            })
            ->setNormalizer('host', function (Options $context, $host) {
                return rtrim($host, '/');
            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureRequestContext(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired([
                'endpoint',
            ])
            ->setDefaults([
                'authenticate' => true,
                'body' => null,
                'data' => [],
                'headers' => [],
                'method' => 'GET',
                'query' => [],
            ])
            ->setDefined('data')
            ->setAllowedTypes('authenticate', 'bool')
            ->setAllowedTypes('body', ['null', 'string', 'Psr\Http\Message\StreamInterface'])
            ->setAllowedTypes('data', 'array')
            ->setAllowedTypes('endpoint', 'string')
            ->setAllowedTypes('headers', 'array')
            ->setAllowedTypes('method', 'string')
            ->setAllowedTypes('query', 'array')
            ->setNormalizer('endpoint', function (Options $context, $endpoint) {
                return '/'.ltrim($endpoint, '/');
            })
            ->setNormalizer('method', function (Options $context, $method) {
                return mb_strtoupper($method);
            });
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(UriFactory $uriFactory, RequestFactory $requestFactory, ApiClientInterface $client, array $context)
    {
        $query = $context['query'];
        $headers = $context['headers'];
        $body = $context['body'];
        $endpoint = $context['endpoint_prefix'].$context['endpoint'];

        if (null === $context['body'] && [] !== $context['data']) {
            if ('POST' === $context['method'] || 'PUT' === $context['method']) {
                $body = $this->httpBuildQuery($context['data']);
                $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            } else {
                $query = array_replace($query, $context['data']);
            }
        }

        $request = $requestFactory->createRequest(
            $context['method'],
            $context['host'].$endpoint.'?'.$this->httpBuildQuery($query),
            $headers,
            $body
        );

        if ($context['authenticate']) {
            $tokenStorage = $context['token_storage'];

            if (!$tokenStorage instanceof TokenStorageInterface) {
                throw new \RuntimeException('No token storage provided.');
            }

            $token = $tokenStorage->get();

            if (!$token->isFresh()) {
                if (!$client instanceof SkolkovoClient) {
                    throw new \InvalidArgumentException(
                        sprintf('Client must be an instance of %s.', SkolkovoClient::class)
                    );
                }

                $token = $client->oauthTokenRefresh()
                    ->setRefreshToken($token->getRefreshToken())
                    ->getResult();

                $tokenStorage->set($token);
            }

            $request = $request->withHeader('Authorization', 'OAuth '.$token->getAccessToken());
        }

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function validateData($data, array $context): void
    {
        if (isset($data['error'])) {
            throw new ServiceException($data['error']);
        }
    }

    private function httpBuildQuery(array $data): string
    {
        return http_build_query($data, '', '&');
    }
}
