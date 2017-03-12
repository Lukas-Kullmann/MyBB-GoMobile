<?php

/**
 * Get the valid mobile style that the user selected
 * Checks if the mobile style that the user selected ($userMobileStyle) is a valid mobile style - if not, returns the first valid mobile style
 * @param int[] $styleList      List of all valid mobile styles
 * @param int $userMobileStyle  The mobile style that the user selected
 * @return int
 */
function gomobile_get_user_mobilestyle($styleList, $userMobileStyle)
{
    if(!in_array($userMobileStyle, $styleList, true))
    {
        $userMobileStyle = $styleList[0];
    }

    return $userMobileStyle;
}