<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Wrapper\BasicDataWrapperInterface;

class BasicDataWrapper implements BasicDataWrapperInterface
{
    public function transformCountries($data)
    {
        $xml = new \SimpleXMLElement($data);

        $return = [];
        foreach ($xml->land as $land) {
            $return[(int) $land->id] = [
                'name' => (string) $land->name,
                'iso2' => (string) $land->iso2,
                'iso3' => (string) $land->iso3,
            ];
        }

        return $return;
    }

    public function transformFederalStates($data)
    {
        $xml = new \SimpleXMLElement($data);

        $return = [];
        foreach ($xml->bundesland as $bundesland) {
            $return[(int) $bundesland->id] = [
                'name' => (string) $bundesland->name,
                'countryId' => (int) $bundesland->landid,
                'fipsCode' => (string) $bundesland->fipscode,
            ];
        }

        return $return;
    }

    public function transformZipCodes($data)
    {
        $xml = new \SimpleXMLElement($data);

        $return = [];
        foreach ($xml->postleitzahl as $postleitzahl) {
            $return[] = [
                'id' => ((string) $postleitzahl->id === '') ? null : (int) $postleitzahl->id,
                'countryId' => ((string) $postleitzahl->landid === '') ? null : (int) $postleitzahl->landid,
                'regionId' => ((string) $postleitzahl->regionid === '') ? null : (int) $postleitzahl->regionid,
                'zipCode' => trim((string) $postleitzahl->plz),
                'place' => trim((string) $postleitzahl->ort),
                'federalStateId' => ((string) $postleitzahl->bundeslandid === '') ? null : (int) $postleitzahl->bundeslandid,
            ];
        }

        return $return;
    }

    public function transformRegions($data)
    {
        $xml = new \SimpleXMLElement($data);

        $return = [];
        foreach ($xml->region as $region) {
            $return[(int) $region->id] = trim((string) $region->name);
        }

        return $return;
    }

    public function transformRealtyTypes($data)
    {
        $xml = new \SimpleXMLElement($data);

        $return = [];
        foreach ($xml->objektart as $objektart) {
            $return[(int) $objektart->id] = [
                'name' => (string) $objektart->name,
                'key' => (string) $objektart->key,
                'attribute' => (string) $objektart->attributename,
            ];
        }

        return $return;
    }

    public function transformRealtyCategories($data)
    {
        $xml = new \SimpleXMLElement($data);

        $return = [];
        foreach ($xml->objektkategorie as $objektkategorie) {
            $return[(int) $objektkategorie->id] = [
                'name' => (string) $objektkategorie->name,
                'sortableRank' => (int) $objektkategorie->sortablerank,
            ];
        }

        return $return;
    }

    public function transformTenant($data)
    {
        $xml = new \SimpleXMLElement($data);

        return (array) $xml->tenant;
    }
}
