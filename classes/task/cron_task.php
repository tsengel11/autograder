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
 * A scheduled task for forum cron.
 *
 *
 * @package    local_autograder
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_autograder\task;

class cron_task extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     * 
     * this local plugin automatically retrive grade from another quiz into another quiz grades.
     * 
     * @return string
     */
    public function get_name() {
        return 'local_autograder';
    }

    /**
     * Run forum cron.
     */
    public function execute() {
        global $CFG,$DB;
        require_once($CFG->dirroot . '/local/autograder/lib.php');

        //Retrieving 
        $grade_items=$DB->get_records('local_autograder_list',['disable_flag'=>0]);

        //print_object($grade_items);
        foreach ($grade_items as $item){
            update_new_grade($item->source_item,$item->dest_item);
        }
    }

}