<?php

// This function checks if the UA string matches the database
// If so, it displays the GoMobile theme

function gomobile_forcetheme()
{
    global $db, $mybb, $plugins, $current_page;

    // We're going to work around the per forum theme setting by altering the $current_page value throughout global.php
    // Then set it back to what it's supposed to be at global_end so it doesn't muck anything up (hopefully)

    gomobile_load_language();

    if($mybb->session->is_spider == false){
        // Force some changes to our footer, but only if we're not a bot
        $GLOBALS['gmb_orig_style'] = intval($mybb->user['style']);
        $GLOBALS['gmb_post_key'] = md5($mybb->post_code);
        $plugins->add_hook("global_end", "gomobile_forcefooter");
    }

    if(
        // Has the user chosen to disable GoMobile completely?
        isset($mybb->user['usemobileversion']) &&
        $mybb->user['usemobileversion'] == 0 &&
        $mybb->user['uid'] &&
        $mybb->cookies['gomobile'] != "force" ||
        // Or has the user temporarily disabled GoMobile via cookies?
        $mybb->cookies['gomobile'] == "disabled"
    )
    {
        return;
    }

    $userMobileStyle = gomobile_get_user_mobilestyle(gomobile_validstyles(), (int) $mybb->user['mobilestyle']);

    // Is the admin using theme permission settings?
    // If so, check them

    if($mybb->settings['gomobile_permstoggle'] == 1)
    {
        // Fetch the theme permissions from the database
        $tquery = $db->simple_select("themes", "*", "tid = '{$userMobileStyle}'");
        $tperms = $db->fetch_field($tquery, "allowedgroups");

        // Also explode our user's additional groups

        $userag = array();

        if($mybb->user['additionalgroups'])
        {
            $userag = explode(",", $mybb->user['additionalgroups']);
        }

        // If the user doesn't have permission to use the theme...

        if($tperms != "all")
        {
            $canuse = explode(",", $tperms);

            if(!in_array($mybb->user['usergroup'], $canuse) && !in_array($userag, $canuse)){
                return;
            }

        }

    }

    $switch = false;

    if($mybb->cookies['gomobile'] == "force")
    {
        // if the mobile style is forced, it will get changed anyways
        // so the user agent string is not checked
        $switch = true;
    }
    else
    {
        // Grab the strings and put them into an array
        $list = $mybb->settings['gomobile_strings'];
        $replace = array("\n", "\r");
        $list = str_replace($replace, ",", $list);
        $list = str_replace(",,", ",", $list);
        $list = explode(",", $list);

        foreach($list as $uastring)
        {
            // Switch to GoMobile if the UA matches our list

            if($uastring && stristr($_SERVER['HTTP_USER_AGENT'], $uastring))
            {
                $switch = true;

                break;
            }

        }
    }

    if($switch)
    {
        $mybb->user['style'] = $userMobileStyle;

        $validPages = array(
            "showthread.php",
            "forumdisplay.php",
            "newthread.php",
            "newreply.php",
            "ratethread.php",
            "editpost.php",
            "polls.php",
            "sendthread.php",
            "printthread.php",
            "moderation.php"
        );

        if(in_array($current_page, $validPages))
        {
            $current_page = "gomobile_temp";
        }
    }

    $GLOBALS['gmb_uses_mobile_version'] = $switch;
}
