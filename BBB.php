
	<?php
	require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
	require_once(dirname(__FILE__).'/locallib.php');
	require_once(dirname(__FILE__).'/lib.php');
	
	$bbbsession['salt'] = trim($CFG->BigBlueButtonSaltKey);
	$bbbsession['url'] = trim(trim($CFG->ServerURLforBigBlueButton),'/').'/';
	$id = optional_param('id', 0, PARAM_INT);
	global $DB;
	$cm = get_coursemodule_from_id('streamline', $id, 0, false, MUST_EXIST);
	$streamline = $DB->get_record('streamline', array('id' => $cm->instance), '*', MUST_EXIST);
	$meeting = $streamline -> meetingid;
	$course = $streamline -> course;
	$id = $streamline -> id;
	$dash = "-";
	$meetingid=$meeting.$dash.$course.$dash.$id;

	$meetingRunningUrl = bigbluebuttonbn_getIsMeetingRunningURL( $meetingid, $bbbsession['url'], $bbbsession['salt'] );
	$recordingsURL = bigbluebuttonbn_getRecordingsArray($meetingid, $bbbsession['url'], $bbbsession['salt'] );
	
	$userID = $USER->id;

    //$context = context_module::instance($cm->id);
	$context = get_context_instance(CONTEXT_COURSE,$COURSE->id);
	$roles = get_user_roles($context, $USER->id, true);
	
	$participants = $streamline->participants;
	
	if( $streamline->participants == null || $streamline->participants == "[]" ){
    //The room that is being used comes from a previous version
		$moderator = has_capability('mod/streamline:moderate', $context);
	} else {
		$moderator = bigbluebuttonbn_is_moderator($userID, $roles, $participants);
	}
	$administrator = has_capability('moodle/category:manage', $context);

	//104.155.215.138
	global $CFG;
	$ipaddress = trim($CFG->ServerURLforBigBlueButton);
	$variable2 = substr($ipaddress, 0, strpos($ipaddress, "b"));
	$variable = trim(trim($variable2),'/').'/';
	$moodle_dir = $CFG->wwwroot;
	
	?>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="streamline.css">
	
	<script>
		  console.log("Meeting 13 Test");
		  var meeting = <?php echo json_encode($meetingid); ?>; 
		  var meetingRunningUrl = <?php echo json_encode($meetingRunningUrl); ?>;
		  var recordings = <?php echo json_encode($recordingsURL); ?>;
		  var userID = <?php echo json_encode($userID); ?>;
		  var roles = <?php echo json_encode($roles); ?>;
		  var participants = <?php echo json_encode($participants); ?>;
		  var moderator = <?php echo json_encode($moderator); ?>;
		  var administrator = <?php echo json_encode($administrator); ?>;

		  
			console.log("User ID: " + userID);	
			console.log("Roles: " + roles);
			console.log("Participants: " + participants);			
	
			
		console.log("Moderator: " + moderator);
		console.log("Admin: " + administrator);
		  console.log(meeting);
		  console.log(meetingRunningUrl);
		  console.log(recordingUrl);
	</script>
	
	<script>
		var meetingRunningUrl = <?php echo json_encode($meetingRunningUrl); ?>;
		var moderator = <?php echo json_encode($moderator); ?>;
		var administrator = <?php echo json_encode($administrator); ?>;
		  
		var sessionRunning = false;
		var url = "" 
		
		// A $( document ).ready() block.
		$( document ).ready(function() {
			BBBSessionRunning();
			if(sessionRunning || administrator || moderator) {
				$("#liveView").css("visibility", "visible");
				$("#recordingView").css("display", "none")			
			} else {
				$("#liveView").css("display", "none")
				$("#recordingView").css("visibility", "visible");
				try{
					console.log("Recording Response");
					console.log(recordings);
					var url=recordings[0].playbacks.presentation.url;
					$("#recordingView").html("<iframe class='recording' width='100%' height='100%' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='"+url+"'></iframe>");  
				}
				catch(e) // This runs when there is error
				{
					$("#recordingView").html("No recordings available for this lecture");
				}				
				//loadRecording();
			}
			
			console.log(sessionRunning);

		});
		
		function loadRecording() {
			$.ajax({
				  type: "GET",
				  url: recordingUrl,
				  dataType: "xml",
				  contentType: "text/xml; charset=\"utf-8\"",
				  complete: function(xmlResponse) {
					console.log(xmlResponse);
					var recordingsResponse=xmlResponse.responseXML;
					try{
						console.log("Recording Response");
						console.log(recordingsResponse);
						var url=recordingsResponse[0].playbacks.presentation.url;
						$("#recordingView").html("<iframe class='recording' width='100%' height='100%' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='"+url+"'></iframe>");  
					}
					catch(e) // This runs when there is error
					{
						$("#recordingView").html("No recordings available for this lecture");
					}
				  }
			});
		}
		
		function BBBSessionRunning() {
			$.ajax({
				  type: "GET",
				  url: meetingRunningUrl,
				  dataType: "xml",
				  contentType: "text/xml; charset=\"utf-8\"",
				  complete: function(xmlResponse) {
					meetingResponse=xmlResponse.responseXML;
					meetingRunning=meetingResponse.getElementsByTagName("running")[0];
					running=meetingRunning.childNodes[0].nodeValue;
					if(running == "false") {
						sessionRunning = false;
						console.log("Session is not running");
						return false;
					} else if(running == "true") {
						sessionRunning = true;
						console.log("Session is running");
						return true;
					}
				  },
				   async:   false
			});
		}
		</script>
	
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <style type="text/css" media="screen">
      html, body, #flashclient                { height:50%;}
      body                                    { margin:0; padding:0; }
      #altContent                             { /* style alt content */ }
    </style>
    <script type="text/javascript" src="<?php Print($variable); ?>client/swfobject/swfobject.js"></script>
	
	
    <script type="text/javascript">
      swfobject.registerObject("ChatModule", "11", "expressInstall.swf");
      swfobject.registerObject("BigBlueButton", "11", "expressInstall.swf");
      swfobject.registerObject("WebcamPreviewStandalone", "11", "expressInstall.swf");
      swfobject.registerObject("WebcamViewStandalone", "11", "expressInstall.swf");
    </script>
    <script src="<?php Print($variable);?>/client/lib/jquery-1.5.1.min.js" language="javascript"></script>
    <script src="<?php Print($variable);?>/client/lib/bigbluebutton.js" language="javascript"></script>
    <script src="<?php Print($variable);?>client/lib/bbb_localization.js" language="javascript"></script>
    <script src="<?php Print($variable);?>client/lib/bbb_blinker.js" language="javascript"></script>
    <script src="<?php Print($variable);?>client/lib/bbb_deskshare.js" language="javascript"></script>
    <script type="text/javascript" src="<?php Print($variable);?>client/lib/bbb_api_bridge.js"></script>
    <script type="text/javascript" src="<?php Print($variable);?>client/lib/bbb_api_cam_preview.js"></script>
    <script type="text/javascript" src="<?php Print($variable);?>client/lib/bbb_api_cam_view.js"></script>
    <script type="text/javascript" src="<?php Print($moodle_dir);?>/mod/streamline/3rd-party.js"></script>
  
    <script>
      window.chatLinkClicked = function(url) {
        window.open(url, '_blank');
        window.focus();
      }
      window.displayBBBClient = function() {
        var bbbc = document.getElementById("flashclient");
        var wcpc = document.getElementById("webcampreviewclient");
        wcpc.style.display = "none";
        bbbc.style.display = "block";
      }
      window.displayWCClient = function() {
        console.log("Displaying webcam preview client");
        var wcpc = document.getElementById("webcampreview");
        wcpc.style.display = "block";
      }
      window.onload = function() {
         registerListeners();
      }
	  //window.location.href="<?php Print($variable); ?>bigbluebutton/api/create?meetingID=test-105&checksum=6de5d773b1768d17f30765e606f1869561e2cce0";
	  
	  
    </script>
</head>
  <body>
  <!--
    <div id="controls">
      <button type="button" onclick="registerListeners()">Listen for Events</button>
      <button type="button" onclick="displayBBBClient()">Show BBB Client</button>
      <button type="button" onclick="displayWCClient()">Show WC Client</button>
      <button type="button" onclick="BBB.shareVideoCamera()">Share Webcam</button>
      <button type="button" onclick="BBB.stopSharingCamera()">Stop Webcam</button>
      <button type="button" onclick="BBB.switchPresenter('x8hxeozsqbk1')">Switch Presenter</button>
      <button type="button" onclick="joinVoiceConference2()">Join Voice</button>
      <button type="button" onclick="leaveVoiceConference2()">Leave Voice</button>
      <button type="button" onclick="getMyUserID()">Get My UserID</button>
      <button type="button" onclick="getMeetingID()">Get MeetingID</button>
      <button type="button" onclick="getMyRoleAsynch()">Get My Role Asynch</button>
      <button type="button" onclick="getMyRoleSynch()">Get My Role Synch</button>
      <button type="button" onclick="muteMe()">Mute Me</button>
      <button type="button" onclick="unmuteMe()">Unmute Me</button>
      <button type="button" onclick="muteAll()">Mute All</button>
      <button type="button" onclick="unmuteAll()">Unmute All</button>
      <button type="button" onclick="raiseHand(true)">Raise Hand</button>
      <button type="button" onclick="raiseHand(false)">Lower Hand</button>
      <button type="button" onclick="switchLayout('S2SVideoChat')">Switch Video Layout</button>
      <button type="button" onclick="switchLayout('S2SPresentation')">Switch Present Layout</button>
      <button type="button" onclick="lockLayout(true)">Lock Layout</button>
      <button type="button" onclick="lockLayout(false)">Unlock Layout</button>
      <button type="button" onclick="sendPublicChat()">Send Public Chat</button>
      <button type="button" onclick="sendPrivateChat()">Send Private Chat</button>
      <button type="button" onclick="amIPresenterSync()">Am I Presenter Sync</button>
      <button type="button" onclick="amIPresenterAsync()">Am I Presenter Async</button>
	  <button type="button" onclick="getMyUserInfoAsynch()">User Info Async</button>
      <button type="button" onclick="getMyUserInfoSynch()">UserInfo Sync</button>
      <button type="button" onclick="queryListOfPresentations()">Query Presentations</button>
      <button type="button" onclick="displayPresentation('presentation3')">Display Presentation</button>
      <button type="button" onclick="deletePresentation('presentation3')">Delete Presentation</button>
      <form id="formUpload" name="formUpload" enctype="multipart/form-data">
        <input type="file" name="fileUpload" id="fileUpload" />
        <button type="button" onclick="uploadPresentation()">Upload Presentation</button>
      </form>
    </div>
-->
	<div id="middleContainer">
		<div id="liveView">
			<div id="flashclient" style="background-color:#EEEEEE;height:100%;width:100%;float:left;">
			   <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="50%" height="50%" id="BigBlueButton" name="BigBlueButton" align="middle">
				  <param name="movie" value="<?php Print($variable);?>client/BigBlueButton.swf?v=216" />
				  <param name="quality" value="high" />
				  <param name="allowfullscreen" value="true" />
				  <param name="bgcolor" value="#869ca7" />
				  <param name="wmode" value="window" />
				  <param name="allowScriptAccess" value="always" />
				 
					<object type="application/x-shockwave-flash" data="<?php Print($variable); ?>client/BigBlueButton.swf?v=VERSION" width="100%" height="100%" align="middle">
					  <param name="quality" value="high" />
					  <param name="bgcolor" value="#869ca7" />
					  <param name="allowScriptAccess" value="always" />
					  
						<a href="http://www.adobe.com/go/getflashplayer">
						  <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
						</a>
					 
				   </object>
			   
				</object>
		<!--
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="50%" height="50%" id="ChatModule" name="ChatModule" align="middle">
				<param name="url" value="http://104.199.138.224/client/ChatModule.swf?v=VERSION" />
				<param name="uri" value="rtmp://104.199.138.224/bigbluebutton" />
				<param name="dependsOn" value="UsersModule" />
				<param name="privateEnabled" value="true" />
				<param name="fontSize" value="12" />
				<param name="colorPickerIsVisible" value="true" />
			</object>
		-->
			</div>
		</div>
		<div id="recordingView">
			This is a place holder for the playback plugin
		</div>
	</div>
	<div id="rightContainer">
		Placeholder
	</div>
    <div id="update-display"/>
    <div id="notifications" aria-live="polite" role="region" aria-label="Chat Notifications"></div>
  </body>
</html>


