<?php

function gomobile_usercp_resetstyle()
{
    global $mybb;

    if(isset($GLOBALS['gmb_orig_style']))
    {
        // Because we override this above, reset it to the original
        $mybb->user['style'] = $GLOBALS['gmb_orig_style'];
    }
}