# CHANGELOG

## 3.0.1 - 2022-07-29

### Fixed

- Update laminas/diactoros to 2.14 due to security advisory https://github.com/laminas/laminas-diactoros/security/advisories/GHSA-8274-h5jp-97vr

## 3.0.0 - 2020-06-15

### Changed

- Custom classes for owner finder and custom rules must now be instantiated from factories. This is
so dependency injection can be implemented in user land properly and also have lazy-loading as
added benefit.

### Added

- Add option to include PSR-7 ServerRequestInterface object in Perm's Request object

## 2.1.0 - 2020-06-14

### Added

- Add new resource restriction: RESTRICTION_PERMISSION_AND_CUSTOM_RULE.

## 2.0.0 - 2020-06-05

### Changed

- Change the type of argument 3 ($resourceId) from int to string in Request class. This is to
accommodate use cases that does not want to explicitly create a "list" of resources with numerical
ids -- as resource names are typically accessible directly from URLs and can already act as an id. This
change, while breaking, still allows the use of numeric ids as long as it is type-casted as string.

## 1.0.0 - 2020-06-03

- Release first version.