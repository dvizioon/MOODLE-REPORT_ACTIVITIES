# How to use

## Create a report

1. Open **Site administration → Reports → Report builder → Custom reports**.
2. Select **New report**.
3. Choose the **Activities** or **Final grade** source.
4. Configure columns, filters and conditions.
5. Set access (audience) and, if needed, schedules and export.

![Conditions panel](../../../screenshots/create-conditions.png)

*Conditions in the report editor*

![Access tab](../../../screenshots/access-report.png)

*Report access control*

![Card view](../../../screenshots/grid-view.png)

*Results in card view*

## Default columns for Activities

When you create a report with the **Activities** source, the plugin suggests these columns:

1. User full name (with link)
2. Course full name (with link)
3. Activity name
4. Activity type
5. Percentage
6. Grade on a 0–10 scale
7. Activity final grade

Columns can be changed at any time in the report editor.

## Filters and conditions

Use Report Builder filters and conditions to restrict the dataset (by course, user, activity type and other available fields).

## Capability

Without `report/activities:view`, the plugin sources are not shown in the selector.

## Plugin entry page

The plugin `index.php` requires authentication and the capability above, then redirects to Report Builder. The usual flow for creating and editing reports remains in the Report Builder admin menu.
