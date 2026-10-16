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
use PHPUnit\Framework\Assert;

/**
 * Unit tests for the course final grade datasource.
 *
 * @package   report_activities
 * @covers    \report_activities\reportbuilder\datasource\coursefinal
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class coursefinal_test extends core_reportbuilder_testcase {

    /**
     * Datasource is discoverable by Report Builder.
     */
    public function test_datasource_is_available(): void {
        $sources = manager::get_report_datasources();
        $found = false;
        foreach ($sources as $componentsources) {
            if (array_key_exists(coursefinal::class, $componentsources)) {
                $found = true;
                Assert::assertEquals(get_string('coursefinal', 'report_activities'), $componentsources[coursefinal::class]);
                break;
            }
        }
        Assert::assertTrue($found, 'Course final grade datasource should appear in Report Builder sources');
    }

    /**
     * Default columns read the stored course total, not an activity grade.
     */
    public function test_datasource_default(): void {
        global $CFG;
        $this->resetAfterTest();
        $this->setAdminUser();
        require_once("{$CFG->libdir}/gradelib.php");

        $course = $this->getDataGenerator()->create_course(['fullname' => 'Curso Nota']);
        $student = $this->getDataGenerator()->create_and_enrol($course, 'student', [
            'firstname' => 'Ana',
            'lastname' => 'Silva',
        ]);

        $courseitem = grade_item::fetch([
            'itemtype' => 'course',
            'courseid' => $course->id,
        ]);
        Assert::assertNotFalse($courseitem);
        $courseitem->update_final_grade($student->id, 8.7);

        /** @var core_reportbuilder_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('core_reportbuilder');
        $report = $generator->create_report([
            'name' => 'Course final test',
            'source' => coursefinal::class,
            'default' => 1,
        ]);

        $content = $this->get_custom_report_content($report->get('id'));
        Assert::assertCount(1, $content);

        $row = array_values($content[0]);
        Assert::assertStringContainsString(fullname($student), $row[0]);
        Assert::assertStringContainsString($course->fullname, $row[1]);
        Assert::assertEquals(format_float(8.7, 2), $row[2]);
    }
}
