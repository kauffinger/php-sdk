<?php

namespace Justimmo\Model\Mapper\V1;

class RealtyInquiryMapper extends AbstractMapper
{
    protected function getMapping()
    {
        //there is no mapping
        return [];
    }

    protected function getFilterMapping()
    {
        return [
            'firstName' => 'vorname',
            'realtyId' => 'objekt_id',
            'lastName' => 'nachname',
            'email' => 'email',
            'phone' => 'tel',
            'message' => 'message',
            'street' => 'strasse',
            'zipCode' => 'plz',
            'city' => 'ort',
            'country' => 'land',
            'title' => 'titel',
            'salutationId' => 'anrede_id',
            'category' => 'kategorie',
        ];
    }

    public function getSetter($apiPropertyName)
    {

    }

    public function getProperty($apiPropertyName)
    {

    }

    public function getType($apiPropertyName)
    {

    }
}
