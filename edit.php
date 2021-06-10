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
require_once($CFG->dirroot . '/local/autograder/classes/form/add.php');

$id = optional_param('id',NULL, PARAM_INT);
$mode = optional_param('mode',NULL, PARAM_INT);

$PAGE->set_url(new moodle_url('/local/autograder/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Edit Autograder');

// add new connection and show all connections = 0
// delele existing connections = 1
// edit the existing connection = 2


echo $OUTPUT->header();
    // Checking the permission
    if(!is_siteadmin()){
        \core\notification::add("You don't have permission to access to Grading Report", \core\output\notification::NOTIFY_ERROR);
        redirect($CFG->wwwroot);
    }

    //check the modes
    echo $id;

    echo $mode;

    if($mode==1){
        global $DB;
        $DB->delete_records('local_autograder_list',['id'=>$id]);
        \core\notification::add("Record Deleted", \core\output\notification::NOTIFY_ERROR);

    }
    elseif($mode==2){
        $dataobject = new stdClass();
        $dataobject->id=$id;
        $dataobject->disable_flag=1;
        $DB->update_record('local_autograder_list', $dataobject);

    }
    elseif($mode==3){
        $dataobject = new stdClass();
        $dataobject->id=$id;
        $dataobject->disable_flag=0;
        $DB->update_record('local_autograder_list', $dataobject,);

    }

    $mform = new auto_grader_form();
    if ($mform->is_cancelled())
    {
        //Handle form cancel operation, if cancel button is present on form
        redirect($CFG->wwwroot.'/my','You pressed Cancel button');
    }
    //else if ($mform->is_submitted()){
    else if ($fromform = $mform->get_data()) {
        //In this case you process validated data. $mform->get_data() returns data posted in form.
        $insertdata= new stdClass();
        $insertdata->source_item= $fromform->source_item;
        $insertdata->dest_item = $fromform->dest_item;
        $insertdata->disable_flag = $fromform->active_flag;
        $DB->insert_record('local_autograder_list',$insertdata);
        redirect($CFG->wwwroot.'/local/autograder/edit.php','Schedule inserted');

    } else {
        // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
        // or on the first display of the form
    }


    // Rebuilding the data array
    $data=$DB->get_records('local_autograder_list');
    foreach ($data as $d){
        $item_object_source = convert_item_name($d->source_item);
        $item_object_dest = convert_item_name($d->dest_item);

        $d->source_item = $item_object_source->fullname.' - <b>'.$item_object_source->itemname.'</b>';
        $d->dest_item = $item_object_dest->fullname.' - <b>'.$item_object_dest->itemname.'</b>';
        if(($d->disable_flag)==0){
            $d->disable_flag = '<div>'.create_deactive_buttons($d->id).'Active</div>';
        }
        else{
            $d->disable_flag = '<div>'.create_active_buttons($d->id).'Deactive</div>';
        }
        $d->control = create_delete_buttons($d->id);
    }


    $table = new html_table();
    $table->head  = array('#', 'Source', 'Destination', 'Active','');
    $table->size  = array('5%', '40%', '40%', '5%','10%');
    $table->align = array('left', 'left', 'left', 'center');
    $table->width = '90%';
    $table->data  = $data;
    $mform->display();
    echo html_writer::table($table);
    echo $OUTPUT->footer();