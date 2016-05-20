<?php
/**
 * Created by PhpStorm.
 * User: TeppeiIsayama
 * Date: 2016/05/16
 * Time: 21:38
 */

namespace CPEBach\VulnHandler;


use Composer\IO\IOInterface;
use CPEBach\Entity\Package;
use CPEBach\Entity\PackageSet;

class Reference
{

    public function referencePackages(PackageSet $packageSet, IOInterface $io, $apiUrl = null)
    {

        if (! $apiUrl) {
            $apiUrl = 'https://php-bach.org';
        }
        $apiUrlFormat = $apiUrl . '/p/%s.json';

        //cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);

        /** @var Package $package */
        foreach ($packageSet as $package) {

            $packageName = $package->getPackageName();
            $versionName = $package->getVersion();

            $url = sprintf($apiUrlFormat, $packageName);





            curl_setopt($ch, CURLOPT_URL, $url);
            $response = curl_exec($ch);

            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $message = '<info>Checking:</info> ' . $packageName . ' (' . $versionName . ') ...';
            $io->write($message);

            if ('200' === (string) $code) {

                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE); // ヘッダサイズ取得
                $body = substr($response, $header_size); // bodyだけ切り出し

                if ($this->parseResponse($package, $body)) {
                    $io->overwrite('<info>Done.</info>');
                } else {
                    $io->overwrite('<fg=white;bg=magenta>Version not found.</>');
                }
            } elseif ('404' === (string) $code) {
                $io->overwrite('<fg=white;bg=magenta>Package not found.</>');
            } else {
                $io->overwriteError($message . '<error>Connection error.</error>');
            }



        }
            curl_close($ch);

    }

    protected function parseResponse(Package $package, $responseStr)
    {

        $responseArray = json_decode($responseStr, true);

        if (isset($responseArray['packages'])) {

            $packageArray = $responseArray['packages'];


            if (isset($packageArray[$package->getPackageName()])) {

                $versionsArray = $packageArray[$package->getPackageName()];


                if (isset($versionsArray[$package->getVersion()]['cves'])) {

                    $cvesArray = $versionsArray[$package->getVersion()]['cves'];


                    if (is_array($cvesArray) && count($cvesArray)) {

                        $package->getCves()->setArray($cvesArray);

                    }

                }

                return true;

            }

        }

        return false;

    }

}