<?php

namespace Justimmo\Model;

class EnergyPass
{
    /**
     * @var string
     */
    protected $epart;

    /**
     * @var \DateTime
     */
    protected $validUntil = null;

    /**
     * @var float
     */
    protected $thermalHeatRequirementValue = null;

    /**
     * @var string
     */
    protected $thermalHeatRequirementClass = null;

    /**
     * @var float
     */
    protected $energyEfficiencyFactorValue = null;

    /**
     * @var string
     */
    protected $energyEfficiencyFactorClass = null;

    /**
     * @param  mixed  $epart
     * @return $this
     */
    public function setEpart($epart)
    {
        $this->epart = $epart;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEpart()
    {
        return $this->epart;
    }

    /**
     * @return $this
     */
    public function setEnergyEfficiencyFactorClass($fgeeKlasse)
    {
        $this->energyEfficiencyFactorClass = $fgeeKlasse;

        return $this;
    }

    public function getEnergyEfficiencyFactorClass()
    {
        return $this->energyEfficiencyFactorClass;
    }

    /**
     * @return $this
     */
    public function setEnergyEfficiencyFactorValue($fgeeWert)
    {
        $this->energyEfficiencyFactorValue = $fgeeWert;

        return $this;
    }

    public function getEnergyEfficiencyFactorValue()
    {
        return $this->energyEfficiencyFactorValue;
    }

    /**
     * @return $this
     */
    public function setValidUntil($gueltigBis)
    {
        $this->validUntil = $gueltigBis;

        return $this;
    }

    /**
     * @param  string  $format formats the date to the specific format, null returns DateTime
     * @return \DateTime|null
     */
    public function getValidUntil($format = 'Y-m-d')
    {
        if ($this->validUntil instanceof \DateTime && $format !== null) {
            return $this->validUntil->format($format);
        }

        return $this->validUntil;
    }

    /**
     * @return $this
     */
    public function setThermalHeatRequirementClass($hwbKlasse)
    {
        $this->thermalHeatRequirementClass = $hwbKlasse;

        return $this;
    }

    public function getThermalHeatRequirementClass()
    {
        return $this->thermalHeatRequirementClass;
    }

    /**
     * @return $this
     */
    public function setThermalHeatRequirementValue($hwbWert)
    {
        $this->thermalHeatRequirementValue = $hwbWert;

        return $this;
    }

    public function getThermalHeatRequirementValue()
    {
        return $this->thermalHeatRequirementValue;
    }
}
