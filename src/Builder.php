<?php

declare(strict_types=1);

namespace MetaRush\Perm;

class Builder
{
    /**
     *
     * @var array[]
     */
    private array $roleResources;

    /**
     *
     * @var int[]
     */
    private array $roleRanks;

    /**
     *
     * @var array[]
     */
    private array $resourceRestrictions;
    private ?string $ownerFinderFqn = null;
    private ?string $customRulesFqn = null;

    /**
     *
     * @param array[] $roleResources
     * @return self
     */
    public function setRoleResources(array $roleResources): self
    {
        $this->roleResources = $roleResources;
        return $this;
    }

    /**
     *
     * @param int[] $roleRanks
     * @return self
     */
    public function setRoleRanks(array $roleRanks): self
    {
        $this->roleRanks = $roleRanks;
        return $this;
    }

    /**
     *
     * @param array[] $resourceRestrictions
     * @return self
     */
    public function setResourceRestrictions(array $resourceRestrictions): self
    {
        $this->resourceRestrictions = $resourceRestrictions;
        return $this;
    }

    public function setOwnerFinderFqn(string $ownerFinderFqn): self
    {
        $this->ownerFinderFqn = $ownerFinderFqn;
        return $this;
    }

    public function setCustomRulesFqn(string $customRulesFqn): self
    {
        $this->customRulesFqn = $customRulesFqn;
        return $this;
    }

    public function build(): Perm
    {
        $roles = new Roles($this->roleResources, $this->roleRanks);
        $perm = new Perm($roles, $this->resourceRestrictions);

        if ($this->ownerFinderFqn)
            $perm->setOwnerFinderFqn($this->ownerFinderFqn);

        if ($this->customRulesFqn)
            $perm->setCustomRulesFqn($this->customRulesFqn);

        return $perm;
    }

}