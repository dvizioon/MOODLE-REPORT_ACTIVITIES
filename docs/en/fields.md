# Grade fields

[Index](index) · [Português](../pt-br/campos)

## Activity and gradebook relationship

| Field | Description | Origin |
|-------|-------------|--------|
| Raw grade | Value sent by the activity module | Gradebook (`rawgrade`) |
| Activity final grade | Official item value in the gradebook after normalisation and any overrides | Gradebook (`finalgrade`) |
| Percentage | Relative position of the grade between the item minimum and maximum | Calculated by the plugin |
| Grade (0–10) | Percentage equivalent converted to a 0–10 scale | Calculated by the plugin |
| Feedback | Comment associated with the grade | Gradebook |
| Activity name | Grade item name | Gradebook |

The 0–10 grade uses half-up rounding (fractional values of 0.5 or above round to the next integer).

## Grade override

When a teacher overrides a grade in the gradebook:

- The **final grade** field reflects the gradebook value.
- The **raw grade** field keeps the original value sent by the module.

## Course total

In the **Final grade** source, the displayed value is the aggregated course total from the gradebook, obtained through the official Moodle API. It is not the grade of a single activity.

## Course category

The course category field is available from Moodle 4.1. On Moodle 4.0, the other columns remain available as usual.
