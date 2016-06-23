# Change log
All Notable changes to `allay` will be documented in this file

## 0.2.1 (released 2016-06-23)
- Array transformer doesn't cast scalar values like string and boolean to array anymore.
- Resource `Resolver` can now return the name of a registered model type.
- Resource exceptions now only contains the resource name instead of class name.
- Minor code styling fixes.

## 0.2.0 (released 2015-11-11)
- Added extra relation (controller) actions, to fetch relations.
- Updated `Readable` and `Writable` interfaces to use an existing query builder.
- Expanded `Resolver` interface to include `->getRelationMethod()`.
- Dropped support for PHP 5.5.9+.

## 0.1.2 (released 2015-10-14)
- Rewritten route solving to allow Lumen.

## 0.1.1 (released 2015-10-14)
- Minor bug codestyling fixes.
- Updated readme to include extensions.

## 0.1.0 (released 2015-10-11)
- Core concept realised.
- Added tests.
