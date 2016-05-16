<?php
/**
 * Created by PhpStorm.
 * User: TeppeiIsayama
 * Date: 2016/05/16
 * Time: 21:38
 */

namespace CPEBach\VulnHandler;


use CPEBach\Entity\Package;
use CPEBach\Entity\PackageSet;

class Reference
{

    public function referencePackages(PackageSet $packageSet)
    {

        $apiUrlFormat = 'https://absolute-axis-824.appspot.com/p/%s.json';

        /** @var Package $package */
        foreach ($packageSet as $package) {

            $packageName = $package->getPackageName();

            $url = sprintf($apiUrlFormat, $packageName);

            $response = file_get_contents($url);


            $this->parseResponse($package, $response);


        }

    }

    protected function parseResponse(Package $package, $responseStr)
    {

        $responseArray = json_decode($responseStr, true);

        if (isset($responseArray['package'])) {

            $packageArray = $responseArray['package'];

            if (isset($packageArray[$package->getPackageName()])) {

                $versionsArray = $packageArray[$package->getPackageName()];

                if (isset($versionsArray[$package->getVersion()]['cves'])) {


                    $cvesArray = $versionsArray[$package->getVersion()]['cves'];

                    if (is_array($cvesArray) && count($cvesArray)) {

                        $package->getCves()->setArray($cvesArray);

                    }

                }

            }

        }

    }

}