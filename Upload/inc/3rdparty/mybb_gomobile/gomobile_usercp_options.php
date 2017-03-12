<?php

// Add GoMobile-related options to the UCP
function gomobile_usercp_options()
{
    global $user;

    inject_use_mobile_option();
    inject_style_option();

    // We're just viewing the page
    $GLOBALS['$usemobileversioncheck'] = '';

    if($user['usemobileversion']){
        $GLOBALS['$usemobileversioncheck'] = "checked=\"checked\"";
    }
}

function inject_use_mobile_option()
{
    global $templates;

    $usercp_option =
        '</tr>
        <tr>
            <td valign="top" width="1">
                <input type="checkbox" class="checkbox" name="usemobileversion" id="usemobileversion" value="1" {$GLOBALS[\'$usemobileversioncheck\']} />
            </td>
            <td>
                <span class="smalltext"><label for="usemobileversion">{$lang->gomobile_use_mobile_version}</span>
            </td>';

    $find = '{$lang->show_codebuttons}</label></span></td>';

    $templates->cache['usercp_options'] = str_replace($find, $find.$usercp_option, $templates->cache['usercp_options']);
}

function inject_style_option()
{
    global $templates, $mybb, $lang;

    $styleOption = $templates->get('usercp_options_style');
    $stylelist = get_theme_selector((int) $mybb->user['mobilestyle']);

    $styleOption = str_replace('{$lang->style}', '{$lang->gomobile_mobile_style}', $styleOption);
    eval('$styleOption = "' . $styleOption . '";');

    $templates->cache['usercp_options'] = str_replace('{$board_style}', '{$board_style}' . $styleOption, $templates->cache['usercp_options']);
}
/**
 * Get the theme selector
 * @param int $selected
 * @return string
 */
function get_theme_selector($selected)
{
    global $db, $templates, $lang;

    $allowedStyles = gomobile_validstyles();

    $query = $db->simple_select(
        'themes',
        'tid, name, pid, allowedgroups',
        'pid!=\'0\' AND tid IN (' . implode(',', $allowedStyles) . ')'
    );

    $num_themes = 0;
    $depth = '';

    while($theme = $db->fetch_array($query))
    {
        $sel = "";

        // Show theme if allowed
        if(is_member($theme['allowedgroups']) || $theme['allowedgroups'] == "all")
        {
            if((int) $theme['tid'] === $selected)
            {
                $sel = " selected=\"selected\"";
            }

            $theme['name'] = htmlspecialchars_uni($theme['name']);
            eval("\$themeselect_option .= \"".$templates->get("usercp_themeselector_option")."\";");

            ++$num_themes;
        }
    }

    $themeselect = '';
    $name = 'mobilestyle';

    eval("\$themeselect = \"".$templates->get("usercp_themeselector")."\";");

    return $themeselect;
}
