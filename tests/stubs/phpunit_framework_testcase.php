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
 * Not loaded at runtime (Composer/Moodle autoload never includes this path).
 *
 * @package   report_activities
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace PHPUnit\Framework;

abstract class TestCase {
    public function assertTrue(mixed $condition, string $message = ''): void {
    }

    public function assertFalse(mixed $condition, string $message = ''): void {
    }

    public function assertEquals(mixed $expected, mixed $actual, string $message = ''): void {
    }

    public function assertSame(mixed $expected, mixed $actual, string $message = ''): void {
    }

    public function assertNotSame(mixed $expected, mixed $actual, string $message = ''): void {
    }

    public function assertNotFalse(mixed $condition, string $message = ''): void {
    }

    public function assertNotNull(mixed $actual, string $message = ''): void {
    }

    public function assertNull(mixed $actual, string $message = ''): void {
    }

    public function assertCount(int $expectedcount, mixed $haystack, string $message = ''): void {
    }

    public function assertInstanceOf(string|object $expected, mixed $actual, string $message = ''): void {
    }

    public function assertStringContainsString(string $needle, string $haystack, string $message = ''): void {
    }

    public function assertArrayHasKey(int|string $key, mixed $array, string $message = ''): void {
    }
}
