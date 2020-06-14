<?php

namespace MetaRush\Perm;

use Psr\Http\Message\ServerRequestInterface;

class Request
{
    private int $userId;
    private int $roleId;
    private string $resourceId;
    private ?ServerRequestInterface $serverRequest;

    public function __construct(int $userId, int $roleId, string $resourceId, ?ServerRequestInterface $serverRequest = null)
    {
        $this->userId = $userId;
        $this->roleId = $roleId;
        $this->resourceId = $resourceId;
        $this->serverRequest = $serverRequest;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getServerRequest(): ?ServerRequestInterface
    {
        return $this->serverRequest;
    }

}