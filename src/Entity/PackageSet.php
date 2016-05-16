<?php
/**
 * Created by PhpStorm.
 * User: TeppeiIsayama
 * Date: 2016/05/16
 * Time: 21:29
 */

namespace CPEBach\Entity;


use Traversable;

/**
 * Class PackageSet
 * @package CPEBach\Entity
 *
 * @property \ArrayObject $packages
 */
class PackageSet implements \IteratorAggregate
{

    protected $packages;

    public function __construct()
    {
        $this->packages = new \ArrayObject();
    }

    public function add(Package $package)
    {

        $this->packages->append($package);

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
        return new \ArrayIterator($this->packages);
    }
}