<?php

declare(strict_types=1);

namespace Tests\Samples;

use MetaRush\Perm\PermissionInterface;
use MetaRush\Perm\Request;

class MyCustomRules implements PermissionInterface
{
    // these are just copies from PermTest.php
    private const RESOURCE_DELETE_POST = 'deletePost';
    private const RESOURCE_DELETE_COMMENT = 'deleteComment';
    private const RESOURCE_DELETE_USER = 'deleteUser';

    private Request $request;

    public function hasPermission(Request $request): bool
    {
        $this->request = $request;

        // ------------------------------------------------

        $resourceId = $request->getResourceId();

        if ($resourceId === self::RESOURCE_DELETE_POST)
            return $this->isPostNotYetRead();

        if ($resourceId === self::RESOURCE_DELETE_COMMENT)
            return $this->isCommentNotYetRead();

        if ($resourceId === self::RESOURCE_DELETE_USER)
            return $this->isAtleastOneAdminRemains();

        return false;
    }

    private function isPostNotYetRead(): bool
    {
        // e.g., get the read status of the post (in actual, you will probably do some DB queries)
        $postNotYetRead = true;

        return $postNotYetRead;
    }

    private function isCommentNotYetRead(): bool
    {
        // e.g., get the read status of the post (in actual, you will probably do some DB queries)
        $commentNotYetRead = true;

        return $commentNotYetRead;
    }

    private function isAtleastOneAdminRemains(): bool
    {
        // e.g., get number of admins from user db and subtract 1 admin
        $expectedNumberOfAdminsLeftAfterDeletingUser = 1;

        return ($expectedNumberOfAdminsLeftAfterDeletingUser > 0);
    }

}