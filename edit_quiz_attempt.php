<?php

/**
 * Version details
 *
 * @package    block_student_progress
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/../../config.php');
require_login();
global $CFG, $DB;
require "$CFG->libdir/tablelib.php";
require_once($CFG->dirroot . '/local/autograder/lib.php');

$id = optional_param('id',NULL, PARAM_INT);
$mode = optional_param('mode',NULL, PARAM_INT);

$PAGE->set_url(new moodle_url('/local/autograder/edit_quiz_attempt.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Edit Autograder');

$url = $CFG->wwwroot;
// add new connection and show all connections = 0
// delele existing connections = 1
// edit the existing connection = 2


class add_quiz_attemt_form extends moodleform {

    //Add elements to form
    public function definition() {
        $mform = $this->_form; // Don't forget the underscore!
        $mform->addElement('html', '<hr />');
        $mform->addElement('text', 'item', 'Create Multi attempt quiz: (Course Module Id) ','size="4"'); // Add elements to your form
        $mform->setType('item', PARAM_NOTAGS);                 //Default value


        $this->add_action_buttons();
    }

}

echo $OUTPUT->header();
// Checking the permission
if(!is_siteadmin()){
    \core\notification::add("You don't have permission to access to Grading Report", \core\output\notification::NOTIFY_ERROR);
    redirect($CFG->wwwroot);
}

if($mode==1){
    global $DB;
    $DB->delete_records('quiz_essay_attempts_quiz',['id'=>$id]);
    \core\notification::add("Record Deleted", \core\output\notification::NOTIFY_ERROR);
}
$mform = new add_quiz_attemt_form();
if ($mform->is_cancelled())
{
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot.'/my','You pressed Cancel button');
}
//else if ($mform->is_submitted()){
else if ($fromform = $mform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    $insertdata= new stdClass();
    $insertdata->cmid= $fromform->item;

    $DB->insert_record('quiz_essay_attempts_quiz',$insertdata);
    redirect($CFG->wwwroot.'/local/autograder/edit_quiz_attempt.php','Multi attempted quiz inserted');

} else {
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form
}


$data=$DB->get_records('quiz_essay_attempts_quiz');

foreach ($data as $d){
    $cmid_object = get_cmid_details($d->cmid);
    $d->course = $cmid_object->fullname;
    $d->cmid = '<a href="'.$CFG->wwwroot.'/mod/quiz/view.php?id='.$cmid_object->id.'">'.$cmid_object->name.'</a>';

    $d->control = create_delete_buttons_quiz_attemt($d->id);
}
    $table = new html_table();
    $table->head  = array('#','Multiple Attempt Actvities' ,'Unit Name','');
    $table->colclasses = array('id','course','cmid','control');
    $table->size  = array('5%', '40%', '40%', '5%','10%');
    $table->align = array('left', 'left', 'left', 'center');
    $table->width = '50%';
    $table->data  = $data;

    $mform->display();
    echo html_writer::table($table);
    echo $OUTPUT->footer();