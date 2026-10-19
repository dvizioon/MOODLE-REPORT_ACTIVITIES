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

declare(strict_types=1);

namespace report_activities\reportbuilder\local\entities;

use core\lang_string;
use core_reportbuilder\local\entities\base;
use core_reportbuilder\local\filters\number;
use core_reportbuilder\local\report\{column, filter};
use stdClass;

/**
 * Course total from the gradebook. The number is grade_get_course_grades(), weights already applied.
 *
 * @package   report_activities
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_total extends base {
    /**
     * Database tables that this entity uses.
     *
     * @return string[]
     */
    protected function get_default_tables(): array {
        return [
            'grade_grades',
            'grade_items',
        ];
    }

    /**
     * The default title for this entity.
     *
     * @return lang_string
     */
    protected function get_default_entity_title(): lang_string {
        return new lang_string('entitygrade', 'report_activities');
    }

    /**
     * Returns list of all available columns.
     *
     * @return column[]
     */
    protected function get_available_columns(): array {
        [
            'grade_grades' => $grade,
            'grade_items' => $gradeitem,
        ] = $this->get_table_aliases();

        // SQL only identifies the row. The shown number is the official course total.
        $columns[] = (new column(
            'finalgrade',
            new lang_string('finalgrade', 'report_activities'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_FLOAT)
            ->add_field("{$grade}.finalgrade")
            ->add_field("{$grade}.userid", 'userid')
            ->add_field("{$gradeitem}.courseid", 'courseid')
            ->set_is_sortable(true)
            ->add_callback(static function ($value, stdClass $row): string {
                global $CFG;
                require_once("{$CFG->dirroot}/grade/querylib.php");

                $userid = (int) ($row->userid ?? 0);
                $courseid = (int) ($row->courseid ?? 0);
                $stored = ($value === null || $value === '') ? '' : format_float((float) $value, 2);

                if ($userid === 0 || $courseid === 0) {
                    return $stored;
                }

                $item = grade_get_course_grades($courseid, $userid);
                $coursegrade = $item->grades[$userid]->grade ?? null;
                if ($coursegrade === null || $coursegrade === false || $coursegrade === '') {
                    // Prefer the official API; fall back to the stored gradebook value.
                    return $stored;
                }
                return format_float((float) $coursegrade, 2);
            });

        return $columns;
    }

    /**
     * Return list of all available filters.
     *
     * @return filter[]
     */
    protected function get_available_filters(): array {
        $grade = $this->get_table_alias('grade_grades');

        $filters[] = (new filter(
            number::class,
            'finalgrade',
            new lang_string('finalgrade', 'report_activities'),
            $this->get_entity_name(),
            "{$grade}.finalgrade"
        ))
            ->add_joins($this->get_joins());

        return $filters;
    }
}
