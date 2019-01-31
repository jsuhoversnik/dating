<?php
/**
 * Created by PhpStorm.
 * User: Jake
 * Date: 1/30/2019
 * Time: 10:45 PM
 */


/* Validate String value
 *
 * @param String item
 * @return boolean
 */
function validName($item)
{
    global $f3;
    return ctype_alpha($item);
}

/* Validate given age
 *
 * @param String item
 * @return boolean
 */
function validAge($item)
{
    return ctype_digit($item) and $item > 18;
}

/* Validate given phone number is valid
 *
 * Im checking that what is entered is all numbers and of length 10,
 * 123 456 7890
 *
 * @param String item
 * @return boolean
 */
function validPhone($item)
{
    return ctype_digit($item) and strlen($item) == 10;
}

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