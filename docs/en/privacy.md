# Privacy

[Index](index) · [Português](../pt-br/privacidade)

## Statement

The plugin uses a null privacy provider.

Implications:

- It does not create its own tables with personal data
- It does not store copies of users, courses or grades
- It only reads data already present in Moodle (users, courses and the gradebook) through Report Builder

## Access control

Source visibility depends on the capability `report/activities:view`. Without that permission, the sources are not shown to the user.

## Compliance

For site policies (LGPD, GDPR or equivalents), treat this plugin as a **presentation** component for data already maintained by Moodle, subject to the same role, retention and access rules as the site.
