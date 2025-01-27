<?php

namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Realty;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Tests\TestCase;

/**
 * Class V1ObjektWrapperTest
 *
 * @todo provide more fixtures with different values and values not available here
 */
class RealtyWrapperTest extends TestCase
{
    /**
     * @var RealtyWrapper
     */
    private $wrapper;

    public function setUp(): void
    {
        $this->wrapper = new RealtyWrapper(new RealtyMapper());
    }

    public function testTransformList()
    {
        $list = $this->wrapper->transformList($this->getFixtures('v1/realty_list.xml'));

        $this->assertInstanceOf('\Justimmo\Pager\ListPager', $list);
        $this->assertEquals(7, $list->count());
        $this->assertEquals(7, $list->getNbResults());
        $this->assertFalse($list->haveToPaginate());

        /** @var Realty $entry */
        $entry = $list[0];
        $this->assertInstanceOf('\Justimmo\Model\Realty', $entry);
        $this->assertEquals(436942, $entry->getId());
        $this->assertEquals('aktiv', $entry->getStatus());
        $this->assertEquals(5, $entry->getStatusId());
        $this->assertEmpty($entry->getProcuredAt());
        $this->assertInstanceOf('\DateTime', $entry->getCreatedAt(null));
        $this->assertEquals('2015-06-30 11:46:27', $entry->getCreatedAt());
        $this->assertEquals('30.06.2015', $entry->getCreatedAt('d.m.Y'));
        $this->assertInstanceOf('\DateTime', $entry->getUpdatedAt(null));
        $this->assertEquals('2015-12-17 11:08:39', $entry->getUpdatedAt());
        $this->assertEquals('17.12.2015', $entry->getUpdatedAt('d.m.Y'));
        $this->assertEquals(16.4100297, $entry->getLongitudePrecise());
        $this->assertEquals(48.2545373, $entry->getLatitudePrecise());
        $this->assertEquals(16.4100298, $entry->getLongitudeFuzzy());
        $this->assertEquals(48.2545374, $entry->getLatitudeFuzzy());
        $this->assertEquals(250, $entry->getRadiusFuzzy());
        $this->assertEquals(Realty::ORIENTATION_NORTH_EAST_SOUTH, $entry->getOrientation());
        $this->assertEquals('simple', $entry->getRealtySystemType());
        $this->assertEmpty($entry->getParentId());
        $this->assertTrue($entry->getShowInSearch());
        $this->assertTrue($entry->getIsReference());

        $entry = $list[1];
        $this->assertInstanceOf('\Justimmo\Model\Realty', $entry);
        $this->assertEquals('aktiv', $entry->getStatus());
        $this->assertEquals(5, $entry->getStatusId());
        $this->assertEmpty($entry->getProcuredAt());
        $this->assertEquals('commercial', $entry->getRealtySystemType());
        $this->assertEmpty($entry->getParentId());
        $this->assertFalse($entry->getShowInSearch());
        $this->assertFalse($entry->getIsReference());

        $entry = $list[2];
        $this->assertInstanceOf('\Justimmo\Model\Realty', $entry);
        $this->assertEquals(195425, $entry->getId());
        $this->assertEquals('vermittelt', $entry->getStatus());
        $this->assertEquals(8, $entry->getStatusId());
        $this->assertInstanceOf('\DateTime', $entry->getProcuredAt(null));
        $this->assertEquals('2014-10-09', $entry->getProcuredAt());
        $this->assertEquals('09.10.2014', $entry->getProcuredAt('d.m.Y'));
        $this->assertEquals('area', $entry->getRealtySystemType());
        $this->assertEquals(195429, $entry->getParentId());
        $this->assertFalse($entry->getIsReference());

        $entry = $list[5];
        $this->assertEmpty($entry->getProcuredAt());

        $entry = $list[6];
        $this->assertEquals(1400, $entry->getNetRent());
        $this->assertEquals(1200, $entry->getRentNet());
        $this->assertEquals(1440, $entry->getRentGross());
        $this->assertEquals('percent', $entry->getRentVatType());
        $this->assertEquals(20, $entry->getRentVatInput());
        $this->assertEquals(20, $entry->getRentVat());
        $this->assertEquals(240, $entry->getRentVatValue());
    }

    public function testTransformSingle()
    {
        /** @var \Justimmo\Model\Realty $objekt */
        $objekt = $this->wrapper->transformSingle($this->getFixtures('v1/realty_detail.xml'));

        $this->assertInstanceOf('\Justimmo\Model\Realty', $objekt);

        $this->assertEquals(195439, $objekt->getId());
        $this->assertEquals(51, $objekt->getProjectId());
        $this->assertEquals(34, $objekt->getPropertyNumber());
        $this->assertEquals('DEMOOBJEKT! Elegantes Büro neben Bristol und Oper', $objekt->getTitle());
        $this->assertEquals('<p>Sonstige angaben</p>', $objekt->getOtherInformation());
        $this->assertStringContainsString('ausgestattetes 1 bis 2 Personenbüro', $objekt->getDescription());
        $this->assertNull($objekt->getTier());
        $this->assertEquals(1, $objekt->getDoorNumber());
        $this->assertEquals(5, $objekt->getStair());
        $this->assertEquals(1030, $objekt->getZipCode());
        $this->assertEquals('Wien', $objekt->getPlace());
        $this->assertEquals('buero_praxen', $objekt->getRealtyType());
        $this->assertEquals(5, $objekt->getRealtyTypeId());
        $this->assertEquals('Büro / Praxis', $objekt->getRealtyTypeName());
        $this->assertEquals('PRAXIS', $objekt->getSubRealtyType());
        $this->assertEquals(28, $objekt->getSubRealtyTypeId());
        $this->assertEquals('Praxis', $objekt->getSubRealtyTypeName());
        $this->assertEquals('simple', $objekt->getRealtySystemType());
        $this->assertEmpty($objekt->getParentId());
        $this->assertTrue($objekt->getIsReference());

        $this->assertEquals([
            'WOHNEN' => false,
            'GEWERBE' => true,
            'ANLAGE' => false,
        ], $objekt->getOccupancy());
        $this->assertEquals([
            'KAUF' => true,
            'MIETE_PACHT' => false,
        ], $objekt->getMarketingType());
        $this->assertEquals('Stephansplatz', $objekt->getStreet());
        $this->assertEquals('Am Graben', $objekt->getRegionalAddition());
        $this->assertEmpty($objekt->getHallway());
        $this->assertEmpty($objekt->getLandParcel());
        $this->assertEmpty($objekt->getDistrict());
        $this->assertEquals('AUT', $objekt->getCountry());
        $this->assertEquals(48.2087105, $objekt->getLatitude());
        $this->assertEquals(16.3726546, $objekt->getLongitude());
        $this->assertEquals($objekt->getFloorArea(), 150);
        $this->assertEquals($objekt->getFloorAreaFrom(), 20);
        $this->assertEquals($objekt->getSurfaceArea(), 150);
        $this->assertNull($objekt->getLivingArea());
        $this->assertNull($objekt->getTotalArea());
        $this->assertEquals(377, $objekt->getSalesArea());
        $this->assertEquals(113, $objekt->getShortTermArea());
        $this->assertEquals(115, $objekt->getWeightedArea());
        $this->assertEquals(117, $objekt->getUndevelopedAtticArea());
        $this->assertEquals($objekt->getGarageCount(), 1);
        $this->assertEquals($objekt->getGarageArea(), 20.57);
        $this->assertEquals($objekt->getParkingCount(), 2);
        $this->assertEquals($objekt->getParkingArea(), 36.85);
        $this->assertEquals($objekt->getCeilingHeight(), 3.5);
        $this->assertEquals(3.25, $objekt->getHallHeight());

        $this->assertEquals(450000, $objekt->getPurchasePrice());
        $this->assertEquals(3000, $objekt->getPurchasePricePerSqm());
        $this->assertEquals(500, $objekt->getAdditionalCharges());
        $this->assertEquals(126, $objekt->getHeatingCosts());
        $this->assertEquals('EUR', $objekt->getCurrency());
        $this->assertEquals('16.200,00 € inkl. 20% USt.', $objekt->getCommission());
        $this->assertNull($objekt->getBuildingSubsidies());
        $this->assertNull($objekt->getYield());
        $this->assertNull($objekt->getNetEarningMonthly());
        $this->assertNull($objekt->getNetEarningYearly());
        $this->assertNull($objekt->getTotalRentVat());
        $this->assertEquals(3.4, $objekt->getTransferTax());
        $this->assertEquals(1.4, $objekt->getLandRegistration());
        $this->assertEquals('2,16%', $objekt->getContractEstablishmentCosts());
        $this->assertNull($objekt->getSurety());
        $this->assertEquals($objekt->getSuretyText(), '3 Bruttomonatsmieten');
        $this->assertNull($objekt->getCompensation());
        $this->assertEquals(10, $objekt->getRentPerSqmFrom());
        $this->assertEquals(13, $objekt->getRentPerSqm());
        $this->assertEquals(2, $objekt->getOperatingCostsPerSqmFrom());
        $this->assertEquals(3, $objekt->getOperatingCostsPerSqm());
        $this->assertEquals(40000, $objekt->getFinancialContribution());
        $this->assertEquals(12000, $objekt->getInfrastructureProvision());
        $this->assertEquals(906.6, $objekt->getMonthlyCosts());

        $costs = $objekt->getAdditionalCosts();
        $this->assertEquals(4, count($costs));

        $this->assertInstanceOf('\Justimmo\Model\AdditionalCosts', $costs['betriebskosten']);
        $this->assertEquals('Betriebskosten', $costs['betriebskosten']->getName());
        $this->assertEquals(500, $costs['betriebskosten']->getNet());
        $this->assertEquals(750, $costs['betriebskosten']->getGross());
        $this->assertEquals(50, $costs['betriebskosten']->getVat());
        $this->assertEquals('numeric', $costs['betriebskosten']->getVatType());
        $this->assertEquals(250, $costs['betriebskosten']->getVatValue());
        $this->assertEquals(250, $costs['betriebskosten']->getVatInput());
        $this->assertFalse($costs['betriebskosten']->getOptional());

        $this->assertInstanceOf('\Justimmo\Model\AdditionalCosts', $costs['heizkosten']);
        $this->assertEquals('Heizkosten', $costs['heizkosten']->getName());
        $this->assertEquals(126, $costs['heizkosten']->getNet());
        $this->assertEquals(138.6, $costs['heizkosten']->getGross());
        $this->assertEquals(10, $costs['heizkosten']->getVat());
        $this->assertEquals('percent', $costs['heizkosten']->getVatType());
        $this->assertEquals(12.6, $costs['heizkosten']->getVatValue());
        $this->assertEquals(10, $costs['heizkosten']->getVatInput());
        $this->assertFalse($costs['heizkosten']->getOptional());

        $this->assertInstanceOf('\Justimmo\Model\AdditionalCosts', $costs['zusatzkosten_6']);
        $this->assertEquals('Garage', $costs['zusatzkosten_6']->getName());
        $this->assertEquals(150, $costs['zusatzkosten_6']->getNet());
        $this->assertEquals(150, $costs['zusatzkosten_6']->getGross());
        $this->assertEquals(0, $costs['zusatzkosten_6']->getVat());
        $this->assertEquals('percent', $costs['zusatzkosten_6']->getVatType());
        $this->assertEquals(0, $costs['zusatzkosten_6']->getVatValue());
        $this->assertEquals(0, $costs['zusatzkosten_6']->getVatInput());
        $this->assertTrue($costs['zusatzkosten_6']->getOptional());

        $this->assertInstanceOf('\Justimmo\Model\AdditionalCosts', $costs['zusatzkosten_8']);
        $this->assertEquals('Liftkosten', $costs['zusatzkosten_8']->getName());
        $this->assertEquals(15, $costs['zusatzkosten_8']->getNet());
        $this->assertEquals(18, $costs['zusatzkosten_8']->getGross());
        $this->assertEquals(20, $costs['zusatzkosten_8']->getVat());
        $this->assertEquals('percent', $costs['zusatzkosten_8']->getVatType());
        $this->assertEquals(3, $costs['zusatzkosten_8']->getVatValue());
        $this->assertEquals(20, $costs['zusatzkosten_8']->getVatInput());
        $this->assertFalse($costs['zusatzkosten_8']->getOptional());

        $garages = $objekt->getGarages();
        $this->assertEquals(4, count($garages));

        $this->assertInstanceOf('\Justimmo\Model\Garage', $garages[0]);
        $this->assertEquals('garage', $garages[0]->getType());
        $this->assertEquals('Garage', $garages[0]->getName());
        $this->assertEquals(50, $garages[0]->getQuantity());
        $this->assertEquals('rent', $garages[0]->getMarketingType());
        $this->assertEquals(250, $garages[0]->getNet());
        $this->assertEquals(275, $garages[0]->getGross());
        $this->assertEquals(25, $garages[0]->getVat());
        $this->assertEquals('numeric', $garages[0]->getVatType());
        $this->assertEquals(25, $garages[0]->getVatValue());

        $this->assertInstanceOf('\Justimmo\Model\Garage', $garages[1]);
        $this->assertEquals('outdoor_court', $garages[1]->getType());
        $this->assertEquals('Stellplatz', $garages[1]->getName());
        $this->assertEquals(100, $garages[1]->getQuantity());
        $this->assertEquals('rent', $garages[1]->getMarketingType());
        $this->assertEquals(60, $garages[1]->getNet());
        $this->assertEquals(66, $garages[1]->getGross());
        $this->assertEquals(6, $garages[1]->getVat());
        $this->assertEquals('numeric', $garages[1]->getVatType());
        $this->assertEquals(6, $garages[1]->getVatValue());

        $this->assertInstanceOf('\Justimmo\Model\Garage', $garages[2]);
        $this->assertEquals('carport', $garages[2]->getType());
        $this->assertEquals('Carport', $garages[2]->getName());
        $this->assertEquals(30, $garages[2]->getQuantity());
        $this->assertEquals('buy', $garages[2]->getMarketingType());
        $this->assertEquals(6500, $garages[2]->getNet());
        $this->assertEquals(7150, $garages[2]->getGross());
        $this->assertEquals(10, $garages[2]->getVat());
        $this->assertEquals('percent', $garages[2]->getVatType());
        $this->assertEquals(650, $garages[2]->getVatValue());

        $this->assertInstanceOf('\Justimmo\Model\Garage', $garages[3]);
        $this->assertEquals('car_park', $garages[3]->getType());
        $this->assertEquals('Parkhaus', $garages[3]->getName());
        $this->assertEquals(200, $garages[3]->getQuantity());
        $this->assertEquals('buy', $garages[3]->getMarketingType());
        $this->assertEquals(10000, $garages[3]->getNet());
        $this->assertEquals(11000, $garages[3]->getGross());
        $this->assertEquals(10, $garages[3]->getVat());
        $this->assertEquals('percent', $garages[3]->getVatType());
        $this->assertEquals(1000, $garages[3]->getVatValue());

        $this->assertEquals(11, count($objekt->getPictures()));
        $this->assertEquals(10, count($objekt->getPictures(null)));
        $this->assertEquals(1, count($objekt->getPictures('TITELBILD')));
        $this->assertEquals(0, count($objekt->getDocuments()));
        $this->assertEquals(0, count($objekt->getVideos()));

        $pictures = $objekt->getPictures();
        $picture = $pictures[0];
        $this->assertEquals('http://files.justimmo.at/public/pic/big/AHA0s6aAaT.jpg', $picture->getUrl());
        $this->assertEquals('http://files.justimmo.at/public/pic/small/AHA0s6aAaT.jpg', $picture->getUrl('small'));
        $this->assertEquals('jpg', $picture->getExtension());
        $this->assertEquals('picture', $picture->getType());

        $links = $objekt->getLinks();
        $link = $links[0];
        $this->assertEquals(1, count($links));
        $this->assertEquals('JUSTIMMO', $link->getTitle());
        $this->assertEquals('http://www.justimmo.at', $link->getUrl());

        $energiepass = $objekt->getEnergyPass();

        $this->assertInstanceOf('\Justimmo\Model\EnergyPass', $energiepass);
        $this->assertEquals('BEDARF', $energiepass->getEpart());
        $this->assertInstanceOf('\DateTime', $energiepass->getValidUntil(null));
        $this->assertEquals('2012-09-12', $energiepass->getValidUntil('Y-m-d'));
        $this->assertEquals('B', $energiepass->getEnergyEfficiencyFactorClass());
        $this->assertEquals(0.96, $energiepass->getEnergyEfficiencyFactorValue());
        $this->assertEquals('B', $energiepass->getThermalHeatRequirementClass());
        $this->assertEquals(44, $energiepass->getThermalHeatRequirementValue());

        $this->assertEquals([
            'ausricht_balkon_terrasse' => 'NORD',
            'bad' => ['FENSTER', 'WANNE', 'DUSCHE', 'BIDET', 'PISSOIR'],
            'boden' => ['PARKETT', 'STEIN', 'TEPPICH', 'MARMOR'],
            'fahrstuhl' => 'PERSONEN',
            'heizungsart' => 'FUSSBODEN',
            'sicherheitstechnik' => ['ALARMANLAGE', 'POLIZEIRUF'],
            'kabel_sat_tv' => 'kabel_sat_tv',
            'kamin' => 'kamin',
            'kueche' => 'OFFEN',
            'sauna' => 'sauna',
            'stellplatzart' => 'TIEFGARAGE',
            'befeuerung' => 'SOLAR',
        ], $objekt->getEquipment());

        $contact = $objekt->getContact();
        $this->assertInstanceOf('\Justimmo\Model\Employee', $contact);
        $this->assertEquals(50, $contact->getId());
        $this->assertEquals(123, $contact->getNumber());
        $this->assertEquals('Alexander', $contact->getFirstName());
        $this->assertEquals('Diem', $contact->getLastName());
        $this->assertEquals('+43 1 888 74 72', $contact->getMobile());
        $this->assertEquals('+43 676 123 45 67', $contact->getPhone());
        $this->assertEquals('+43 767 765 43 21', $contact->getFax());
        $this->assertEquals('a.diem@bgcc.at', $contact->getEmail());
        $this->assertEquals(1, count($contact->getAttachments()));
        $this->assertEquals('von der Stange', $contact->getSuffix());

        $this->assertEquals('Freitext 1', $objekt->getFreetext1());
        $this->assertNull($objekt->getFreetext2());
        $this->assertNull($objekt->getFreetext3());
        $this->assertEquals('Freitext 4', $objekt->getFreetext4());
        $this->assertEquals('Im schönen Grünen', $objekt->getLocality());

        $this->assertEquals(2, count($objekt->getCategories()));
        $this->assertEquals([
            19 => 'Demokategorie 1',
            85 => 'Demokategorie 2',
        ], $objekt->getCategories());

        $this->assertEquals('November 2015', $objekt->getAvailableFrom());

        $this->assertEquals('aktiv', $objekt->getStatus());
        $this->assertEquals(5, $objekt->getStatusId());
        $this->assertInstanceOf('\DateTime', $objekt->getProcuredAt(null));
        $this->assertEquals('2015-11-11', $objekt->getProcuredAt());
        $this->assertEquals('11.11.2015', $objekt->getProcuredAt('d.m.Y'));

        $this->assertInstanceOf('\DateTime', $objekt->getCreatedAt(null));
        $this->assertEquals('2014-12-10 15:10:23', $objekt->getCreatedAt());
        $this->assertEquals('10.12.2014', $objekt->getCreatedAt('d.m.Y'));
        $this->assertInstanceOf('\DateTime', $objekt->getUpdatedAt(null));
        $this->assertEquals('2015-09-10 16:10:23', $objekt->getUpdatedAt());
        $this->assertEquals('10.09.2015', $objekt->getUpdatedAt('d.m.Y'));

        $this->assertEquals(5, $objekt->getRentDuration());
        $this->assertEquals('month', $objekt->getRentDurationType());

        $this->assertEquals(355, $objekt->getBuildableArea());

        $this->assertEquals('Neubau', $objekt->getAge());
        $this->assertEquals('Neubau', $objekt->getStyleOfBuilding());

        $this->assertEquals(1, $objekt->getStyleOfBuildingId());

        $this->assertEquals(16.4100297, $objekt->getLongitudePrecise());
        $this->assertEquals(48.2545373, $objekt->getLatitudePrecise());
        $this->assertEquals(16.4100298, $objekt->getLongitudeFuzzy());
        $this->assertEquals(48.2545374, $objekt->getLatitudeFuzzy());
        $this->assertEquals(250, $objekt->getRadiusFuzzy());
        $this->assertEquals(Realty::ORIENTATION_SOUTH_EAST_WEST, $objekt->getOrientation());

        $this->assertEquals(2, $objekt->getOwnershipTypeId());
    }

    public function testTransformSingleNullValuesAndUnlimitedRent()
    {
        /** @var \Justimmo\Model\Realty $objekt */
        $objekt = $this->wrapper->transformSingle($this->getFixtures('v1/realty_detail_2.xml'));

        $this->assertInstanceOf('\Justimmo\Model\Realty', $objekt);

        $this->assertNull($objekt->getSalesArea());
        $this->assertNull($objekt->getShortTermArea());
        $this->assertNull($objekt->getWeightedArea());
        $this->assertNull($objekt->getUndevelopedAtticArea());

        $this->assertNull($objekt->getHallHeight());

        $this->assertEquals(0, $objekt->getRentDuration());
        $this->assertEquals('unlimited', $objekt->getRentDurationType());
    }
}
