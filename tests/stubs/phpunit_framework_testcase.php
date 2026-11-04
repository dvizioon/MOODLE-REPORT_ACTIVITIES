<?php
// phpcs:ignoreFile
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
 * IDE-only stub for PHPUnit\Framework\TestCase when vendor/phpunit is absent.
 *
 * PHP 7.4-compatible signatures so moodle-plugin-ci phplint passes on old branches.
 * Not loaded at runtime (Composer/Moodle autoload never includes this path).
 *
 * @package   report_activities
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace PHPUnit\Framework;

abstract class TestCase {
    /**
     * @param mixed $condition
     * @param string $message
     */
    public function assertTrue($condition, string $message = ''): void {
    }

    /**
     * @param mixed $condition
     * @param string $message
     */
    public function assertFalse($condition, string $message = ''): void {
    }

    /**
     * @param mixed $expected
     * @param mixed $actual
     * @param string $message
     */
    public function assertEquals($expected, $actual, string $message = ''): void {
    }

    /**
     * @param mixed $expected
     * @param mixed $actual
     * @param string $message
     */
    public function assertSame($expected, $actual, string $message = ''): void {
    }

    /**
     * @param mixed $expected
     * @param mixed $actual
     * @param string $message
     */
    public function assertNotSame($expected, $actual, string $message = ''): void {
    }

    /**
     * @param mixed $condition
     * @param string $message
     */
    public function assertNotFalse($condition, string $message = ''): void {
    }

    /**
     * @param mixed $actual
     * @param string $message
     */
    public function assertNotNull($actual, string $message = ''): void {
    }

    /**
     * @param mixed $actual
     * @param string $message
     */
    public function assertNull($actual, string $message = ''): void {
    }

    /**
     * @param int $expectedcount
     * @param mixed $haystack
     * @param string $message
     */
    public function assertCount(int $expectedcount, $haystack, string $message = ''): void {
    }

    /**
     * @param string|object $expected
     * @param mixed $actual
     * @param string $message
     */
    public function assertInstanceOf($expected, $actual, string $message = ''): void {
    }

    public function assertStringContainsString(string $needle, string $haystack, string $message = ''): void {
    }

    /**
     * @param int|string $key
     * @param mixed $array
     * @param string $message
     */
    public function assertArrayHasKey($key, $array, string $message = ''): void {
    }
}
