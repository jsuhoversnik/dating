<?php
/**
 * Created by PhpStorm.
 * User: Jake
 * Date: 1/30/2019
 * Time: 10:45 PM
 */





/* Validate outdoor activities checklist
 *
 * @param String item
 * @return boolean
 */
function validOutdoor($item)
{
    global $f3;
    return in_array($item,$f3->get('outdoor'));
}

/* Validate indoor activities checklist
 *
 * @param String item
 * @return boolean
 */
function validIndoor($item)
{
    global $f3;
    return in_array($item,$f3->get('indoor'));
}