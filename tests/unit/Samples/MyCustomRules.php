<?php

declare(strict_types=1);

namespace Tests\Samples;

use MetaRush\Perm\PermissionInterface;
use MetaRush\Perm\Request;

class MyCustomRules implements PermissionInterface
{
    // these are just copies from PermTest.php
    private const RESOURCE_DELETE_POST = 5;
    private const RESOURCE_DELETE_COMMENT = 6;

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

}