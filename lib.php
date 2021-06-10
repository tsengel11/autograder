<?php

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once("$CFG->libdir/formslib.php");

function update_new_grade($sourceitem,$destitem){
    global $CFG ,$DB;
    //echo $sourceitem;
    require_once($CFG->dirroot . '/lib/gradelib.php');
    $item_object=$DB->get_record('grade_items',['id'=>$sourceitem]);

    $dest_item_object=$DB->get_record('grade_items',['id'=>$destitem]);
    $sql = 'SELECT userid,finalgrade,rawgrade FROM {grade_grades}
            where itemid = :itemid and finalgrade>0';
    $parameter = ['itemid'=>$sourceitem];
    $source_grades = $DB->get_records_sql($sql,$parameter);

    //print_object($source_grades);

    foreach($source_grades as $grade){
        $newgrade = array();
        $newgrade['userid'] = $grade->userid;
        $newgrade['rawgrade'] = $grade->rawgrade;
        $newgrade['finalgrade'] = $grade->finalgrade;
        $result=grade_update('mod/quiz', $dest_item_object->courseid, 
                    $item_object->itemtype, 
                    $item_object->itemmodule, 
                    $dest_item_object->iteminstance, 0, 
                    $newgrade);
        echo $result;
    }

}
function convert_item_name($item){
    global $DB;
    $sql = 'SELECT i.itemname, c.fullname FROM {grade_items} as i
            left join {course} as c on i.courseid = c.id
            where i.id=:itemid';
    $parameter = ['itemid'=>$item];

    //print_object($DB->get_record_sql($sql,$parameter,));

    $result = $DB->get_record_sql($sql,$parameter);
    return $result;

}
function convert_cmid($cmid){
    global $DB;
    $sql = 'SELECT i.id FROM {grade_items} as i
            left join {course_modules} as cm on i.iteminstance = cm.instance
            where cm.id=:cmid';
    $parameter = ['cmid'=>$cmid];
    $result = $DB->get_record_sql($sql,$parameter);
    return $result;
}
function get_cmid_details($cmid){
    global $DB;
    $sql = 'SELECT cm.id,
            c.fullname,
            q.name 
            FROM {course_modules} as cm
            left join {quiz} as q on cm.instance = q.id
            left join {course} as c on cm.course = c.id
            where cm.id=:cmid';
    $parameter = ['cmid'=>$cmid];
    $result = $DB->get_record_sql($sql,$parameter);
    return $result;
}

function create_delete_buttons($id){
    global $CFG;
    return '<a href="'.$CFG->wwwroot.'/local/autograder/edit.php?id='.$id.'&mode=1"'. 'class="action-icon"><i class="icon fa fa-trash fa-fw " title="Delete" aria-label="Delete"></i></a>';
}
function create_deactive_buttons($id){
    global $CFG;
    return '<a href="'.$CFG->wwwroot.'/local/autograder/edit.php?id='.$id.'&mode=2"'. 'class="action-icon"><i class="icon fa fa-square-o fa-fw " title="Edit" aria-label="Edit"></i></a>';
}
function create_active_buttons($id){
    global $CFG;
    return '<a href="'.$CFG->wwwroot.'/local/autograder/edit.php?id='.$id.'&mode=3"'. 'class="action-icon"><i class="icon fa fa-check-square-o fa-fw " title="Edit" aria-label="Edit"></i></a>';
}
function create_delete_buttons_quiz_attemt($id){
    global $CFG;
    return '<a href="'.$CFG->wwwroot.'/local/autograder/edit_quiz_attempt.php?id='.$id.'&mode=1"'. 'class="action-icon"><i class="icon fa fa-trash fa-fw " title="Delete" aria-label="Delete"></i></a>';
}