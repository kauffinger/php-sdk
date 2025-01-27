<?php

namespace Justimmo\Model\Mapper\V1;

class BasicDataMapper extends AbstractMapper
{
    protected function getMapping()
    {
        //there is no mapping
        return [];
    }

    protected function getFilterMapping()
    {
        return [
            'all' => 'alle',
            'country' => 'land',
            'federalState' => 'bundesland',
        ];
    }

    public function getSetter($apiPropertyName)
    {
        //no support for objects
    }

    public function getProperty($apiPropertyName)
    {
        //no support for objects
    }

    public function getType($apiPropertyName)
    {
        //no type casting
    }
}
