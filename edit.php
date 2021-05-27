<?php
// This file is part of Moodle - http://moodle.org/
//

/**
 * Version details
 *
 * @package    block_student_progress
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/../../config.php');

require_once($CFG->dirroot . '/local/autograder/lib.php');

$PAGE->set_url(new moodle_url('/local/autograder/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Edit Autograder');
global $CFG, $DB;

echo $OUTPUT->header();

        $grade_items=$DB->get_records('local_autograder_list',['disable_flag'=>0]);
        print_object($grade_items);
        
        foreach ($grade_items as $item){
                echo "Update Function";
        update_new_grade($item->source_item,$item->dest_item);
        }

echo $OUTPUT->footer();