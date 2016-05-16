<?php
/**
 * Created by PhpStorm.
 * User: TeppeiIsayama
 * Date: 2016/05/16
 * Time: 21:23
 */

namespace CPEBach\Entity;

/**
 * Class Package
 * @package CPEBach\Entity
 *
 * @property string $packageName
 * @property string $version
 * @property CveSet $cves
 */
class Package
{

    protected $packageName;

    protected $version;

    protected $cves;

    public function __construct($packageName, $version)
    {

        $this->packageName = $packageName;
        $this->version = $version;

        $this->cves = new CveSet();

    }

    /**
     * @return mixed
     */
    public function getPackageName()
    {
        return $this->packageName;
    }

    /**
     * @param mixed $packageName
     */
    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return CveSet
     */
    public function getCves()
    {
        return $this->cves;
    }





}