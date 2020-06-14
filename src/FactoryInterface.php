<?php

namespace MetaRush\Perm;

interface FactoryInterface
{
    public static function getInstance(): PermissionInterface;
}