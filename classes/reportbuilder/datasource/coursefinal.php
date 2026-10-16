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

namespace report_activities\reportbuilder\datasource;

use core_course\reportbuilder\local\entities\course_category;
use core_reportbuilder\datasource;
use core_reportbuilder\local\entities\{course, user};
use core_reportbuilder\local\helpers\database;
use report_activities\reportbuilder\local\entities\course_total;

/**
 * Course final grade datasource — stored gradebook total, not an activity grade.
 *
 * @package   report_activities
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class coursefinal extends datasource {

    /**
     * Return user friendly name of the report source.
     *
     * @return string
     */
    public static function get_name(): string {
        return get_string('coursefinal', 'report_activities');
    }

    /**
     * Initialise report.
     */
    protected function initialise(): void {
        global $CFG;

        // Rows come from the stored course total. The cell value is grade_get_course_grades().
        $gradeentity = new course_total();
        $gradeentity->set_entity_name('grade');
        $gradealias = $gradeentity->get_table_alias('grade_grades');
        $gradeitemalias = $gradeentity->get_table_alias('grade_items');

        $this->set_main_table('grade_grades', $gradealias);

        // Course total: itemtype=course. iteminstance is the grade category id, not the course id.
        $gradeitemjoin = "JOIN {grade_items} {$gradeitemalias}
            ON {$gradeitemalias}.id = {$gradealias}.itemid
           AND {$gradeitemalias}.itemtype = 'course'";
        $gradeentity->add_join($gradeitemjoin);
        $this->add_entity($gradeentity);

        // User.
        $userentity = new user();
        $useralias = $userentity->get_table_alias('user');
        $paramguest = database::generate_param_name();
        $userentity->add_join("JOIN {user} {$useralias}
            ON {$useralias}.id = {$gradealias}.userid
           AND {$useralias}.deleted = 0");
        $this->add_entity($userentity);
        $this->add_base_condition_sql("{$useralias}.id <> :{$paramguest}", [
            $paramguest => (int) $CFG->siteguest,
        ]);

        // Course.
        $courseentity = new course();
        $coursealias = $courseentity->get_table_alias('course');
        $paramsite = database::generate_param_name();
        $courseentity->add_joins($gradeentity->get_joins());
        $courseentity->add_join("JOIN {course} {$coursealias}
            ON {$coursealias}.id = {$gradeitemalias}.courseid");
        $this->add_entity($courseentity);
        $this->add_base_condition_sql("{$coursealias}.id <> :{$paramsite}", [
            $paramsite => SITEID,
        ]);

        // Course category.
        $coursecatentity = new course_category();
        $coursecatalias = $coursecatentity->get_table_alias('course_categories');
        $coursecatentity->add_joins($courseentity->get_joins());
        $coursecatentity->add_join("LEFT JOIN {course_categories} {$coursecatalias}
            ON {$coursecatalias}.id = {$coursealias}.category");
        $this->add_entity($coursecatentity);

        $this->add_all_from_entities([
            $userentity,
            $courseentity,
            $coursecatentity,
            $gradeentity,
        ]);
    }

    /**
     * Default columns.
     *
     * @return string[]
     */
    public function get_default_columns(): array {
        return [
            'user:fullnamewithlink',
            'course:coursefullnamewithlink',
            'grade:finalgrade',
        ];
    }

    /**
     * Default sorting.
     *
     * @return int[]
     */
    public function get_default_column_sorting(): array {
        return [
            'user:fullnamewithlink' => SORT_ASC,
            'course:coursefullnamewithlink' => SORT_ASC,
        ];
    }

    /**
     * Default filters.
     *
     * @return string[]
     */
    public function get_default_filters(): array {
        return [
            'user:fullname',
            'course:fullname',
            'grade:finalgrade',
        ];
    }

    /**
     * Default conditions.
     *
     * @return string[]
     */
    public function get_default_conditions(): array {
        return [];
    }
}
