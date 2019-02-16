<?php
/**
 * @author Jake Suhoversnik
 * @version 1.0
 *
 * Extends from member class and adds interests category of information
 */
Class PremiumMember extends Member
{
    private $_inDoorInterests;
    private $_outDoorInterests;

    /**
     * PremiumMember constructor, includes parent constructor call
     * @param $_inDoorInterests
     * @param $_outDoorInterests
     */
    public function __construct($fname, $lname, $age, $gender, $phone, $email = "", $state = "", $seeking = "", $bio = "",$inDoorInterests = "", $outDoorInterests = "")
    {
        //set parent constructor values
        parent::__construct($fname, $lname, $age, $gender, $phone, $email, $state, $seeking, $bio);

        $this->_inDoorInterests = $inDoorInterests;
        $this->_outDoorInterests = $outDoorInterests;
    }

    /**
     * @return string
     */
    public function getInDoorInterests()
    {
        return $this->_inDoorInterests;
    }

    /**
     * @param string $inDoorInterests
     */
    public function setInDoorInterests($inDoorInterests)
    {
        $this->_inDoorInterests = $inDoorInterests;
    }

    /**
     * @return string
     */
    public function getOutDoorInterests()
    {
        return $this->_outDoorInterests;
    }

    /**
     * @param string $outDoorInterests
     */
    public function setOutDoorInterests($outDoorInterests)
    {
        $this->_outDoorInterests = $outDoorInterests;
    }


}