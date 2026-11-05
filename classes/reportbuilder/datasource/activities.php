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

use core_reportbuilder\datasource;
use core_reportbuilder\local\entities\{course, user};
use core_reportbuilder\local\helpers\database;
use report_activities\reportbuilder\compat;
use report_activities\reportbuilder\datasource_trait;
use report_activities\reportbuilder\local\entities\{grade, grade_item};

/**
 * Activities datasource — user, activity type and grades for graded activities.
 *
 * @package   report_activities
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class activities extends datasource {
    use datasource_trait;

    /**
     * Return user friendly name of the report source.
     *
     * @return string
     */
    public static function get_name(): string {
        return get_string('activities', 'report_activities');
    }

    /**
     * Whether this report source is available to the current user.
     *
     * @return bool
     */
    public static function is_available(): bool {
        /** @var \core\context $context */
        $context = \core\context\system::instance();
        return has_capability('report/activities:view', $context);
    }

    /**
     * Initialise report.
     */
    protected function initialise(): void {
        global $CFG;

        // Main table: one row per user grade on an activity.
        $gradeentity = new grade();
        $gradealias = $gradeentity->get_table_alias('grade_grades');
        $gradeitemalias = $gradeentity->get_table_alias('grade_items');

        $this->set_main_table('grade_grades', $gradealias);

        // Join activity grade items (exclude category/course totals).
        $gradeitemjoin = "JOIN {grade_items} {$gradeitemalias}
            ON {$gradeitemalias}.id = {$gradealias}.itemid
           AND {$gradeitemalias}.itemtype = 'mod'";
        $gradeentity->add_join($gradeitemjoin);
        $this->add_entity($gradeentity);

        // Same grade_items alias on both entities. get_joins() is protected on Moodle 4.0.
        $gradeitementity = new grade_item();
        compat::share_table_alias($gradeitementity, 'grade_items', $gradeitemalias);
        $gradeitementity->add_join($gradeitemjoin);
        $this->add_entity($gradeitementity);

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

        // Course (needs the grade item join first).
        $courseentity = new course();
        $coursealias = $courseentity->get_table_alias('course');
        $coursejoin = "JOIN {course} {$coursealias}
            ON {$coursealias}.id = {$gradeitemalias}.courseid";
        $courseentity->add_join($gradeitemjoin);
        $courseentity->add_join($coursejoin);
        $this->add_entity($courseentity);

        $entities = [$userentity, $courseentity, $gradeitementity, $gradeentity];

        // Course category exists from Moodle 4.1.
        $categoryclass = compat::course_category_class();
        if ($categoryclass !== null) {
            $coursecatentity = new $categoryclass();
            $coursecatalias = $coursecatentity->get_table_alias('course_categories');
            $coursecatentity->add_join($gradeitemjoin);
            $coursecatentity->add_join($coursejoin);
            $coursecatentity->add_join("LEFT JOIN {course_categories} {$coursecatalias}
                ON {$coursecatalias}.id = {$coursealias}.category");
            $this->add_entity($coursecatentity);
            $entities[] = $coursecatentity;
        }

        $this->publish_entities($entities);
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
            'grade_item:name',
            'grade_item:type',
            'grade:percentage',
            'grade:gradeoutof10',
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
            'grade_item:name' => SORT_ASC,
        ];
    }

    /**
     * Default filters.
     *
     * @return string[]
     */
    public function get_default_filters(): array {
        return [
            'course:fullname',
            'grade_item:type',
            'grade_item:name',
            'user:fullname',
        ];
    }

    /**
     * Default conditions (none forced — empty conditions caused empty reports).
     *
     * @return string[]
     */
    public function get_default_conditions(): array {
        return [];
    }
}
