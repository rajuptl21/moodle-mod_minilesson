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

namespace mod_minilesson\local\itemtype;

use mod_minilesson\constants;
use mod_minilesson\utils;
use templatable;
use renderable;

/**
 * Renderable class for a speakinggapfill item in a minilesson activity.
 *
 * @package    mod_minilesson
 * @copyright  2023 Justin Hunt <justin@poodll.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class item_speakinggapfill extends item {

    //the item type
    public const ITEMTYPE = constants::TYPE_SGAPFILL;


    /**
     * The class constructor.
     *
     */
    public function __construct($itemrecord, $moduleinstance=false, $context=false){
        parent::__construct($itemrecord, $moduleinstance, $context);
        $this->needs_speechrec=true;
    }

    /**
     * Export the data for the mustache template.
     *
     * @param \renderer_base $output renderer to be used to render the action bar elements.
     * @return array
     */
    public function export_for_template(\renderer_base $output) {

        $testitem= new \stdClass();
        $testitem = $this->get_common_elements($testitem);
        $testitem = $this->get_text_answer_elements($testitem);
        $testitem = $this->get_polly_options($testitem);
        $testitem = $this->set_layout($testitem);

        // Sentences
        $sentences = [];
        if(isset($testitem->customtext1)) {
            $sentences = explode(PHP_EOL, $testitem->customtext1);
        }

        $testitem->sentences = $this->process_speakinggapfill_sentences($sentences);
        $testitem->readsentence = $this->itemrecord->{constants::READSENTENCE}==1;
        $testitem->allowretry = $this->itemrecord->{constants::GAPFILLALLOWRETRY}==1;

        // Cloudpoodll
        $testitem = $this->set_cloudpoodll_details($testitem);

        return $testitem;
    }

    public static function validate_import($newrecord,$cm){
        $error = new \stdClass();
        $error->col='';
        $error->message='';

        if($newrecord->customtext1==''){
            $error->col='customtext1';
            $error->message=get_string('error:emptyfield',constants::M_COMPONENT);
            return $error;
        }

        //return false to indicate no error
        return false;
    }
        /*
    * This is for use with importing, telling import class each column's is, db col name, minilesson specific data type
    */
    public static function get_keycolumns(){
        //get the basic key columns and customize a little for instances of this item type
        $keycols = parent::get_keycolumns();
        $keycols['text5']=['type'=>'voice','optional'=>true,'default'=>null,'dbname'=>constants::POLLYVOICE];
        $keycols['int4']=['type'=>'voiceopts','optional'=>true,'default'=>null,'dbname'=>constants::POLLYOPTION];
        $keycols['int3']=['type'=>'boolean','optional'=>true,'default'=>0,'dbname'=>constants::GAPFILLALLOWRETRY];
        $keycols['int2']=['type'=>'boolean','optional'=>true,'default'=>0,'dbname'=>constants::READSENTENCE];
        return $keycols;
    }

}
