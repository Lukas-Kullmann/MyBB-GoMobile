<?php

// Page numbers and links, whoop
function gomobile_showthread(){
    global $lang, $postcount, $perpage, $thread, $pagejump, $pages, $page_location;

    // Display the total number of pages

    if($pages > 0) {
        $page_location = " {$lang->gomobile_of} {$pages} ";
    }

    // If there's more than one page, display links to the first & last posts

    if($postcount > $perpage)
    {
        $pj_template = "<div class=\"float_left\" style=\"padding-top: 12px;\">
            <a href=\"".get_thread_link($thread['tid'])."\" class=\"pagination_a\">{$lang->gomobile_jump_fpost}</a>
            <a href=\"".get_thread_link($thread['tid'], 0, 'lastpost')."\" class=\"pagination_a\">{$lang->gomobile_jump_lpost}</a></div>";

        $pagejump = $pj_template;
    }
}
