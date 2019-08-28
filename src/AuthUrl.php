<?php
declare(strict_types=1);
namespace Patreon;

/**
 * Class AuthUrl
 * @package Patreon
 */
class AuthUrl
{
    /** @var string $baseUrl */
    private $baseUrl = 'https://www.patreon.com/oauth2/authorize';

    /** @var string $clientId */
    protected $clientId;

    /** @var string[] $scopes */
    protected $scopes = [];

    /** @var array<string, mixed> $state */
    protected $state = [];

    /** @var string $redirectUri */
    protected $redirectUri;

    /**
     * AuthUrl constructor.
     * @param string $clientId
     * @param string $redirectUri
     * @param string[] $scopes
     */
    public function __construct(
        string $clientId = '',
        string $redirectUri = '',
        array $scopes = []
    ) {
        $this->clientId = $clientId;
        $this->redirectUri = $redirectUri;
        $this->scopes = $scopes;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->buildUrl();
        } catch (\Throwable $ex) {
            return '';
        }
    }

    /**
     * @return string
     */
    public function buildUrl(): string
    {
        $pieces = [
            'response_type' => 'code',
            'client_id' => $this->clientId
        ];
        if (!empty($this->state)) {
            $pieces['state'] = $this->state;
        }
        if (!empty($this->scopes)) {
            $pieces['scope'] = implode(' ', $this->scopes);
        }
        return $this->baseUrl . '?' . http_build_query($pieces);
    }

    /**
     * @param string $key
     * @param string|string[] $value
     * @return AuthUrl
     */
    public function with(string $key, $value): self
    {
        switch ($key) {
            case 'clientId':
                if (!is_string($value)) {
                    throw new \TypeError('Expected string');
                }
                return $this->withClientId($value);
            case 'redirectUri':
                if (!is_string($value)) {
                    throw new \TypeError('Expected string');
                }
                return $this->withRedirectUri($value);
            case 'addedScope':
                if (!is_string($value)) {
                    throw new \TypeError('Expected string');
                }
                return $this->withAddedScope($value);
            case 'scopes':
                if (!is_array($value)) {
                    throw new \TypeError('Expected array');
                }
                return $this->withScopes($value);
            case 'state':
                if (!is_array($value)) {
                    throw new \TypeError('Expected array');
                }
                return $this->withState($value);
            default:
                $self = clone $this;
                $self->state[$key] = $value;
                return $self;
        }
    }

    /**
     * @param string $newScope
     * @return self
     */
    public function withAddedScope(string $newScope): self
    {
        $self = clone $this;
        $self->scopes []= $newScope;
        return $self;
    }

    /**
     * @param string $clientId
     * @return self
     */
    public function withClientId(string $clientId = ''): self
    {
        $self = clone $this;
        $self->clientId = $clientId;
        return $self;
    }

    /**
     * @param string $redirectUri
     * @return self
     */
    public function withRedirectUri(string $redirectUri = ''): self
    {
        $self = clone $this;
        $self->redirectUri = $redirectUri;
        return $self;
    }

    /**
     * @param string[] $scopes
     * @return self
     */
    public function withScopes(array $scopes = []): self
    {
        $self = clone $this;
        $self->scopes = $scopes;
        return $self;
    }

    /**
     * @param array $state
     * @return self
     */
    public function withState(array $state = []): self
    {
        $self = clone $this;
        $self->state = $state;
        return $self;
    }
}
