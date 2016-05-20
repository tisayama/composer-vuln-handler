<?php
/**
 * Created by PhpStorm.
 * User: TeppeiIsayama
 * Date: 2016/05/16
 * Time: 21:46
 */

namespace CPEBach\Entity;


class Cve
{

    protected $cveName;

    protected $summary;

    protected $cvssV2Score;

    protected $referenceUrl;

    public function __construct($cveName, $summary)
    {
        $this->cveName = $cveName;
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getCveName()
    {
        return $this->cveName;
    }

    /**
     * @param mixed $cveName
     */
    public function setCveName($cveName)
    {
        $this->cveName = $cveName;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getCvssV2Score()
    {
        return $this->cvssV2Score;
    }

    /**
     * @param mixed $cvssV2Score
     */
    public function setCvssV2Score($cvssV2Score)
    {
        $this->cvssV2Score = $cvssV2Score;
    }

    /**
     * @return string|null
     */
    public function getReferenceUrl()
    {
        return $this->referenceUrl;
    }

    /**
     * @param string|null $referenceUrl
     */
    public function setReferenceUrl($referenceUrl)
    {
        $this->referenceUrl = $referenceUrl;
    }



}