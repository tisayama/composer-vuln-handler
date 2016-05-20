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
use CPEBach\Entity\Cve;
use CPEBach\Entity\Package;
use CPEBach\Entity\PackageSet;
use CPEBach\VulnHandler\Reference;

/**
 * Class VulnHandlerPlugin
 * @package CPEBach\Composer
 *
 * @property Composer $composer
 * @property IOInterface $io
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

        $this->io->write(array(
            '',
            '<fg=black;bg=cyan>VulnHandlerPlugin powered by php-bach.org</>',
            ''
        ));
        $localRepo = $event->getComposer()->getRepositoryManager()->getLocalRepository();

        $packageSet = new PackageSet();



        foreach ($localRepo->getPackages() as $package) {

            $packageSet->add(new Package($package->getPrettyName(), $package->getPrettyVersion()));

        }

        //api url
        //null is default(php-bach)
        $apiUrl = null;
        if ($event->getComposer()->getConfig()->has('vuln-handler-plugin.url')) {
            $apiUrl = (string) $event->getComposer()->getConfig()->get('vuln-handler-plugin.url');
        }

        $reference = new Reference();
        $reference->referencePackages($packageSet, $this->io, $apiUrl);

        $this->io->write(
            array(
                '==============================',
                "Results",
                '=============================='
            ));

        /** @var Package $package */
        foreach ($packageSet as $package) {

            if (count($package->getCves())) {

                //CVEsがある

                $this->io->writeError("<info>The package: \"{$package->getPackageName()}\" ({$package->getVersion()}) has following CVEs.</info>");

                    /** @var Cve $cve */
                foreach ($package->getCves() as $cve) {

                    //値域ごとに色分け
                    $cvssMessage = '   ';
                    if ($score = $cve->getCvssV2Score()) {
                        if (((float) $score) >= 7.0) {
                            $cvssMessage .= '<fg=white;bg=red>' . $score . '(HIGH)</>';
                        } elseif (((float) $score) >= 4.0) {
                            $cvssMessage .= '<fg=white;bg=blue>' . $score . '(MEDIUM)</>';
                        } elseif (((float) $score) >= 0.0) {
                            $cvssMessage .= '<fg=white;bg=yellow>' . $score . '(LOW)</>';
                        }
                    }

                    //表示メッセージを生成
                    $displayMessages = array(
                        '',
                        $cve->getCveName() . $cvssMessage,
                        'Summary:', '  ' . $cve->getSummary()
                    );
                    if ($refUrl = $cve->getReferenceUrl()) {
                        $displayMessages[] = 'Reference: ' . $refUrl;
                    }

                    $this->io->write($displayMessages);


                }

            }

        }

    }

}


