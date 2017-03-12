<?php

// Add a link in the footer only if we're not a bot
function gomobile_forcefooter()
{
    global $lang, $footer, $mybb, $navbits;

    $footer = str_replace(
        "<a href=\"<archive_url>\">".$lang->bottomlinks_litemode."</a>",
        "<a href=\"misc.php?action=switch_version&amp;my_post_key=".$GLOBALS['gmb_post_key']."\">".$lang->gomobile_mobile_version."</a>",
        $footer
    );

    // If we have a match, override the default breadcrumb
    if(isset($GLOBALS['gmb_uses_mobile_version']) && (bool) $GLOBALS['gmb_uses_mobile_version'])
    {
        $navbits = array();
        $navbits[0]['url'] = $mybb->settings['bburl'];
        $navbits[0]['name'] = $mybb->settings['gomobile_mobile_name'];
    }
}
