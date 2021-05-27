<?php

function update_new_grade($sourceitem,$destitem){
    global $DB;
    //echo $sourceitem;
    $item_object=$DB->get_record('grade_items',['id'=>$sourceitem]);

    $dest_item_object=$DB->get_record('grade_items',['id'=>$destitem]);
    //print_object($item_object);

    //$course_module =$DB->get_record('local_autograder_list',)
    $sql = 'SELECT userid,finalgrade,rawgrade FROM {grade_grades}
            where itemid = :itemid and finalgrade>1';
    $parameter = ['itemid'=>$sourceitem];
    $source_grades = $DB->get_records_sql($sql,$parameter);

    print_object($source_grades);

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