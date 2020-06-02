<?php

namespace MetaRush\Perm;

class Request
{
    private int $userId;
    private int $roleId;
    private int $resourceId;

    public function __construct(int $userId, int $roleId, int $resourceId)
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

    public function getResourceId(): int
    {
        return $this->resourceId;
    }

}