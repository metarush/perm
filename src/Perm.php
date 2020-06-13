<?php

declare(strict_types=1);

namespace MetaRush\Perm;

class Perm
{
    public const RESTRICTION_PERMISSION = 'permission';
    public const RESTRICTION_CUSTOM_RULE = 'customRule';
    public const RESTRICTION_OWNER = 'owner';
    public const RESTRICTION_PERMISSION_AND_CUSTOM_RULE = 'permissionAndCustomRule';
    public const RESTRICTION_CUSTOM_RULE_AND_OWNER = 'customRuleAndOwner';

    private PermissionInterface $roles;

    /**
     * Array where each key is a resourceId and its value is an array of self::RESTRICTIONS_*
     *
     * @var array[]
     */
    private array $restrictions;
    private string $ownerFinderFqn;
    private string $customRulesFqn;

    /**
     *
     * @param PermissionInterface $roles
     * @param array[] $restrictions
     */
    public function __construct(PermissionInterface $roles, array $restrictions)
    {
        $this->roles = $roles;
        $this->restrictions = $restrictions;
    }

    public function hasPermission(Request $request): bool
    {
        $restrictions = $this->getRestrictions($request->getResourceId());

        $votes = 0;

        foreach ($restrictions as $v) {

            if ($v == self::RESTRICTION_PERMISSION && $this->roles->hasPermission($request))
                $votes++;

            if ($v == self::RESTRICTION_CUSTOM_RULE && $this->hasCustomRulePermission($request))
                $votes++;

            if ($v == self::RESTRICTION_OWNER && $this->hasOwnerPermission($request))
                $votes++;

            if ($v == self::RESTRICTION_PERMISSION_AND_CUSTOM_RULE && $this->roles->hasPermission($request) && $this->hasCustomRulePermission($request))
                $votes++;

            if ($v == self::RESTRICTION_CUSTOM_RULE_AND_OWNER && $this->hasCustomRulePermission($request) && $this->hasOwnerPermission($request))
                $votes++;
        }

        return $votes > 0;
    }

    /**
     * Set the FQN of userland owner finder class
     *
     * @param string $ownerFinderFqn
     * @return void
     */
    public function setOwnerFinderFqn(string $ownerFinderFqn): void
    {
        $this->ownerFinderFqn = $ownerFinderFqn;
    }

    /**
     * Set the FQN of userland custom rules class
     *
     * @param string $customRulesFqn
     * @return void
     */
    public function setCustomRulesFqn(string $customRulesFqn): void
    {
        $this->customRulesFqn = $customRulesFqn;
    }

    private function hasCustomRulePermission(Request $request): bool
    {
        $customRuleFqn = $this->customRulesFqn;

        /* @var $customRule PermissionInterface  */
        $customRule = new $customRuleFqn;

        return $customRule->hasPermission($request);
    }

    private function hasOwnerPermission(Request $request): bool
    {
        $ownerFinderFqn = $this->ownerFinderFqn;

        /* @var $ownerFinder PermissionInterface  */
        $ownerFinder = new $ownerFinderFqn;

        return $ownerFinder->hasPermission($request);
    }

    /**
     *
     * @param string $resourceId
     * @return array[]
     */
    private function getRestrictions(string $resourceId): array
    {
        return $this->restrictions[$resourceId];
    }

}