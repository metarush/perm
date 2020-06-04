<?php

namespace MetaRush\Perm;

class Request
{
    private int $userId;
    private int $roleId;
    private string $resourceId;

    public function __construct(int $userId, int $roleId, string $resourceId)
    {
        $this->userId = $userId;
        $this->roleId = $roleId;
        $this->resourceId = $resourceId;
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

}