# Installation

[Index](index) · [Português](../pt-br/instalacao)

## Requirements

| Item | Value |
|------|-------|
| Plugin version | 1.3.8 |
| Build number | 2026110700 |
| Moodle | 4.0 to 5.2 |
| Maturity | Stable |

See also [Compatibility](compatibility).

## Install path

| Moodle version | Directory |
|----------------|-----------|
| 4.x | `report/activities/` |
| 5.1 or later | `public/report/activities/` |

The directory name must be `activities`. The Frankenstyle component name is `report_activities`.

## Procedure

1. Download the package from the [Moodle Marketplace](https://moodle.org/plugins/) or from the [GitHub releases page](https://github.com/dvizioon/MOODLE-REPORT-ACTIVITIES/releases).
2. Extract the archive and copy the `activities` folder to the path above.
3. Open **Site administration → Notifications** and complete the site upgrade.

Command-line alternative, from the Moodle root:

```bash
php admin/cli/upgrade.php
```

On Moodle 5.1 or later:

```bash
php public/admin/cli/upgrade.php
```

## After installation

1. Open **Site administration → Reports → Report builder**.
2. Create a new custom report.
3. Select the **Activities** or **Final grade** source.

From version 1.3.8 there is no dedicated entry under Site administration → Reports. Access is only through Report Builder.

## Capability

The capability `report/activities:view` is required at system context. By default, managers and course creators have this capability.

Usage guide: [How to use](usage).
