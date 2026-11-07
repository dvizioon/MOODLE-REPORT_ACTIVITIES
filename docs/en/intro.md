# Introduction

[Index](index) · [Português](../pt-br/intro)

**Report Activities** adds data sources to Moodle Report Builder. It does not replace or duplicate the native reporting interface: columns, filters, conditions, audiences, schedules and CSV/Excel export remain core Moodle features.

## Available sources

| Source | Granularity | Recommended use |
|--------|-------------|-----------------|
| **Activities** | One row per student and graded activity | Track grades for quizzes, assignments and other graded activities |
| **Final grade** | One row per student and course | Obtain the course total as stored in the gradebook |

## Data flow

1. The student completes a graded activity.
2. The module stores the grade in the Moodle **gradebook**.
3. The plugin reads that data and exposes it as a Report Builder source.

```text
Graded activity
        │
        ▼
Gradebook (items and grades)
        │
        ▼
Report Builder
  (Report Activities sources)
```

## Scope limits

- Does not recalculate course weights, formulas or aggregation; it uses values already stored in the gradebook.
- Does not include activities without a grade item (for example page, file, URL or label).
- Does not replace the course grader report; it is intended for custom reports in Report Builder.

Continue to [Installation](installation).
