# Changelog

All notable changes to `sentry-dashboard-tile` will be documented in this file

## 3.0.1 - 2021-03-12

**Updated:**

- Removed sleep from `SentryTileServiceProvider`

## 3.0.0 - 2021-02-26

**Added:**

- Added autoloading for livewire components.
- Moved the Livewire Http\Livewire
- Added team component

**Updated:**

- Updated readme.

## 2.0.1 - 2021-02-16

**Fixed:**

- Parsed the datetimes before importing
- Added the correct casts for the `first_seen` and `last_seen` columns on the `Issue` model.

## 2.0.0 - 2021-02-15

**Added:**

- Extra information for GH actions.
- Added new migration for sentry teams.
- Fixtures for the tests.
- Traits for the test suite.
- Added extra tests.

**Updated:**
- The way how migrations are being published.
- The tests.

## 1.0.1 - 2020-12-12

**Added:**

- Added new information in the readme.
- migration stub publish command.
- dashboard.php config documentation.
- Kernel.php schedule documentation.

## 1.0.0 - 2020-12-11

- initial release
