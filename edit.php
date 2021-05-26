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

        echo "Test Page";
        $sourceitem= 2449;
        $destitem = 3246;
        update_new_grade($sourceitem,$destitem);

echo $OUTPUT->footer();