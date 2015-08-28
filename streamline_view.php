<html>

<head>
   
    <link rel="stylesheet" href="css/kube.min.css" />
    <link rel="stylesheet" href="css/your-styles.css" />
 
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.js"></script>
    <script src="js/kube.min.js"></script>
	<script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
	<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
</head>
</html>

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
 * Prints a particular instance of streamline
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_streamline
 * @copyright  2015 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace streamline with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... streamline instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('streamline', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $streamline  = $DB->get_record('streamline', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $streamline  = $DB->get_record('streamline', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $streamline->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('streamline', $streamline->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_streamline\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $streamline);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/streamline/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($streamline->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('streamline-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($streamline->welcome) {
    //echo $OUTPUT->box(format_module_intro('streamline', $streamline, $cm->id), 'generalbox mod_introbox', 'streamlineintro');
}

/* Example of how to call salt key
$bbbsession = trim($CFG -> BigBlueButtonSaltKey);
echo $bbbsession;*/

// Replace the following lines with you own code.
//echo $OUTPUT->heading('Yay! It works!');

//
?>
<div class="units-row">

	<div class="backG">
		<?php include 'BBB.php';?>
	</div>


</div>
</br>
<div class="units-row">
	<div class="backG">
	<div class="unit-100">
		<?php include 'Forums/Forum.php';?>
	</div>
	<div>
<div>
<?php
// Finish the page.
echo $OUTPUT->footer();

?>

