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

namespace report_activities\tests;

/**
 * Bridge for Report Builder PHPUnit base across Moodle 4.0–5.x.
 *
 * Moodle 4.0 / some 4.2 builds define {@see \core_reportbuilder_testcase} in
 * reportbuilder/tests/helpers.php. Later branches autoload
 * {@see \core_reportbuilder\tests\core_reportbuilder_testcase}.
 *
 * @package   report_activities
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (class_exists(\core_reportbuilder\tests\core_reportbuilder_testcase::class)) {
    /**
     * Autoloaded Moodle 4.1+ / 5.x helper.
     */
    abstract class reportbuilder_testcase extends \core_reportbuilder\tests\core_reportbuilder_testcase {
    }
} else {
    global $CFG;
    $loaded = false;
    // Join a relative core path. Moodle 4.0 keeps the helper beside reportbuilder/tests.
    // Moodle 5.1+ keeps the same file under public/.
    $relatives = [
        'reportbuilder/tests/helpers.php',
        'public/reportbuilder/tests/helpers.php',
    ];
    foreach ($relatives as $relative) {
        $helper = $CFG->dirroot . '/' . $relative;
        if (is_readable($helper)) {
            require_once($helper);
            $loaded = true;
            break;
        }
    }
    if (!$loaded || !class_exists(\core_reportbuilder_testcase::class)) {
        throw new \coding_exception(
            'core_reportbuilder_testcase is not available on this Moodle version'
        );
    }
    /**
     * Legacy helpers.php base (Moodle 4.0-era).
     */
    abstract class reportbuilder_testcase extends \core_reportbuilder_testcase {
    }
}
