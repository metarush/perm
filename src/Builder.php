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
    private ?string $ownerFinderFactoryFqn = null;
    private ?string $customRulesFactoryFqn = null;

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

    public function setOwnerFinderFactoryFqn(string $ownerFinderFactoryFqn): self
    {
        $this->ownerFinderFactoryFqn = $ownerFinderFactoryFqn;
        return $this;
    }

    public function setCustomRulesFactoryFqn(string $customRulesFactoryFqn): self
    {
        $this->customRulesFactoryFqn = $customRulesFactoryFqn;
        return $this;
    }

    public function build(): Perm
    {
        $roles = new Roles($this->roleResources, $this->roleRanks);
        $perm = new Perm($roles, $this->resourceRestrictions);

        if ($this->ownerFinderFactoryFqn)
            $perm->setOwnerFinderFactoryFqn($this->ownerFinderFactoryFqn);

        if ($this->customRulesFactoryFqn)
            $perm->setCustomRulesFactoryFqn($this->customRulesFactoryFqn);

        return $perm;
    }

}