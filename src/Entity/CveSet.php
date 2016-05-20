<?php
/**
 * Created by PhpStorm.
 * User: TeppeiIsayama
 * Date: 2016/05/16
 * Time: 21:46
 */

namespace CPEBach\Entity;


use Traversable;

class CveSet implements \IteratorAggregate, \Countable
{


    protected $cves;

    public function __construct()
    {
        $this->cves = new \ArrayObject();
    }

    public function add(Package $package)
    {

        $this->cves->append($package);

    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->cves);
    }


    public function setArray($cvesArray)
    {

        if (!is_array($cvesArray)) {
            return;
        }

        foreach ($cvesArray as $cve) {

            if (
                (
                    isset($cve['name'])
                    && ($name = $cve['name'])
                )
                && (
                    isset($cve['summary'])
                    && ($summary = $cve['summary'])
                )
            ) {


                $cveEntity = new Cve($name, $summary);

                if (isset($cve['cvss_v2_score'])
                    && ($v2Score = $cve['cvss_v2_score'])
                ) {

                    $cveEntity->setCvssV2Score($v2Score);
                }

                if (isset($cve['reference_url'])
                    && $cve['reference_url']
                ) {
                    $cveEntity->setReferenceUrl($cve['reference_url']);
                }

                $this->cves->append($cveEntity);


            }

        }

    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->cves);
    }
}