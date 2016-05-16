<?php
/**
 * Created by PhpStorm.
 * User: TeppeiIsayama
 * Date: 2016/05/16
 * Time: 21:15
 */

namespace CPEBach\Composer;


use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;

/**
 * Class VulnHandlerPlugin
 * @package CPEBach\Composer
 *
 * @property Composer $Composer
 * @property IOInterface $IO
 */
class VulnHandlerPlugin implements PluginInterface, EventSubscriberInterface
{

    protected $composer;
    protected $io;

    /**
     * Apply plugin modifications to Composer
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        /** @var Composer composer */
        $this->composer = $composer;

        $this->io = $io;


    }

    public static function getSubscribedEvents()
    {

        return array(
            'post-autoload-dump' => 'referPackages'

        );
    }

    public function referPackages(Event $event)
    {

        $localRepo = $event->getComposer()->getRepositoryManager()->getLocalRepository();

        foreach ($localRepo->getPackages() as $package) {

            var_dump($package);

        }

    }

}


