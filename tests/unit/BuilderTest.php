<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use MetaRush\Perm\Perm;
use MetaRush\Perm\Builder;

class BuilderTest extends TestCase
{
    private const ROLE_ADMIN = 1;
    private const ROLE_MOD = 2;
    private const ROLE_STAFF = 3;
    private const RESOURCE_CREATE_MOD = 1;
    private const RESOURCE_CREATE_USER = 2;
    private const RESOURCE_CREATE_POST = 3;

    public function test_build_validRequest_returnPermObject()
    {
        $roleResources = [
            self::ROLE_ADMIN => [
                self::RESOURCE_CREATE_MOD,
            ],
            self::ROLE_MOD   => [
                self::RESOURCE_CREATE_USER,
            ],
            self::ROLE_STAFF => [
                self::RESOURCE_CREATE_POST,
            ]
        ];

        $roleRanks = [
            self::ROLE_ADMIN => 1,
            self::ROLE_MOD   => 2,
            self::ROLE_STAFF => 3,
        ];

        $resourceRestrictions = [
            self::RESOURCE_CREATE_MOD  => [
                Perm::RESTRICTION_PERMISSION,
            ],
            self::RESOURCE_CREATE_USER => [
                Perm::RESTRICTION_PERMISSION,
            ],
            self::RESOURCE_CREATE_POST => [
                Perm::RESTRICTION_PERMISSION,
            ],
        ];

        // ------------------------------------------------

        $expected = Perm::class;

        $actual = (new Builder)
            ->setRoleResources($roleResources)
            ->setRoleRanks($roleRanks)
            ->setResourceRestrictions($resourceRestrictions)
            ->setCustomRulesFactoryFqn(Samples\MyCustomRulesFactory::class)
            ->setOwnerFinderFactoryFqn(Samples\MyCustomRulesFactory::class)
            ->build();

        $this->assertInstanceOf($expected, $actual);
    }

}