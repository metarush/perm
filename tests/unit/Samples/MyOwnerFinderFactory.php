<?php

namespace Tests\Samples;

use MetaRush\Perm\PermissionInterface;
use MetaRush\Perm\FactoryInterface;

class MyOwnerFinderFactory implements FactoryInterface
{
    public static function getInstance(): PermissionInterface
    {
        return new MyOwnerFinder();
    }

}