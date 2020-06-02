<?php

namespace MetaRush\Perm;

interface PermissionInterface
{
    public function hasPermission(Request $request): bool;
}