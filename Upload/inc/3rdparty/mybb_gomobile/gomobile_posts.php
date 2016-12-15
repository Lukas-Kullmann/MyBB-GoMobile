<?php
// Was this post sent from GoMobile?

function gomobile_posts($p)
{
    global $mybb;

    $is_mobile = intval($mybb->input['mobile']) === 1 ? 1 : 0;

    // If so, we're going to store it for future use
    $p->post_insert_data['mobile'] = $is_mobile;

    return $p;
}