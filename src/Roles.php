<?php

namespace MetaRush\Perm;

class Roles implements PermissionInterface
{
    /**
     * Array where each key is a roleId and its value is an array of resourceIds
     *
     * @var array[]
     */
    private array $roleResources;

    /**
     * Array where each key is a roleId and its value is an int equal to its rank
     * Note: the lower the value to higher it is in the rank hierarchy
     *
     * @var int[]
     */
    private array $roleRanks;

    /**
     *
     * @param array[] $roleResources
     * @param int[] $roleRanks
     */
    public function __construct(array $roleResources, array $roleRanks)
    {
        $this->roleResources = $roleResources;
        $this->roleRanks = $roleRanks;
    }

    public function hasPermission(Request $request): bool
    {
        $allowedResources = $this->getAllowedResources($request->getRoleId());

        return \in_array($request->getResourceId(), $allowedResources);
    }

    /**
     * Get array of resourceIds that $roleId has access to
     *
     * @param int $roleId
     * @return int[]
     */
    private function getAllowedResources(int $roleId): array
    {
        $roleRank = $this->roleRanks[$roleId];

        // ------------------------------------------------

        $sortableRoleRanks = $this->roleRanks;
        \rsort($sortableRoleRanks);
        $lastRoleRank = $sortableRoleRanks[0];

        // ------------------------------------------------

        $roleResourcesByRank = [];
        foreach ($this->roleResources as $k => $v)
            $roleResourcesByRank[$this->roleRanks[$k]] = $v;

        // ------------------------------------------------

        $allowedResources = [];
        for ($i = $roleRank; $i <= $lastRoleRank; $i++)
            foreach ($roleResourcesByRank[$i] as $v)
                $allowedResources[] = $v;

        return $allowedResources;
    }

}