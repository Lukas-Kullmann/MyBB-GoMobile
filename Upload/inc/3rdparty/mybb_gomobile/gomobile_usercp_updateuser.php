<?php

function gomobile_usercp_updateuser()
{
    global $mybb, $db, $user;

    if($mybb->request_method == "post")
    {
        // We're saving our options here
        $update_array = array(
            "usemobileversion" => (int) $mybb->input['usemobileversion'],
            'mobilestyle'      => (int) $mybb->input['mobilestyle']
        );

        $db->update_query("users", $update_array, "uid = '".$user['uid']."'");
    }
}