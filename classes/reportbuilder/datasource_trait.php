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
 * Publishes entity columns on Moodle 4.0 and on later Report Builder APIs.
 *
 * @package   report_activities
 * @copyright 2026 Dvizioon
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace report_activities\reportbuilder;

/**
 * Moodle 4.1+ has add_all_from_entities(). Moodle 4.0 only has add_*_from_entity().
 */
trait datasource_trait {
    /**
     * Make every column, filter and condition of the given entities available.
     *
     * @param \core_reportbuilder\local\entities\base[] $entities
     */
    protected function publish_entities(array $entities): void {
        if (method_exists(\core_reportbuilder\datasource::class, 'add_all_from_entities')) {
            $this->add_all_from_entities();
            return;
        }
        foreach ($entities as $entity) {
            $name = $entity->get_entity_name();
            $this->add_columns_from_entity($name);
            $this->add_filters_from_entity($name);
            $this->add_conditions_from_entity($name);
        }
    }
}
