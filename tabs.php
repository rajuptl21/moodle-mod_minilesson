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
 * Sets up the tabs at the top of the module view page　for teachers.
 *
 * This file was adapted from the mod/lesson/tabs.php
 *
 * @package mod_minilesson
 * @copyright  2014 Justin Hunt  {@link http://poodll.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or late
 */

defined('MOODLE_INTERNAL') || die();

use \mod_minilesson\constants;

/// This file to be included so we can assume config.php has already been included.
global $DB;
if (empty($moduleinstance)) {
    print_error('cannotcallscript');
}
if (!isset($currenttab)) {
    $currenttab = '';
}
if (!isset($cm)) {
    $cm = get_coursemodule_from_instance(constants::M_MODNAME, $moduleinstance->id);
    $context = context_module::instance($cm->id);
}
if (!isset($course)) {
    $course = $DB->get_record('course', array('id' => $moduleinstance->course));
}

$tabs = $row = $inactive = $activated = array();
$config = get_config(constants::M_COMPONENT);


$row[] = new tabobject('view', "$CFG->wwwroot/mod/minilesson/view.php?id=$cm->id", get_string('view', constants::M_COMPONENT), get_string('preview', constants::M_COMPONENT, format_string($moduleinstance->name)));
if (has_capability('mod/minilesson:manage', $context)) {
    $row[] = new tabobject('rsquestions', "$CFG->wwwroot/mod/minilesson/rsquestion/rsquestions.php?id=$cm->id", get_string('rsquestions', constants::M_COMPONENT), get_string('managersquestions', constants::M_COMPONENT));
}
if (has_capability('mod/minilesson:manage', $context) && $config->enablesetuptab) {
    $row[] = new tabobject(
        'setup',
        "$CFG->wwwroot/mod/minilesson/setup.php?id=$cm->id",
        get_string('setup', constants::M_COMPONENT),
        get_string('setup', constants::M_COMPONENT)
    );
}

if (has_capability('mod/minilesson:evaluate', $context)) {
    $row[] = new tabobject('grading', "$CFG->wwwroot/mod/minilesson/grading.php?id=$cm->id", get_string('grading', constants::M_COMPONENT), get_string('viewgrading', constants::M_COMPONENT));
    $row[] = new tabobject('reports', "$CFG->wwwroot/mod/minilesson/reports.php?id=$cm->id", get_string('reports', constants::M_COMPONENT), get_string('viewreports', constants::M_COMPONENT));
}

if (has_capability('mod/minilesson:manage', $context)) {
    $row[] = new tabobject(
        'import',
        "$CFG->wwwroot/mod/minilesson/import.php?id=$cm->id",
        get_string('import', constants::M_COMPONENT),
        get_string('import', constants::M_COMPONENT)
    );
}

if (has_capability('mod/minilesson:canuseaigen', $context)) {
    $row[] = new tabobject(
        'aigen',
        "$CFG->wwwroot/mod/minilesson/aigen.php?id=$cm->id",
        get_string('aigen', constants::M_COMPONENT),
        get_string('aigen', constants::M_COMPONENT)
    );
}


if (has_capability('mod/minilesson:push', $context) && $config->enablepushtab) {
    $row[] = new tabobject(
        'push',
        "$CFG->wwwroot/mod/minilesson/push.php?id=$cm->id",
        get_string('push', constants::M_COMPONENT),
        get_string('push', constants::M_COMPONENT)
    );
}


$tabs[] = $row;

print_tabs($tabs, $currenttab, $inactive, $activated);
