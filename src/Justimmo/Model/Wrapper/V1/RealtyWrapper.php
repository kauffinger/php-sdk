<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\EnergyPass;
use Justimmo\Model\Realty;
use Justimmo\Model\AdditionalCosts;
use Justimmo\Pager\ListPager;

class RealtyWrapper extends AbstractWrapper
{
    /**
     * simple attributes mostly used in list call
     *
     * @var array
     */
    protected $simpleMapping = array(
        'id',
        'objektnummer',
        'titel',
        'dreizeiler',
        'naehe',
        'objektbeschreibung',
        'etage',
        'tuernummer',
        'plz',
        'ort',
        'kaufpreis',
        'gesamtmiete',
        'nutzflaeche',
        'grundflaeche',
        'projekt_id',
        'status',
    );

    protected $geoMapping = array(
        'ort',
        'plz',
        'regionaler_zusatz',
        'anzahl_etagen',
        'etage',
        'gemarkung',
        'flur',
        'flurstueck',
        'bundesland',
        'strasse',
        'tuernummer',
        'hausnummer',
    );

    protected $preisMapping = array(
        'kaufpreis',
        'nettokaltmiete',
        'nebenkosten',
        'heizkosten',
        'wohnbaufoerderung',
        'rendite',
        'nettoertrag_jaehrlich',
        'nettoertrag_monatlich',
        'gesamtmiete_ust',
        'grunderwerbssteuer',
        'grundbucheintragung',
        'vertragserrichtungsgebuehr',
        'kaution',
    );

    protected $flaechenMapping = array(
        'nutzflaeche',
        'grundflaeche',
        'wohnflaeche',
        'gesamtflaeche',
        'anzahl_zimmer',
        'anzahl_badezimmer',
        'anzahl_sep_wc',
        'anzahl_balkon_terrassen',
        'balkon_terrasse_flaeche',
        'anzahl_balkone',
        'anzahl_terrassen',
        'gartenflaeche',
        'kellerflaeche',
        'bueroflaeche',
        'lagerflaeche',
        'anzahl_loggias',
        'loggias_flaeche',
        'balkons_flaeche',
        'terrassen_flaeche',
        'anzahl_garagen',
        'anzahl_abstellraum',
    );

    protected $energyMapping = array(
        'epass_hwbwert',
        'epass_hwbklasse',
        'epass_fgeewert',
        'epass_fgeeklasse',
    );

    public function transformList($data)
    {
        $xml = new \SimpleXMLElement($data);

        $transformed = new ListPager();
        $transformed->setNbResults((int) $xml->{'query-result'}->count);

        if (isset($xml->immobilie)) {
            foreach ($xml->immobilie as $immobilie) {
                $objekt = $this->transformSingle($immobilie->asXML());
                $transformed->append($objekt);
            }
        }
        $transformed->setMaxPerPage($transformed->count());

        return $transformed;
    }

    /**
     * @param $data
     *
     * @return Objekt
     */
    public function transformSingle($data)
    {
        $xml = new \SimpleXMLElement($data);

        if (isset($xml->immobilie)) {
            $xml = $xml->immobilie;
        }

        $objekt = new Realty();

        //basic attributes from list view
        $this->map($this->simpleMapping, $xml, $objekt);

        //list object attachment mapping
        if (isset($xml->erstes_bild)) {
            $objekt->addAttachment(new Attachment((string) $xml->erstes_bild));
        }
        if (isset($xml->zweites_bild)) {
            $objekt->addAttachment(new Attachment((string) $xml->zweites_bild));
        }

        //detailed attributes from detail view, OpenImmo
        if (isset($xml->verwaltung_techn)) {
            $objekt->setId((int) $xml->verwaltung_techn->objektnr_intern);
            $objekt->setPropertyNumber((string) $xml->verwaltung_techn->objektnr_extern);
            $objekt->setProjectId((int) $xml->verwaltung_techn->projekt_id);
        }

        if (isset($xml->verwaltung_objekt)) {
            $objekt->setStatus($this->cast($xml->verwaltung_objekt->status));
        }

        if (isset($xml->objektkategorie)) {
            if (isset($xml->objektkategorie->objektart)) {
                $objekt->setRealtyType((string) $xml->objektkategorie->objektart->children()->getName());
            }
            if (isset($xml->objektkategorie->nutzungsart)) {
                $objekt->setOccupancy($this->attributesToArray($xml->objektkategorie->nutzungsart->attributes()));
            }
            if (isset($xml->objektkategorie->vermarktungsart)) {
                $objekt->setMarketingType($this->attributesToArray($xml->objektkategorie->vermarktungsart->attributes()));
            }
        }

        if (isset($xml->freitexte)) {
            $objekt->setTitle((string) $xml->freitexte->objekttitel);
            $objekt->setEquipmentDescription((string) $xml->freitexte->ausstatt_beschr);
            $objekt->setDescription((string) $xml->freitexte->objektbeschreibung);
        }

        if (isset($xml->geo)) {
            $this->map($this->geoMapping, $xml->geo, $objekt);

            if (isset($xml->geo->geokoordinaten)) {
                $coord = $this->attributesToArray($xml->geo->geokoordinaten->attributes());
                $objekt->setLatitude((double) $coord['breitengrad']);
                $objekt->setLongitude((double) $coord['laengengrad']);
            }

            if (isset($xml->geo->land)) {
                $iso = $this->attributesToArray($xml->geo->land->attributes());
                if (array_key_exists('iso_land', $iso)) {
                    $objekt->setCountry((string) $iso['iso_land']);
                }
            }
        }

        if (isset($xml->preise)) {
            $this->map($this->preisMapping, $xml->preise, $objekt);
            $objekt->setTotalRent($this->cast($xml->preise->warmmiete, 'double'));

            if (isset($xml->preise->waehrung)) {
                $iso = $this->attributesToArray($xml->preise->waehrung->attributes());
                if (array_key_exists('iso_waehrung', $iso)) {
                    $objekt->setCurrency((string) $iso['iso_waehrung']);
                }
            }

            if (isset($xml->preise->zusatzkosten)) {
                foreach ($xml->preise->zusatzkosten[0] as $key => $zusatzkosten) {
                    $name = isset($zusatzkosten->name) ? $zusatzkosten->name : $key;
                    $objekt->addAdditionalCosts($key, new AdditionalCosts((string) $name, (double) $zusatzkosten->brutto, (double) $zusatzkosten->netto, (double) $zusatzkosten->ust));
                }
            }
        }

        if (isset($xml->anhaenge) && isset($xml->anhaenge->anhang)) {
            $this->mapAttachmentGroup($xml->anhaenge->anhang, $objekt);
        }

        if (isset($xml->dokumente) && isset($xml->dokumente->dokument)) {
            $this->mapAttachmentGroup($xml->dokumente->dokument, $objekt, 'document');
        }

        if (isset($xml->videos) && isset($xml->videos->video)) {
            $this->mapAttachmentGroup($xml->videos->video, $objekt, 'video');
        }

        if (isset($xml->bilder360) && isset($xml->bilder360->pfad)) {
            foreach ($xml->bilder360->pfad as $anhang) {
                $attachment = new Attachment($this->cast($anhang), 'bilder360');
                $objekt->addAttachment($attachment);
            }
        }

        if (isset($xml->flaechen)) {
            $this->map($this->flaechenMapping, $xml->flaechen, $objekt);
            $objekt->setSurfaceArea($this->cast($xml->flaechen->grundstuecksflaeche));
        }

        if (isset($xml->zustand_angaben)) {
            $objekt->setYearBuilt($this->cast($xml->zustand_angaben->baujahr, 'int'));

            $data = $this->attributesToArray($xml->zustand_angaben->zustand);
            if (array_key_exists('zustand_art', $data)) {
                $objekt->setCondition($data['zustand_art']);
            }

            $data = $this->attributesToArray($xml->zustand_angaben->alter);
            if (array_key_exists('alter_attr', $data)) {
                $objekt->setAge($data['alter_attr']);
            }

            $data = $this->attributesToArray($xml->zustand_angaben->erschliessung);
            if (array_key_exists('erschl_attr', $data)) {
                $objekt->setInfrastructureProvision($data['zustand_art']);
            }

            if (isset($xml->zustand_angaben->energiepass)) {
                $energiepass = new EnergyPass();
                $energiepass
                    ->setEpart($this->cast($xml->zustand_angaben->energiepass->epart))
                    ->setValidUntil($this->cast($xml->zustand_angaben->energiepass->gueltig_bis, 'datetime'));

                foreach ($xml->zustand_angaben->energiepass->epart->user_defined_simplefield as $simpleField) {
                    $attributes = $this->attributesToArray($simpleField);
                    if (array_key_exists('feldname', $attributes)) {
                        $setter = $this->mapper->getSetter($attributes['feldname']);
                        $energiepass->$setter($this->cast($simpleField));
                    }
                }

                $objekt->setEnergyPass($energiepass);
            }

            if (isset($xml->ausstattung[0])) {
                /** @var \SimpleXMLElement $element */
                foreach ($xml->ausstattung[0] as $key => $element) {
                    if ((int) $element === 1) {
                        $objekt->addEquipment($key, $key);
                    } elseif ($element->attributes()->count()) {
                        $attributes = $this->attributesToArray($element);
                        $value      = array();
                        foreach ($attributes as $k => $v) {
                            if ($v == 1) {
                                $value[] = $k;
                            } else {
                                $value[$k][] = $v;
                            }
                        }
                        $objekt->addEquipment($key, count($value) > 1 ? $value : $value[0]);
                    } else {
                        $objekt->addEquipment($key, (string) $element);
                    }
                }
            }

        }

        return $objekt;
    }

    /**
     * @param \SimpleXMLElement      $xml
     * @param null                   $type
     *
     * @param \Justimmo\Model\Realty $objekt
     *
     * @internal param array $data
     * @return \Justimmo\Model\Attachment|null
     */
    protected function mapAttachmentGroup(\SimpleXMLElement $xml, Realty $objekt, $type = null)
    {
        foreach ($xml as $anhang) {
            $data = (array) $anhang->daten;
            if (array_key_exists('pfad', $data)) {
                $attachment = new Attachment($data['pfad'], $type);
                $attachment->setData($data);
                $attachment->setTitle($this->cast($anhang->anhangtitel));
                $objekt->addAttachment($attachment);
            } elseif (isset($anhang->pfad)) {
                $attachment = new Attachment($this->cast($anhang->pfad), $type);
                $attachment->setTitle($this->cast($anhang->titel));
                $objekt->addAttachment($attachment);
            }
        }
    }
}