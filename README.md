# Activities reports (`report_activities`)

Report Builder data sources for **Moodle 5.2**: activity grades and course final grades from the gradebook.

| | |
|---|---|
| **Plugin type** | `report` |
| **Component** | `report_activities` |
| **Requires** | Moodle 5.2 (`2026042000`) |
| **License** | [GPL-3.0-or-later](LICENSE) |
| **Repository** | Prefer the name `moodle-report_activities` ([Moodle coding style](https://moodle.atlassian.net/wiki/external/ODRhYjAyNTY4NDVmNGJlNjljN2ViMzkwYzdmYmIwMGI#Code-repository-name)) |

Portuguese documentation: [README.pt_br.md](README.pt_br.md)

---

## What it does

This plugin does **not** add a separate report UI. It registers Report Builder sources under Site administration → Reports → Report builder (`/reportbuilder/index.php`).

| Source (UI) | Class | One row means |
|-------------|--------|----------------|
| **Activities** | `report_activities\reportbuilder\datasource\activities` | User × graded activity (`grade_items.itemtype = mod`) |
| **Final grade** | `report_activities\reportbuilder\datasource\coursefinal` | User × course total (`itemtype = course`), via `grade_get_course_grades()` |

Columns, filters, audiences, schedules and CSV/Excel export are core Report Builder features. The plugin only supplies the data sources.

---

## Entities / fields

| Group | Fields |
|-------|--------|
| **User** | Core user entity (name, email, profile fields, …) |
| **Course** | Core course entity |
| **Course category** | Core course category entity |
| **Activity** | Name (`format_string`), type, grade min/max |
| **Grade** | Raw grade, final grade, percentage, grade 0–10, feedback (`format_text` + `feedbackformat`), last modified |

### Activity grade vs course final grade

| Report field | Meaning | Moodle storage |
|--------------|---------|----------------|
| **Raw grade** | Value sent by the activity module | `grade_grades.rawgrade` |
| **Final grade** (Activities source) | Official gradebook value for that activity item | `grade_grades.finalgrade` on `itemtype = mod` |
| **Final grade** (Final grade source) | Course total with weights / aggregation already applied | Same as grader report “Course total”, via `grade_get_course_grades()` |
| **Percentage** / **Grade (0–10)** | Normalised from min/max of the grade item | Computed |

---

## Capabilities

| Capability | Purpose |
|------------|---------|
| `report/activities:view` | Access the plugin entry page and the admin link under Reports |

Managers and course creators get this capability by default. Opening `/report/activities/index.php` requires it, then redirects to Report Builder.

Creating and viewing custom reports still uses core Report Builder capabilities (`moodle/reportbuilder:*`).

---

## Installation

1. Place the plugin in `report/activities/` (folder name must be `activities`).
2. Visit **Site administration → Notifications** and complete the upgrade.
3. Open **Site administration → Reports → Report builder** (or **Reports → Activities reports**).
4. Create a report and choose source **Activities** or **Final grade**.

### Release archive (no `.git`)

Do **not** zip the working tree (that ships `.git`). From the plugin repository:

```bash
git archive --format=zip --prefix=activities/ -o report_activities.zip HEAD
```

Install by extracting so that Moodle sees `report/activities/version.php`.

Screenshots under `screenshots/` are for documentation only (`export-ignore` in `.gitattributes`).

---

## Tests

```bash
vendor/bin/phpunit public/report/activities/tests/reportbuilder/datasource/activities_test.php
vendor/bin/phpunit public/report/activities/tests/reportbuilder/datasource/coursefinal_test.php
```

Behat feature: `tests/behat/activities_report.feature`.

---

## Privacy

The plugin does not store personal data. It reads existing gradebook and user records for reporting. See `classes/privacy/provider.php`.

---

## Support

- Issues: use the project issue tracker on the plugin repository
- Contact: danielmartinsjob@gmail.com
