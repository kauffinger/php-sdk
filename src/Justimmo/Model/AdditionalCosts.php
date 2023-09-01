<?php

namespace Justimmo\Model;

class AdditionalCosts
{
    /**
     * @var string
     */
    protected $name;

    /**
     * the net value
     *
     * @var float
     */
    protected $net;

    /**
     * the gross value
     *
     * @var float
     */
    protected $gross;

    /**
     * the vat value in percent
     *
     * @var float
     */
    protected $vat;

    /**
     * the vat type (numeric|percent)
     *
     * @var string
     */
    protected $vatType = 'percent';

    /**
     * the calculated vat value in currency
     *
     * @var float
     */
    protected $vatValue;

    /**
     * the vat value as inputed by user
     *
     * @var float
     */
    protected $vatInput;

    /**
     * this cost is optional and has not been calculated in the overall values of the realty
     *
     * @var bool
     */
    protected $optional = false;

    /**
     * @param  string  $vatType
     * @param  bool  $optional
     */
    public function __construct($name, $gross = null, $net = null, $vat = null, $vatType = 'percent', $vatValue = null, $vatInput = null, $optional = false)
    {
        $this->gross = $gross;
        $this->name = $name;
        $this->net = $net;
        $this->vat = $vat;
        $this->vatType = $vatType;
        $this->vatValue = $vatValue;
        $this->vatInput = $vatInput;
        $this->optional = $optional;
    }

    /**
     * @param  float  $brutto
     * @return $this
     */
    public function setGross($brutto)
    {
        $this->gross = $brutto;

        return $this;
    }

    /**
     * @return float
     */
    public function getGross()
    {
        return $this->gross;
    }

    /**
     * @param  string  $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  float  $value
     * @return $this
     */
    public function setNet($value)
    {
        $this->net = $value;

        return $this;
    }

    /**
     * @return float
     */
    public function getNet()
    {
        return $this->net;
    }

    /**
     * @param  float  $value
     * @return $this
     */
    public function setVat($value)
    {
        $this->vat = $value;

        return $this;
    }

    /**
     * @return float
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @return bool
     */
    public function getOptional()
    {
        return $this->optional;
    }

    /**
     * @param  bool  $value
     * @return $this
     */
    public function setOptional($value)
    {
        $this->optional = $value;

        return $this;
    }

    /**
     * @return float
     */
    public function getVatInput()
    {
        return $this->vatInput;
    }

    /**
     * @param  float  $value
     * @return $this
     */
    public function setVatInput($value)
    {
        $this->vatInput = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getVatType()
    {
        return $this->vatType;
    }

    /**
     * @param  string  $value
     * @return $this
     */
    public function setVatType($value)
    {
        $this->vatType = $value;

        return $this;
    }

    /**
     * @return float
     */
    public function getVatValue()
    {
        return $this->vatValue;
    }

    /**
     * @param  float  $value
     * @return $this
     */
    public function setVatValue($value)
    {
        $this->vatValue = $value;

        return $this;
    }
}
