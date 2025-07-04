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
 * @package   mod_minilesson
 * @copyright 2014 Justin Hunt poodllsupport@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use \mod_minilesson\constants;

/**
 * Define all the restore steps that will be used by the restore_minilesson_activity_task
 */

/**
 * Structure step to restore one minilesson activity
 */
class restore_minilesson_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();

        $userinfo = $this->get_setting_value('userinfo'); // are we including userinfo?

        ////////////////////////////////////////////////////////////////////////
        // XML interesting paths - non-user data
        ////////////////////////////////////////////////////////////////////////

        // root element describing minilesson instance
        $oneactivity = new restore_path_element(constants::M_MODNAME, '/activity/minilesson');
        $paths[] = $oneactivity;

        //rsquestions
        $rsquestions = new restore_path_element(constants::M_QTABLE,
            '/activity/minilesson/rsquestions/rsquestion');
        $paths[] = $rsquestions;

		

        // End here if no-user data has been selected
        if (!$userinfo) {
            return $this->prepare_activity_structure($paths);
        }

        ////////////////////////////////////////////////////////////////////////
        // XML interesting paths - user data
        ////////////////////////////////////////////////////////////////////////
		//attempts
		 $attempts= new restore_path_element(constants::M_ATTEMPTSTABLE,
                                            '/activity/minilesson/attempts/attempt');
		$paths[] = $attempts;


        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_minilesson($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);
        $data->timecreated = $this->apply_date_offset($data->timecreated);


        // insert the activity record
        $newitemid = $DB->insert_record(constants::M_TABLE, $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }

    protected function process_minilesson_rsquestions($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->timemodified = $this->apply_date_offset($data->timemodified);


        $data->{constants::M_MODNAME} = $this->get_new_parentid(constants::M_MODNAME);
        $newquestionid = $DB->insert_record(constants::M_QTABLE, $data);
        $this->set_mapping(constants::M_QTABLE, $oldid, $newquestionid, true); // Mapping with files
    }

	
	protected function process_minilesson_attempt($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->courseid = $this->get_courseid();
        $data->userid = $this->get_mappingid('user', $data->userid);
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->moduleid = $this->get_new_parentid(constants::M_MODNAME);
        $newitemid = $DB->insert_record(constants::M_ATTEMPTSTABLE, $data);
		
		// Mapping without files
		//here we set the table name as the "key" to the mapping, but its actually arbitrary
		//'we would need to use the "key" later when calling add_related_files for the itemid in the moodle files area
		//IF we had files for this set of data. )
       $this->set_mapping(constants::M_ATTEMPTSTABLE, $oldid, $newitemid, true);
    }

    protected function after_execute() {
        // Add module related files, no need to match by itemname (just internally handled context)
        $this->add_related_files(constants::M_COMPONENT, 'intro', null);
/*		$this->add_related_files(constants::M_COMPONENT, 'welcome', null); */

		//question stuff
        //do question areas
        $this->add_related_files(constants::M_COMPONENT, constants::TEXTQUESTION_FILEAREA, constants::M_QTABLE);
        $this->add_related_files(constants::M_COMPONENT, constants::MEDIAQUESTION, constants::M_QTABLE);
        $this->add_related_files(constants::M_COMPONENT, constants::AUDIOSTORY, constants::M_QTABLE);


        //do answer areas
        for($anumber=1;$anumber<=constants::MAXANSWERS;$anumber++) {
            $this->add_related_files(constants::M_COMPONENT, constants::TEXTANSWER_FILEAREA . $anumber, constants::M_QTABLE);
            $this->add_related_files(constants::M_COMPONENT, constants::FILEANSWER . $anumber, constants::M_QTABLE);
            $this->add_related_files(constants::M_COMPONENT, constants::FILEANSWER . $anumber . '_image', constants::M_QTABLE);
            $this->add_related_files(constants::M_COMPONENT, constants::FILEANSWER . $anumber . '_audio', constants::M_QTABLE);
        }
		
		 $userinfo = $this->get_setting_value('userinfo'); // are we including userinfo?
		 if($userinfo){
			$this->add_related_files(constants::M_COMPONENT, constants::M_FILEAREA_SUBMISSIONS, constants::M_ATTEMPTSTABLE);

         }
    }
}
