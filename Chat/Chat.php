<template name="footer">
  <div id="footer" class="myFooter gradientBar navbar-default">
    {{{getFooterString}}}
  </div>
</template>

<template name="header">
  {{#if getInSession "display_navbar"}}
    <div id="navbar" class="myNavbar gradientBar navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="navbarUserButtons navbarSection">
        <div id="collapseButtonSection">
          {{#if isPortraitMobile}}
            <button class="navbar-toggle btn navbarButton collapseSlidingMenuButton">
              <div class="push-menu-icon">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </div>
            </button>
          {{else}}
            {{#if getInSession "display_hiddenNavbarSection"}}
              {{> makeButton btn_class="navbarButton collapseNavbarButton" i_class="glyphicon glyphicon-chevron-left" rel="tooltip" data_placement="bottom" title="Collapse"}}
            {{else}}
              {{> makeButton btn_class="navbarButton collapseNavbarButton" i_class="glyphicon glyphicon-chevron-right" rel="tooltip" data_placement="bottom" title="Expand"}}
            {{/if}}
          {{/if}}
        </div>
        <div class="collapseNavbarSection">
          <div class='hiddenNavbarSection'>
            <!-- display/hide users list toggle -->
            {{#if getInSession "display_usersList"}}
              {{> makeButton btn_class="navbarIconToggleActive usersListIcon navbarButton collapseSectionButton" i_class="glyphicon glyphicon-user" rel="tooltip" data_placement="bottom" title="Hide List of Users"}}
            {{else}}
              {{> makeButton btn_class="usersListIcon navbarButton collapseSectionButton" i_class="glyphicon glyphicon-user" rel="tooltip" data_placement="bottom" title="Show List of Users"}}
            {{/if}}

            <!-- display/hide whiteboard toggle -->
            {{#if getInSession "display_whiteboard"}}
              {{> makeButton btn_class="navbarIconToggleActive whiteboardIcon navbarButton collapseSectionButton" i_class="ion-easel" rel="tooltip" data_placement="bottom" title="Hide Whiteboard"}}
            {{else}}
              {{> makeButton btn_class="whiteboardIcon navbarButton collapseSectionButton" i_class="ion-easel" rel="tooltip" data_placement="bottom" title="Show Whiteboard"}}
            {{/if}}

            <!-- display/hide chat bar toggle -->
            {{#if getInSession "display_chatbar"}}
              {{> makeButton btn_class="navbarIconToggleActive chatBarIcon navbarButton collapseSectionButton" i_class="glyphicon glyphicon-comment" rel="tooltip" data_placement="bottom" title="Hide Message Pane"}}
            {{else}}
              {{> makeButton btn_class="chatBarIcon navbarButton collapseSectionButton" i_class="glyphicon glyphicon-comment" rel="tooltip" data_placement="bottom" title="Show Message Pane"}}
            {{/if}}

            <!-- display/hide webcam streams toggle -->
            <!-- {{#if isCurrentUserSharingVideo}}
                   {{> makeButton btn_class="navbarIconToggleActive videoFeedIcon navbarButton" i_class="glyphicon glyphicon-stop" sharingVideo=true rel="tooltip" data_placement="bottom" title="Hide Webcams"}}
                 {{else}}
                   {{> makeButton btn_class="videoFeedIcon navbarButton" i_class="glyphicon glyphicon-facetime-video" sharingVideo=false rel="tooltip" data_placement="bottom" title="Show Webcams"}}
                 {{/if}} -->
          </div>
          <div class='audioControllersSection'>
            <!-- We are in a form of audio -->
            {{#if amIInAudio}}
              <div class='hiddenNavbarSection'>
                <!-- display the button for leaving audio -->
                {{> makeButton btn_class="navbarIconToggleActive audioFeedIcon navbarButton audioButton leaveAudioButton" i_class="ion-volume-mute" sharingAudio=true rel="tooltip" data_placement="bottom" title="Leave Audio Call"}}
              </div>
              {{#unless amIListenOnlyAudio}}
                {{#if isCurrentUserMuted}}
                  <!-- if you are muted the button representing your status will show volume off -->
                  {{> makeButton btn_class="muteIcon navbarButton audioButton" i_class="glyphicon glyphicon-volume-off" sharingAudio=true rel="tooltip" data_placement="bottom" title="Unmute"}}
                {{else}}
                  {{#if isCurrentUserTalking}}
                    <!-- you are talking. Display a high volume/volume up representing voice activity -->
                    {{> makeButton btn_class="navbarIconToggleActive muteIcon navbarButton audioButton" i_class="glyphicon glyphicon-volume-up" sharingAudio=true rel="tooltip" data_placement="bottom" title="Mute"}}
                  {{else}}
                    <!-- you are not talking. Display low volume/volume down representing no voice activity -->
                    {{> makeButton btn_class="navbarIconToggleActive muteIcon navbarButton audioButton" i_class="glyphicon glyphicon-volume-down" sharingAudio=true rel="tooltip" data_placement="bottom" title="Mute"}}
                  {{/if}}
                {{/if}}
              {{/unless}}
            {{else}}
              <div class='hiddenNavbarSection'>
                {{> makeButton btn_class="audioFeedIcon navbarButton audioButton joinAudioButton" i_class="glyphicon glyphicon-headphones" sharingAudio=false rel="tooltip" data_placement="bottom" title="Join Audio Call"}}
              </div>
            {{/if}}
          </div>
          <div class='hiddenNavbarSection'>
            {{#if isCurrentUserRaisingHand}}
              {{> makeButton btn_class="lowerHand navbarIconToggleActive navbarButton collapseSectionButton" i_class="ion-android-hand" rel="tooltip" data_placement="bottom" title="Lower your hand"}}
            {{else}}
              {{> makeButton btn_class="raiseHand navbarButton collapseSectionButton" i_class="ion-android-hand" rel="tooltip" data_placement="bottom" title="Raise your hand"}}
            {{/if}}

            {{> recordingStatus}}
          </div>
        </div>
      </div>
      <div class="navbarTitle navbarSection"><span>{{getMeetingName}}</span></div>
      <div class="navbarSettingsButtons navbarSection">
        <!-- {{> makeButton id="userId" btn_class="settingsIcon navbarButton" i_class="glyphicon glyphicon-cog" rel="tooltip" data_placement="bottom" title="Settings"}} -->
        {{> makeButton btn_class="signOutIcon navbarButton" i_class="glyphicon glyphicon-log-out" rel="tooltip" data_placement="bottom" title="Logout"}}
      </div>
    </div>
    <div class="navbarFiller"></div>
  {{else}}
    {{> makeButton id="navbarMinimizedButton" btn_class="hideNavbarIcon navbarMinimizedButtonSmall" i_class="glyphicon glyphicon-chevron-down" rel="tooltip" data_placement="bottom" title="Display Navbar"}}
  {{/if}}
</template>
<!-- dialog that presents user with audio options
     contains microphone and listen only with icons -->
<template name="joinAudioDialog">
  <div id="joinAudioDialog" title="How do you want to join the audio?">
    <hr class="joinAudioDialogHr"/>
    <div style="float:left; border-right: 2px solid darkgrey; width: 50%; height: 100%">
      <i class="icon ion-mic-a joinAudioDialogIcon"></i>
      <br/>
      <button id="microphone" class="joinAudioDialogButton">Microphone</button>
    </div>
    <div style="float:left; width: 50%">
      <i class="icon ion-volume-high joinAudioDialogIcon"></i>
      <br/>
      <button id="listen_only" class="joinAudioDialogButton">Listen Only</button>
    </div>
    <br style="clear:both;"/><br/>
    <hr style="margin: 10px; border: 1px solid darkgrey;" />
  </div>
</template>

<template name="main">
  {{setTitle}}
  <body>
    <div id="dialog" title="Confirm Logout">
      <p>Are you sure you want to log out?</p>
    </div>
    {{> joinAudioDialog}}
    <div id="notification">
      <div id="browser-icon-container"></div>
      <p id="notification-text"></p>
    </div>

    <div id="main" class="mainContainer row-fluid">
      {{#if isDisconnected}}
        {{>status}}
      {{else}}
        <div>{{> header}}</div>
        {{> whiteboard id="whiteboard" title=getWhiteboardTitle name="whiteboard"}}
        {{> chatbar id="chat" title="Chat" name="chatbar"}}
        {{> usersList id="users" name="usersList"}}
        <audio id="remote-media" autoplay="autoplay"></audio>
        {{> footer}}
      {{/if}}
      <div id='shield'></div>
    </div>
    {{#if isPortraitMobile}}
      {{> slidingMenu}}
    {{/if}}
  </body>
</template>

<template name="recordingStatus">
  <!-- Recording status of the meeting -->
  {{#with getCurrentMeeting}}
    {{#if intendedForRecording}}
      {{#if currentlyBeingRecorded}}
        <button class="recordingStatus recordingStatusTrue" rel="tooltip" data-placement="bottom" title="This Meeting is Being Recorded"><span class="glyphicon glyphicon-record"></span> Recording</button>
      {{else}}
        <button class="recordingStatus recordingStatusFalse" rel="tooltip" data-placement="bottom" title="This Meeting is Not Currently Being Recorded"><span class="glyphicon glyphicon-record"></span></button>
      {{/if}}
    {{/if}}
  {{/with}}
</template>

<template name='slidingMenu'>
  <div class="sliding-menu" id="sliding-menu">
    <div class="slideSection">
      {{#if getInSession "display_usersList"}}
        {{> makeButton btn_class="navbarIconToggleActive usersListIcon slideButton" i_class="glyphicon glyphicon-user" rel="tooltip" data_placement="right" title="Hide List of Users"}}
      {{else}}
        {{> makeButton btn_class="usersListIcon slideButton" i_class="glyphicon glyphicon-user" rel="tooltip" data_placement="right" title="Show List of Users"}}
      {{/if}}

      {{#if getInSession "display_whiteboard"}}
        {{> makeButton btn_class="navbarIconToggleActive whiteboardIcon slideButton" i_class="ion-easel" rel="tooltip" data_placement="right" title="Hide Whiteboard"}}
      {{else}}
        {{> makeButton btn_class="whiteboardIcon slideButton" i_class="ion-easel" rel="tooltip" data_placement="right" title="Show Whiteboard"}}
      {{/if}}

      {{#if getInSession "display_chatbar"}}
        {{> makeButton btn_class="navbarIconToggleActive chatBarIcon slideButton" i_class="glyphicon glyphicon-comment" rel="tooltip" data_placement="right" title="Hide Message Pane"}}
      {{else}}
        {{> makeButton btn_class="chatBarIcon slideButton" i_class="glyphicon glyphicon-comment" rel="tooltip" data_placement="right" title="Show Message Pane"}}
      {{/if}}

      {{#if amIInAudio}}
        {{> makeButton btn_class="navbarIconToggleActive audioFeedIcon slideButton audioButton leaveAudioButton" i_class="ion-volume-mute" sharingAudio=true rel="tooltip" data_placement="bottom" title="Leave Audio Call"}}
      {{else}}
        {{> makeButton btn_class="audioFeedIcon slideButton audioButton joinAudioButton" i_class="glyphicon glyphicon-headphones" sharingAudio=false rel="tooltip" data_placement="bottom" title="Join Audio Call"}}
      {{/if}}

      {{#if isCurrentUserRaisingHand}}
        {{> makeButton btn_class="lowerHand navbarIconToggleActive slideButton" i_class="ion-android-hand" rel="tooltip" data_placement="right" title="Lower your hand"}}
      {{else}}
        {{> makeButton btn_class="raiseHand slideButton" i_class="ion-android-hand" rel="tooltip" data_placement="right" title="Raise your hand"}}
      {{/if}}
    </div>
  </div>
</template>

