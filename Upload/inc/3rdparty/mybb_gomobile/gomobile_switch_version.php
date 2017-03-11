<?php
// Switch to the mobile view via the footer link

function gomobile_switch_version()
{
    global $lang, $mybb;

    if($mybb->input['action'] != "switch_version")
    {
        return;
    }

    $url = "index.php";

    if(isset($_SERVER['HTTP_REFERER']))
    {
        $url = htmlentities($_SERVER['HTTP_REFERER']);
    }


    if(md5($mybb->post_code) != $mybb->input['my_post_key'])
    {
        redirect($url, $lang->invalid_post_code);
    }


    if($mybb->input['do'] == "full")
    {
        // Disable the mobile theme
        my_setcookie("gomobile", "disabled", -1);
    }

    elseif($mybb->input['do'] == "clear")
    {
        // Clear the mobile theme cookie
        my_setcookie("gomobile", "nothing", -1);
    }
    else
    {
        // Assume we're wanting to switch to the mobile version
        my_setcookie("gomobile", "force", -1);
    }

    redirect($url, $lang->gomobile_switched_version);
}