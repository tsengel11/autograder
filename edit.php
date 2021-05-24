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


$PAGE->set_url(new moodle_url('/local/autograder/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Edit Autograder');
global $CFG, $DB;

echo $OUTPUT->header();

echo "Test Page";

$courseid = 383;

$gradebookgrades = grade_get_grades($courseid, 'mod', 'quiz',898, 99);

$data = new stdClass();
$data->finalgrade = 95;
$data->userid = 99;
$data->id = 194762;

$DB->update_record('grade_grades',$data);

$gradebookgrades2 = grade_get_grades(381, 'mod', 'quiz',1112, 99);

print_object($gradebookgrades);
print_object($gradebookgrades2);

echo $OUTPUT->footer();