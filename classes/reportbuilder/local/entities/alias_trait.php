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

/**
 * Table alias helpers for Report Builder entities on Moodle 4.0 and 5.x.
 *
 * @package   report_activities
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace report_activities\reportbuilder\local\entities;

/**
 * Moodle 4.0–4.3 require get_default_table_aliases(). Later versions use get_default_tables().
 */
trait alias_trait {
    /**
     * Legacy alias map. Ignored when the core class already generates aliases.
     *
     * @return string[]
     */
    protected function get_default_table_aliases(): array {
        $aliases = [];
        foreach ($this->get_default_tables() as $table) {
            $aliases[$table] = $table;
        }
        return $aliases;
    }

    /**
     * Table name to SQL alias. Uses get_table_aliases() from Moodle 4.4 onward.
     *
     * @return string[]
     */
    protected function aliasmap(): array {
        if (method_exists(\core_reportbuilder\local\entities\base::class, 'get_table_aliases')) {
            return $this->get_table_aliases();
        }
        $map = [];
        foreach (array_keys($this->get_default_table_aliases()) as $table) {
            $map[$table] = $this->get_table_alias($table);
        }
        return $map;
    }
}
