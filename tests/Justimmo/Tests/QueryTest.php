<?php

namespace Justimmo\Tests;

use Justimmo\Api\JustimmoNullApi;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\NullWrapper;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    /**
     * @var \Justimmo\Model\RealtyQuery
     */
    protected $query;

    public function setUp(): void
    {
        $this->query = new RealtyQuery(new JustimmoNullApi(), new NullWrapper(), new RealtyMapper());
    }

    public function testSingle()
    {
        $this->query->clear();

        $this->query->filterByPrice(455);

        $this->assertEquals([
            'filter' => [
                'preis' => 455,
            ],
        ], $this->query->getParams());
    }

    public function testRange()
    {
        $this->query->clear();
        $this->query->filterByPrice(['min' => 455, 'max' => 800]);

        $this->assertEquals([
            'filter' => [
                'preis_von' => 455,
                'preis_bis' => 800,
            ],
        ], $this->query->getParams());
    }

    public function testMultiple()
    {
        $this->query->clear();
        $this->query->filterByPrice([455, 800]);

        $this->assertEquals([
            'filter' => [
                'preis' => [455, 800],
            ],
        ], $this->query->getParams());
    }

    public function testOrderBy()
    {
        $this->query->clear();
        $this->query->orderBy('Price');

        $this->assertEquals([
            'orderby' => 'preis',
            'ordertype' => 'asc',
        ], $this->query->getParams());

        $this->query->orderBy('Price', 'desc');
        $this->assertEquals([
            'orderby' => 'preis',
            'ordertype' => 'desc',
        ], $this->query->getParams());
    }

    public function testOrderByCall()
    {
        $this->query->clear();

        $this->query->orderByPrice();
        $this->assertEquals([
            'orderby' => 'preis',
            'ordertype' => 'asc',
        ], $this->query->getParams());

        $this->query->orderByPrice('desc');
        $this->assertEquals([
            'orderby' => 'preis',
            'ordertype' => 'desc',
        ], $this->query->getParams());

        $this->query->orderByCreatedAt('desc');
        $this->assertEquals([
            'orderby' => 'created_at',
            'ordertype' => 'desc',
        ], $this->query->getParams());
    }

    public function testFull()
    {
        $this->query->clear();
        $this->query->set('culture', 'de')
            ->orderBy('Price')
            ->setLimit(10)
            ->setOffset(5)
            ->filterByFederalStateId(5)
            ->filterByPrice(['min' => 455, 'max' => 800])
            ->filterByZipCode(['1020', '1030']);

        $this->assertEquals([
            'limit' => 10,
            'offset' => 5,
            'culture' => 'de',
            'orderby' => 'preis',
            'ordertype' => 'asc',
            'filter' => [
                'preis_von' => 455,
                'preis_bis' => 800,
                'bundesland_id' => 5,
                'plz' => ['1020', '1030'],
            ],
        ], $this->query->getParams());

    }

    public function testMagicMethodMapping()
    {
        $this->query->clear();
        $this->query
            ->filterByParentId(12345)
            ->filterByRealtySystemType('area');

        $this->assertEquals([
            'filter' => [
                'parent_id' => 12345,
                'realty_type' => 'area',
            ],
        ], $this->query->getParams());
    }
}
