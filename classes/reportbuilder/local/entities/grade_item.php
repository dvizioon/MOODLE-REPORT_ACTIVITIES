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
use core_reportbuilder\local\filters\{number, select, text};
use core_reportbuilder\local\report\{column, filter};
use stdClass;

/**
 * Grade item (activity) entity for the Activities report source.
 *
 * @package   report_activities
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class grade_item extends base {

    /**
     * Database tables that this entity uses.
     *
     * @return string[]
     */
    protected function get_default_tables(): array {
        return [
            'grade_items',
        ];
    }

    /**
     * The default title for this entity.
     *
     * @return lang_string
     */
    protected function get_default_entity_title(): lang_string {
        return new lang_string('entitygradeitem', 'report_activities');
    }

    /**
     * Returns list of all available columns.
     *
     * @return column[]
     */
    protected function get_available_columns(): array {
        $gradeitem = $this->get_table_alias('grade_items');

        // Activity name (multilang + filters via format_string).
        $columns[] = (new column(
            'name',
            new lang_string('activityname', 'report_activities'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_TEXT)
            ->add_field("{$gradeitem}.itemname")
            ->add_field("{$gradeitem}.courseid", 'courseid')
            ->set_is_sortable(true)
            ->add_callback(static function(?string $name, stdClass $row): string {
                if ($name === null || $name === '') {
                    return '';
                }
                $courseid = (int) ($row->courseid ?? 0);
                $options = ['context' => context_course::instance(SITEID)];
                if ($courseid > 0) {
                    $options['context'] = context_course::instance($courseid);
                }
                return format_string($name, true, $options);
            });

        // Activity type (module name, e.g. quiz / assign).
        $columns[] = (new column(
            'type',
            new lang_string('activitytype', 'report_activities'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_TEXT)
            ->add_field("{$gradeitem}.itemmodule")
            ->set_is_sortable(true)
            ->add_callback(static function(?string $module): string {
                if ($module === null || $module === '') {
                    return '';
                }
                $stringid = 'modulename';
                $component = 'mod_' . $module;
                if (get_string_manager()->string_exists($stringid, $component)) {
                    return get_string($stringid, $component);
                }
                return $module;
            });

        // Maximum grade.
        $columns[] = (new column(
            'grademax',
            new lang_string('grademax', 'report_activities'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_FLOAT)
            ->add_field("{$gradeitem}.grademax")
            ->set_is_sortable(true)
            ->add_callback(static function(?float $value): string {
                return $value === null ? '' : format_float($value, 2);
            });

        // Minimum grade.
        $columns[] = (new column(
            'grademin',
            new lang_string('grademin', 'report_activities'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_FLOAT)
            ->add_field("{$gradeitem}.grademin")
            ->set_is_sortable(true)
            ->add_callback(static function(?float $value): string {
                return $value === null ? '' : format_float($value, 2);
            });

        return $columns;
    }

    /**
     * Return list of all available filters.
     *
     * @return filter[]
     */
    protected function get_available_filters(): array {
        $gradeitem = $this->get_table_alias('grade_items');

        // Activity name.
        $filters[] = (new filter(
            text::class,
            'name',
            new lang_string('activityname', 'report_activities'),
            $this->get_entity_name(),
            "{$gradeitem}.itemname"
        ))
            ->add_joins($this->get_joins());

        // Activity type.
        $filters[] = (new filter(
            select::class,
            'type',
            new lang_string('activitytype', 'report_activities'),
            $this->get_entity_name(),
            "{$gradeitem}.itemmodule"
        ))
            ->add_joins($this->get_joins())
            ->set_options_callback([self::class, 'get_module_options']);

        // Maximum grade.
        $filters[] = (new filter(
            number::class,
            'grademax',
            new lang_string('grademax', 'report_activities'),
            $this->get_entity_name(),
            "{$gradeitem}.grademax"
        ))
            ->add_joins($this->get_joins());

        return $filters;
    }

    /**
     * Options for activity type filter (installed modules with grades).
     *
     * @return array<string, string>
     */
    public static function get_module_options(): array {
        global $DB;

        $modules = $DB->get_fieldset_sql("
            SELECT DISTINCT itemmodule
              FROM {grade_items}
             WHERE itemtype = :itemtype
               AND itemmodule IS NOT NULL
               AND itemmodule <> ''
          ORDER BY itemmodule
        ", ['itemtype' => 'mod']);

        $options = [];
        foreach ($modules as $module) {
            $component = 'mod_' . $module;
            if (get_string_manager()->string_exists('modulename', $component)) {
                $options[$module] = get_string('modulename', $component);
            } else {
                $options[$module] = $module;
            }
        }
        asort($options, SORT_NATURAL | SORT_FLAG_CASE);

        return $options;
    }
}
