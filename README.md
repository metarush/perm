# metarush/perm

`Perm` allows you to implement role-based access control (RBAC) functionality in your app with optional custom rules (like ABAC).

## Install

Install via composer as `metarush/perm`

## Usage

`Perm` expects you to provide the following arrays:

- **$roleRanks** hierarchy of roles
- **$roleResources** list of resources roles has access to
- **$resourceRestrictions** list of restrictions resources have that must validate before access is granted

---

`array` **$roleRanks**

An array where keys represent roleIds while the values represent their hierarchy. Example:

```php
$roleRanks = [
    1 => 1,
    2 => 2,
    3 => 3
]
```

Keys `1`,`2`,`3` represent roleIds in your app. This could mean that `1` represents an admin, `2` a moderator, `3` a staff.

Values `1`,`2`,`3` represent their hierarchy. **Lower value means a higher rank**.

Note: Resources of a lower role are inherited by higher roles. E.g., Resources of a role with rank `3` are inherited by roles with a rank `2` and `1`.

---

`array` **$roleResources**

An array where keys represent roleIds while values represent an array of resourceIds the roleId has access to. Example:

```php
$roleResources = [
    1 => ['1','2'],
    2 => ['3','4'],
    3 => ['5','6']
];
```

Keys `1`,`2`,`3` represent roleIds in your app. This could mean that `1` represents an admin, `2` a moderator, `3` a staff.

Values `['1','2']`, `['3','4']`, `['5','6']` represent resourceIds in your app. This could mean that `1` represents "create user", `2` "edit user", `3` "create post", and so on.

You may notice that the values are strings. This is so you could literally use strings like 'createUser' as it can also act as identifiers. Doing this, you may skip explicitly creating a list of numerical resourceIds, as long as your app can handle it.

Alternative example:

```php
$roleResources = [
    1 => ['createUser', 'editUser'],
    2 => ['createPost','editPost'],
    3 => ['readPost', 'createComment']
];
```

`Perm` does not care how you name your roles or resources.

---

`array` **$resourceRestrictions**

An array where keys represent resourceIds while values represent an array of restrictions that are allowed. Restrictions in `Perm` are represented by the following public constants:

`Perm::RESTRICTION_PERMISSION`

Allow if the role has an explicit permission. E.g., "moderator" role has "create user" permission.

`Perm::RESTRICTION_CUSTOM_RULE`

Allow if the custom rule is met

`Perm::RESTRICTION_OWNER`

Allow if the requesting user is the owner of the resource

`Perm::RESTRICTION_CUSTOM_RULE_AND_OWNER`

Allow if the custom rule is met **and** the requesting user is the owner of the resource

`Perm::RESTRICTION_PERMISSION_AND_CUSTOM`

Allow if the role has explicit permission **and** if the custom rule is met

Example:

```php
$resourceRestrictions = [
    '1' => [
        Perm::RESTRICTION_PERMISSION
    ],
    '2' => [
        Perm::RESTRICTION_PERMISSION,
        Perm::RESTRICTION_OWNER
    ],
    '3' => [
        Perm::RESTRICTION_CUSTOM_RULE
    ],
    '4' => [
        Perm::RESTRICTION_CUSTOM_RULE_AND_OWNER
    ]
    '5' => [
        Perm::RESTRICTION_PERMISSION_AND_CUSTOM_RULE
    ]
];
```

Keys `1`,`2`,`3`,`4`, `5` represent resourceIds in your app. This could mean `1` represents "create user", `2` "edit post", `3` "delete post", `4` "delete comment", `5` "delete user".

Values represent their restrictions that `Perm` understands. This is how `Perm` will interpret the abve example:

- resourceId `1` or "create user" resource, is allowed if the role of the requesting user has an explict "create user" permission.

- resourceId `2` or "edit post" resource, is allowed if the role of the requesting user has an explict "edit post" permission, **or**, if the requesting user is the owner of the resource

- resourceId `3` or "delete post" resource, is allowed if its custom rule is met. E.g., any rule you set like "post can be deleted if x". The rule is customizable in your own code as long as it returns a boolean value.

- resourceId `4` or "delete comment" resource, is allowed if its custom rule is met **and** if the requesting user is the owner of the resource.

- resourceId `5` or "delete user" resource, is allowed if the role has explicit permission **and** if its custom rule is met

As you can see in resourceId `2`, you can combine restrictions as you wish.

**When using combined restrictions, only one restriction that validates is required to grant access.** E.g., In resourceId `2`, access is granted if the user has explicit permission for resourceId `2` **or** if its custom rule validates.

---

### Optional custom classes

`PermissionInterface` **$ownerFinderFqn**

This custom class that you create will be used as the owner finder for a given resource.

Required by:

- `Perm::RESTRICTION_OWNER`

- `Perm::RESTRICTION_CUSTOM_RULE_AND_OWNER`

Use the `->setOwnerFinderFqn($ownerFinderFqn)` method in the builder to apply this custom class.

`PermissionInterface` **$customRulesFqn**

This custom class that you create will be used as the custom rule handler for a given resource.

Required by:

- `Perm::RESTRICTION_CUSTOM_RULE`

- `Perm::RESTRICTION_CUSTOM_RULE_AND_OWNER`

- `Perm::RESTRICTION_PERMISSION_AND_CUSTOM_RULE`

Use the `->setCustomRulesFqn($customRulesFqn)` method in the builder to apply this custom class.

For an example on how to implement these custom classes, see the `tests/unit/Samples` folder.

---

## Init the library

In your middleware, controller, or top of your app:

```php
use MetaRush\Perm;

$perm = (new Perm\Builder)
    ->setRoleRanks($roleRanks)
    ->setRoleResources($roleResources)
    ->setResourceRestrictions($resourceRestrictions)
    ->setOwnerFinderFqn(Perm\Samples\MyOwnerFinder::class) // optional
    ->setCustomRulesFqn(Perm\Samples\MyCustomRules::class) // optional
    ->build();
```

Create a `Request` object

```php
$userId = 5; // userId of the requesting user
$roleId = 3; // roleId of $userId e.g., "staff"
$resourceId = 'addUser'; // resourceId of the requested resource
$request = new Perm\Request($userId, $roleId, $resourceId);
```

The above values are arbitrary data depending to your app.

Pass the `Request` object to `Perm'`s `hasPermission()`method:

```php
if (!$perm->hasPermission($request))
    exit('Access denied');

// access allowed, continue with the app...
```

That's the idea on how to use `Perm`. The CRUD functions, or database of users, roles , and resources, are not included in this library as they are best implemented in userland -- since they are typically unique per project. The important thing to remember is to make them compatible with the three arrays required by `Perm` as discussed previously:

- **$roleRanks**
- **$roleResources**
- **$resourceRestrictions**