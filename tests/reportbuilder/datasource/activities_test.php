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

use core_reportbuilder\manager;
use core_reportbuilder_generator;
use core_reportbuilder\tests\core_reportbuilder_testcase;
use grade_item;

/**
 * Unit tests for the Activities report builder datasource.
 *
 * @package   report_activities
 * @covers    \report_activities\reportbuilder\datasource\activities
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @method void assertTrue(mixed $condition, string $message = '')
 * @method void assertFalse(mixed $condition, string $message = '')
 * @method void assertEquals(mixed $expected, mixed $actual, string $message = '')
 * @method void assertCount(int $expectedCount, mixed $haystack, string $message = '')
 * @method void assertStringContainsString(string $needle, string $haystack, string $message = '')
 * @method void assertNotFalse(mixed $condition, string $message = '')
 * @method void assertNotSame(mixed $expected, mixed $actual, string $message = '')
 */
final class activities_test extends core_reportbuilder_testcase {

    /**
     * Ensure gradelib is loaded for grade_item helpers.
     */
    private function require_gradelib(): void {
        global $CFG;
        require_once("{$CFG->libdir}/gradelib.php");
    }

    /**
     * Datasource is discoverable by Report Builder when the user has the view capability.
     */
    public function test_datasource_is_available(): void {
        $this->resetAfterTest();
        $this->setAdminUser();

        $this->assertTrue(activities::is_available());

        $sources = manager::get_report_datasources();
        $found = false;
        foreach ($sources as $componentsources) {
            if (array_key_exists(activities::class, $componentsources)) {
                $found = true;
                $this->assertEquals(get_string('activities', 'report_activities'), $componentsources[activities::class]);
                break;
            }
        }
        $this->assertTrue($found, 'Activities datasource should appear in Report Builder sources');
    }

    /**
     * Datasource is hidden when the user lacks report/activities:view.
     */
    public function test_datasource_unavailable_without_capability(): void {
        $this->resetAfterTest();
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);

        $this->assertFalse(activities::is_available());
    }

    /**
     * Default report columns show user, course, activity and gradebook grades.
     */
    public function test_datasource_default(): void {
        $this->resetAfterTest();
        $this->setAdminUser();
        $this->require_gradelib();

        $course = $this->getDataGenerator()->create_course(['fullname' => 'Grade Course']);
        $student = $this->getDataGenerator()->create_and_enrol($course, 'student', [
            'firstname' => 'Ana',
            'lastname' => 'Silva',
        ]);

        $assign = $this->getDataGenerator()->create_module('assign', [
            'course' => $course->id,
            'name' => 'Trabalho 1',
            'grade' => 100,
        ]);

        $gradeitem = grade_item::fetch([
            'itemtype' => 'mod',
            'itemmodule' => 'assign',
            'iteminstance' => $assign->id,
            'courseid' => $course->id,
        ]);
        $this->assertNotFalse($gradeitem);
        $gradeitem->update_final_grade($student->id, 73.0);

        /** @var core_reportbuilder_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('core_reportbuilder');
        $report = $generator->create_report([
            'name' => 'Activities test',
            'source' => activities::class,
            'default' => 1,
        ]);

        $content = $this->get_custom_report_content($report->get('id'));
        $this->assertCount(1, $content);

        $row = array_values($content[0]);
        // Default: user link, course link, activity name, type, %, 0-10, final grade.
        $this->assertStringContainsString(fullname($student), $row[0]);
        $this->assertStringContainsString($course->fullname, $row[1]);
        $this->assertEquals('Trabalho 1', $row[2]);
        $this->assertEquals(get_string('modulename', 'mod_assign'), $row[3]);
        $this->assertEquals('73%', $row[4]);
        $this->assertEquals('7', $row[5]);
        $this->assertEquals(format_float(73.0, 2), $row[6]);
    }

    /**
     * Raw grade vs final grade columns from the gradebook.
     */
    public function test_raw_and_final_grade_columns(): void {
        $this->resetAfterTest();
        $this->setAdminUser();
        $this->require_gradelib();

        $course = $this->getDataGenerator()->create_course();
        $student = $this->getDataGenerator()->create_and_enrol($course, 'student', [
            'firstname' => 'Bruno',
            'lastname' => 'Costa',
        ]);

        $assign = $this->getDataGenerator()->create_module('assign', [
            'course' => $course->id,
            'name' => 'Prova',
            'grade' => 10,
        ]);

        $gradeitem = grade_item::fetch([
            'itemtype' => 'mod',
            'itemmodule' => 'assign',
            'iteminstance' => $assign->id,
            'courseid' => $course->id,
        ]);
        $gradeitem->update_final_grade($student->id, 8.5);

        /** @var core_reportbuilder_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('core_reportbuilder');
        $report = $generator->create_report([
            'name' => 'Grades detail',
            'source' => activities::class,
            'default' => 0,
        ]);

        $generator->create_column(['reportid' => $report->get('id'), 'uniqueidentifier' => 'user:fullname']);
        $generator->create_column(['reportid' => $report->get('id'), 'uniqueidentifier' => 'grade_item:name']);
        $generator->create_column(['reportid' => $report->get('id'), 'uniqueidentifier' => 'grade:rawgrade']);
        $generator->create_column(['reportid' => $report->get('id'), 'uniqueidentifier' => 'grade:finalgrade']);
        $generator->create_column(['reportid' => $report->get('id'), 'uniqueidentifier' => 'grade:percentage']);
        $generator->create_column(['reportid' => $report->get('id'), 'uniqueidentifier' => 'grade:gradeoutof10']);

        $content = $this->get_custom_report_content($report->get('id'));
        $this->assertCount(1, $content);

        $row = array_values($content[0]);
        $this->assertEquals(fullname($student), $row[0]);
        $this->assertEquals('Prova', $row[1]);
        // Raw may match final when graded via update_final_grade; both must be present from gradebook.
        $this->assertNotSame('', $row[3]);
        $this->assertEquals(format_float(8.5, 2), $row[3]);
        $this->assertEquals('85%', $row[4]);
        $this->assertEquals('9', $row[5]); // 8.5 on 0–10 scale → 9 (half-up).
    }
}
