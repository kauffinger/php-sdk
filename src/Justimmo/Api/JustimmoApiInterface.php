<?php

namespace Justimmo\Api;

interface JustimmoApiInterface
{
    /**
     * make a call to the realty list with a set of given params
     *
     *
     * @return string
     */
    public function callRealtyList(array $params = []);

    /**
     * make a call to the realty ids with a set of given params
     *
     *
     * @return string
     */
    public function callRealtyIds(array $params = []);

    /**
     * calls the detail information of a single realty
     *
     *
     * @return string
     */
    public function callRealtyDetail($pk, array $params = []);

    /**
     * make a call to the team list with a set of given params
     *
     *
     * @return string
     */
    public function callEmployeeList(array $params = []);

    /**
     * calls the detail information of a single employee
     *
     *
     * @return string
     */
    public function callEmployeeDetail($pk);

    /**
     * Make a call to the employee ids with a set of given params
     *
     *
     * @return string
     */
    public function callEmployeeIds(array $params = []);

    /**
     * make a call to the project list with a set of given params
     *
     *
     * @return mixed
     */
    public function callProjectList(array $params = []);

    /**
     * calls the detail information of a single project
     *
     *
     * @return string
     */
    public function callProjectDetail($pk, array $params = []);

    /**
     * Make a call to the project ids with a set of given params
     *
     *
     * @return string
     */
    public function callProjectIds(array $params = []);

    /**
     * retrieve a countrie list
     *
     *
     * @return string
     */
    public function callCountries(array $params = []);

    /**
     * retrieve a federal states list
     *
     *
     * @return string
     */
    public function callFederalStates(array $params = []);

    /**
     * retrieve a zip code list
     *
     *
     * @return string
     */
    public function callZipCodes(array $params = []);

    /**
     * retrieve a region list
     *
     *
     * @return string
     */
    public function callRegions(array $params = []);

    /**
     * Retrieve tenant data
     *
     *
     * @return string
     */
    public function callTenant(array $params = []);

    /**
     * retrieve a realty type list
     *
     *
     * @return string
     */
    public function callRealtyTypes(array $params = []);

    /**
     * retrieve a realtycategories list
     *
     *
     * @return string
     */
    public function callRealtyCategories(array $params = []);

    /**
     * retrieves the expose for a realty
     *
     *
     * @return string
     */
    public function callExpose($pk, $type = 'Default');

    /**
     * makes a request to the api to create a realty inquiry to the contact person of the realty
     *
     *
     * @return string
     */
    public function postRealtyInquiry(array $params = []);
}
