<?php

namespace Justimmo\Model\Mapper;

interface MapperInterface
{
    /**
     * gets the setter for a api property name
     *
     *
     * @return string
     */
    public function getSetter($apiPropertyName);

    /**
     * get the type a property has to be cast ing
     *
     *
     * @return string
     */
    public function getType($apiPropertyName);

    /**
     * get the name of the property on a model object for a api property name
     *
     *
     * @return string
     */
    public function getProperty($apiPropertyName);

    /**
     * gets a filter property name to sent to the api call for a specific model property
     *
     *
     * @return string
     */
    public function getFilterPropertyName($modelPropertyName);
}
