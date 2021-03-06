<?php

declare(strict_types=1);

namespace Tests\Samples;

use MetaRush\Perm\PermissionInterface;
use MetaRush\Perm\Request;

class MyOwnerFinder implements PermissionInterface
{
    // these are just copies from PermTest.php
    private const RESOURCE_EDIT_POST = 'editPost';
    private const RESOURCE_DELETE_COMMENT = 'deleteComment';

    private Request $request;

    public function hasPermission(Request $request): bool
    {
        $this->request = $request;

        // ------------------------------------------------

        $resourceId = $request->getResourceId();

        if ($resourceId === self::RESOURCE_EDIT_POST)
            return $this->isPostOwner();

        if ($resourceId === self::RESOURCE_DELETE_COMMENT)
            return $this->isCommentOwner();

        return false;
    }

    private function isPostOwner(): bool
    {
        // e.g., get ownerId from DB (in actual, you will probably do some DB queries)
        $ownerId = 5;

        return $this->request->getUserId() === $ownerId;
    }

    private function isCommentOwner(): bool
    {
        // e.g., get ownerId from DB (in actual, you will probably do some DB queries)
        $ownerId = 5;

        return $this->request->getUserId() === $ownerId;
    }

}