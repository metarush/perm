<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use MetaRush\Perm\Perm;
use MetaRush\Perm\Roles;
use MetaRush\Perm\Request;

class PermTest extends TestCase
{
    private const ROLE_ADMIN = 1;
    private const ROLE_MOD = 2;
    private const ROLE_STAFF = 3;
    private const RESOURCE_CREATE_MOD = 'createMod';
    private const RESOURCE_CREATE_USER = 'createUser';
    private const RESOURCE_CREATE_POST = 'createPost';
    private const RESOURCE_EDIT_POST = 'editPost';
    private const RESOURCE_DELETE_POST = 'deletePost';
    private const RESOURCE_DELETE_COMMENT = 'deleteComment';
    private const RESOURCE_DELETE_USER = 'deleteUser';

    private array $userRoles;
    private array $roleResources;
    private array $resourceRestrictions;
    private array $roleRanks;
    private Perm $perm;

    public function setUp(): void
    {
        $this->userRoles = [
            1 => self::ROLE_ADMIN,
            2 => self::ROLE_MOD,
            3 => self::ROLE_STAFF,
            4 => self::ROLE_STAFF,
            5 => self::ROLE_STAFF,
        ];

        $this->roleResources = [
            self::ROLE_ADMIN => [
                self::RESOURCE_CREATE_MOD,
                self::RESOURCE_DELETE_USER,
            ],
            self::ROLE_MOD   => [
                self::RESOURCE_CREATE_USER,
                self::RESOURCE_EDIT_POST,
                self::RESOURCE_DELETE_POST,
                self::RESOURCE_DELETE_COMMENT,
            ],
            self::ROLE_STAFF => [
                self::RESOURCE_CREATE_POST,
            ]
        ];

        $this->resourceRestrictions = [
            self::RESOURCE_CREATE_MOD     => [
                Perm::RESTRICTION_PERMISSION,
            ],
            self::RESOURCE_CREATE_USER    => [
                Perm::RESTRICTION_PERMISSION,
            ],
            self::RESOURCE_CREATE_POST    => [
                Perm::RESTRICTION_PERMISSION,
            ],
            self::RESOURCE_EDIT_POST      => [
                Perm::RESTRICTION_PERMISSION,
                Perm::RESTRICTION_OWNER,
            ],
            self::RESOURCE_DELETE_POST    => [
                Perm::RESTRICTION_PERMISSION,
                Perm::RESTRICTION_CUSTOM_RULE,
            ],
            self::RESOURCE_DELETE_COMMENT => [
                Perm::RESTRICTION_PERMISSION,
                Perm::RESTRICTION_CUSTOM_RULE_AND_OWNER,
            ],
            self::RESOURCE_DELETE_USER    => [
                Perm::RESTRICTION_PERMISSION_AND_CUSTOM_RULE,
            ],
        ];

        // lower number is higher in rank
        $this->roleRanks = [
            self::ROLE_ADMIN => 1,
            self::ROLE_MOD   => 2,
            self::ROLE_STAFF => 3,
        ];

        // ------------------------------------------------

        $roles = new Roles($this->roleResources, $this->roleRanks);
        $this->perm = new Perm($roles, $this->resourceRestrictions);
    }

    public function test_hasPermission_withPermissionRestriction_pass()
    {
        $userId = 2;
        $roleId = $this->userRoles[$userId];
        $resourceId = self::RESOURCE_CREATE_POST;
        $request = new Request($userId, $roleId, $resourceId);

        $expected = true;
        $actual = $this->perm->hasPermission($request);
        $this->assertEquals($expected, $actual);
    }

    public function test_hasPermission_withOwnerRestriction_pass()
    {
        $this->perm->setOwnerFinderFqn(Samples\MyOwnerFinder::class);

        // ------------------------------------------------

        $userId = 5; // actual owner in our fictional db
        $roleId = $this->userRoles[$userId];
        $resourceId = self::RESOURCE_EDIT_POST;
        $request = new Request($userId, $roleId, $resourceId);

        $expected = true;
        $actual = $this->perm->hasPermission($request);
        $this->assertEquals($expected, $actual);

        // ------------------------------------------------

        $userId = 3; // not the actual owner in our fictional db
        $roleId = $this->userRoles[$userId];
        $resourceId = self::RESOURCE_EDIT_POST;
        $request = new Request($userId, $roleId, $resourceId);

        $expected = false;
        $actual = $this->perm->hasPermission($request);
        $this->assertEquals($expected, $actual);
    }

    public function test_hasPermission_withCustomRuleRestriction_pass()
    {
        $this->perm->setCustomRulesFqn(Samples\MyCustomRules::class);

        // ------------------------------------------------

        $userId = 5; // arbitrary user
        $roleId = $this->userRoles[$userId];
        $resourceId = self::RESOURCE_DELETE_POST;
        $request = new Request($userId, $roleId, $resourceId);

        $expected = true;
        $actual = $this->perm->hasPermission($request);
        $this->assertEquals($expected, $actual);
    }

    public function test_hasPermission_withCustomRuleAndOwnerRestriction_pass()
    {
        $this->perm->setOwnerFinderFqn(Samples\MyOwnerFinder::class);
        $this->perm->setCustomRulesFqn(Samples\MyCustomRules::class);

        // ------------------------------------------------

        $userId = 5; // actual owner in our fictional db
        $roleId = $this->userRoles[$userId];
        $resourceId = self::RESOURCE_DELETE_COMMENT;
        $request = new Request($userId, $roleId, $resourceId);

        $expected = true;
        $actual = $this->perm->hasPermission($request);
        $this->assertEquals($expected, $actual);

        // ------------------------------------------------

        $userId = 3; // not the actual owner in our fictional db
        $roleId = $this->userRoles[$userId];
        $resourceId = self::RESOURCE_DELETE_COMMENT;
        $request = new Request($userId, $roleId, $resourceId);

        $expected = false;
        $actual = $this->perm->hasPermission($request);
        $this->assertEquals($expected, $actual);
    }

    public function test_hasPermission_withPermissionAndCustomRuleRestriction_pass()
    {
        $this->perm->setCustomRulesFqn(Samples\MyCustomRules::class);

        // ------------------------------------------------

        $userId = 1;
        $roleId = $this->userRoles[$userId];
        $resourceId = self::RESOURCE_DELETE_USER;
        $request = new Request($userId, $roleId, $resourceId);

        $expected = true;
        $actual = $this->perm->hasPermission($request);
        $this->assertEquals($expected, $actual);
    }

}