<?php
require_once("$CFG->libdir/formslib.php");

class auto_grader_form extends moodleform {

    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!
        $mform->addElement('html', '<hr />');
        $mform->addElement('html', '<h4><b>Create New clustered Units</b></h4>');
        $mform->addElement('text', 'source_item', 'Source Activity: (Course Module Id)','size="4"'); // Add elements to your form
        $mform->setType('source_item', PARAM_NOTAGS);                 //Default value

        $mform->addElement('text', 'dest_item', 'Destination Activity:(Course Module Id)','size="4"'); // Add elements to your form
        $mform->setType('dest_item', PARAM_NOTAGS);

        $active_flag = array();
        $active_flag['1']='Deactive';
        $active_flag['0']='Active';
        $mform->addElement('select','active_flag','Activation:',$active_flag);
        $this->add_action_buttons();
    }

}