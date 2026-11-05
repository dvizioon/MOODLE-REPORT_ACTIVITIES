<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Bridges Moodle 4.0 and 5.x APIs used by this plugin.
 *
 * @package   report_activities
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace report_activities\reportbuilder;

/**
 * Compatibility helpers for Report Builder across Moodle 4.0–5.x.
 */
class compat {
    /**
     * Alias classes that only exist as namespaced types from Moodle 4.2/4.3 onward.
     */
    public static function boot(): void {
        if (!class_exists(\core\lang_string::class) && class_exists(\lang_string::class)) {
            class_alias(\lang_string::class, \core\lang_string::class);
        }
        if (!class_exists(\core\context::class) && class_exists(\context::class)) {
            class_alias(\context::class, \core\context::class);
        }
        if (!class_exists(\core\context\system::class) && class_exists(\context_system::class)) {
            class_alias(\context_system::class, \core\context\system::class);
        }
        if (!class_exists(\core\context\course::class) && class_exists(\context_course::class)) {
            class_alias(\context_course::class, \core\context\course::class);
        }
    }

    /**
     * Reuse a table alias already joined by another entity.
     *
     * Moodle 4.4+ has set_table_join_alias(). Moodle 4.0–4.3 only has set_table_alias().
     *
     * @param \core_reportbuilder\local\entities\base $entity
     * @param string $table
     * @param string $alias
     */
    public static function share_table_alias($entity, string $table, string $alias): void {
        if (method_exists($entity, 'set_table_join_alias')) {
            $entity->set_table_join_alias($table, $alias);
            return;
        }
        $entity->set_table_alias($table, $alias);
    }

    /**
     * Course category report entity exists from Moodle 4.1.
     *
     * @return class-string|null
     */
    public static function course_category_class(): ?string {
        global $CFG;
        $relative = '/course/classes/reportbuilder/local/entities/course_category.php';
        foreach ([$CFG->dirroot, $CFG->dirroot . '/public'] as $root) {
            if (is_readable($root . $relative)) {
                return \core_course\reportbuilder\local\entities\course_category::class;
            }
        }
        return null;
    }
}
