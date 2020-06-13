# CHANGELOG

## 2.1.0 - 2020-06-14

### Added

- Added new resource restriction: RESTRICTION_PERMISSION_AND_CUSTOM_RULE

## 2.0.0 - 2020-06-05

### Changed

- Change the type of argument 3 ($resourceId) from int to string in Request class. This is to
accommodate use cases that does not want to explicitly create a "list" of resources with numerical
ids -- as resource names are typically accessible directly from URLs and can already act as an id. This
change, while breaking, still allows the use of numeric ids as long as it is type-casted as string.

## 1.0.0 - 2020-06-03

- Release first version.