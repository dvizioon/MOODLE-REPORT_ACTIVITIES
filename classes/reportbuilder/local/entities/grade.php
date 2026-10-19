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

use context_course;
use core\lang_string;
use core_reportbuilder\local\entities\base;
use core_reportbuilder\local\filters\{date, number, text};
use core_reportbuilder\local\helpers\format;
use core_reportbuilder\local\report\{column, filter};
use stdClass;

/**
 * Grade entity for the Activities report source.
 *
 * @package   report_activities
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class grade extends base {
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

        // Final grade (as stored in gradebook).
        $columns[] = (new column(
            'finalgrade',
            new lang_string('finalgrade', 'report_activities'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_FLOAT)
            ->add_field("{$grade}.finalgrade")
            ->set_is_sortable(true)
            ->add_callback(static function (?float $value): string {
                return $value === null ? '' : format_float($value, 2);
            });

        // Raw grade.
        $columns[] = (new column(
            'rawgrade',
            new lang_string('rawgrade', 'report_activities'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_FLOAT)
            ->add_field("{$grade}.rawgrade")
            ->set_is_sortable(true)
            ->add_callback(static function (?float $value): string {
                return $value === null ? '' : format_float($value, 2);
            });

        // Percentage of achievement between grademin and grademax (single SQL field).
        $percentagesql = "CASE
                WHEN {$grade}.finalgrade IS NULL THEN NULL
                WHEN {$gradeitem}.grademax <= {$gradeitem}.grademin THEN NULL
                ELSE (({$grade}.finalgrade - {$gradeitem}.grademin)
                    / ({$gradeitem}.grademax - {$gradeitem}.grademin)) * 100
            END";
        $columns[] = (new column(
            'percentage',
            new lang_string('percentage', 'report_activities'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_FLOAT)
            ->add_field($percentagesql, 'percentage')
            ->set_is_sortable(true)
            ->add_callback(static function (?float $value): string {
                return $value === null ? '' : format_float($value, 0) . '%';
            });

        // Grade normalised to 0–10 and rounded in SQL (half up via ROUND).
        $outof10sql = "CASE
                WHEN {$grade}.finalgrade IS NULL THEN NULL
                WHEN {$gradeitem}.grademax <= {$gradeitem}.grademin THEN NULL
                ELSE ROUND((({$grade}.finalgrade - {$gradeitem}.grademin)
                    / ({$gradeitem}.grademax - {$gradeitem}.grademin)) * 10)
            END";
        $columns[] = (new column(
            'gradeoutof10',
            new lang_string('gradeoutof10', 'report_activities'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_INTEGER)
            ->add_field($outof10sql, 'gradeoutof10')
            ->set_is_sortable(true)
            ->add_callback(static function ($value): string {
                return $value === null || $value === '' ? '' : (string) (int) $value;
            });

        // Feedback (HTML from graders — format_text + filters / purifier).
        $columns[] = (new column(
            'feedback',
            new lang_string('feedback', 'report_activities'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_LONGTEXT)
            ->add_field("{$grade}.feedback")
            ->add_field("{$grade}.feedbackformat")
            ->add_field("{$gradeitem}.courseid", 'courseid')
            ->set_is_sortable(false)
            ->add_callback(static function (?string $value, stdClass $row): string {
                if ($value === null || $value === '') {
                    return '';
                }
                $format = (int) ($row->feedbackformat ?? FORMAT_MOODLE);
                $courseid = (int) ($row->courseid ?? 0);
                $options = [];
                if ($courseid > 0) {
                    $options['context'] = context_course::instance($courseid);
                }
                return format_text($value, $format, $options);
            });

        // Time modified.
        $columns[] = (new column(
            'timemodified',
            new lang_string('timemodified', 'report_activities'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_TIMESTAMP)
            ->add_field("{$grade}.timemodified")
            ->set_is_sortable(true)
            ->add_callback([format::class, 'userdate']);

        return $columns;
    }

    /**
     * Return list of all available filters.
     *
     * @return filter[]
     */
    protected function get_available_filters(): array {
        [
            'grade_grades' => $grade,
            'grade_items' => $gradeitem,
        ] = $this->get_table_aliases();

        // Final grade.
        $filters[] = (new filter(
            number::class,
            'finalgrade',
            new lang_string('finalgrade', 'report_activities'),
            $this->get_entity_name(),
            "{$grade}.finalgrade"
        ))
            ->add_joins($this->get_joins());

        // Percentage (computed).
        $percentagesql = "
            CASE
                WHEN {$grade}.finalgrade IS NULL THEN NULL
                WHEN {$gradeitem}.grademax <= {$gradeitem}.grademin THEN NULL
                ELSE (({$grade}.finalgrade - {$gradeitem}.grademin)
                    / ({$gradeitem}.grademax - {$gradeitem}.grademin)) * 100
            END
        ";
        $filters[] = (new filter(
            number::class,
            'percentage',
            new lang_string('percentage', 'report_activities'),
            $this->get_entity_name(),
            $percentagesql
        ))
            ->add_joins($this->get_joins());

        // Feedback.
        $filters[] = (new filter(
            text::class,
            'feedback',
            new lang_string('feedback', 'report_activities'),
            $this->get_entity_name(),
            "{$grade}.feedback"
        ))
            ->add_joins($this->get_joins());

        // Time modified.
        $filters[] = (new filter(
            date::class,
            'timemodified',
            new lang_string('timemodified', 'report_activities'),
            $this->get_entity_name(),
            "{$grade}.timemodified"
        ))
            ->add_joins($this->get_joins())
            ->set_limited_operators([
                date::DATE_ANY,
                date::DATE_NOT_EMPTY,
                date::DATE_EMPTY,
                date::DATE_RANGE,
                date::DATE_LAST,
                date::DATE_CURRENT,
            ]);

        return $filters;
    }
}
