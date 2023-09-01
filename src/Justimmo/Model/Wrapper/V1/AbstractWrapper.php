<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\Mapper\MapperInterface;
use Justimmo\Model\Wrapper\WrapperInterface;

abstract class AbstractWrapper implements WrapperInterface
{
    /**
     * @var MapperInterface
     */
    protected $mapper;

    public function __construct(MapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * maps an mapping array between a SimpleXML and Objekt
     */
    protected function map($mapping, \SimpleXMLElement $xml, $objekt)
    {
        foreach ($mapping as $key) {
            if (isset($xml->$key)) {
                $setter = $this->mapper->getSetter($key);
                $objekt->$setter($this->cast($xml->$key, $this->mapper->getType($key)));
            }
        }
    }

    /**
     * casts a simple xml element to a type
     *
     * @param  string  $type
     * @return float|int|null|string|\DateTime
     */
    protected function cast(\SimpleXMLElement $xml, $type = 'string')
    {
        switch ($type) {
            case 'string':
                return (string) $xml;
            case 'int':
                return (int) $xml;
            case 'double':
                return (float) $xml;
            case 'boolean' :
                return (bool) ((string) $xml);
            case 'datetime':
                $date = (string) $xml;
                if (empty($date)) {
                    return null;
                }

                return new \DateTime($date);
            default:
                return $xml;
        }
    }

    /**
     * converts the attributes of a SimpleXmlElement to an array
     *
     *
     * @return array
     */
    protected function attributesToArray(\SimpleXMLElement $xml)
    {
        $array = (array) $xml;

        return array_key_exists('@attributes', $array) ? $array['@attributes'] : [];
    }

    /**
     * @param  \Justimmo\Model\Realty|\Justimmo\Model\Employee|\Justimmo\Model\Project  $attachmentAware
     *
     * @internal param array $data
     */
    protected function mapAttachmentGroup(\SimpleXMLElement $xml, $attachmentAware, $type = null, $forceGroup = null)
    {
        foreach ($xml as $anhang) {
            $data = (array) $anhang->daten;
            $attributes = $this->attributesToArray($anhang);
            $group = $forceGroup ?: (array_key_exists('gruppe', $attributes) ? $attributes['gruppe'] : null);
            if (array_key_exists('pfad', $data)) {
                $path = isset($data['orig']) ? $data['orig'] : $data['pfad'];
                $attachment = new Attachment($path, $type, $group);
                $attachment->mergeData($data);
                if (isset($anhang->vorschaubild)) {
                    $attachment->mergeData(['vorschaubild' => $this->cast($anhang->vorschaubild)]);
                }
                $attachment->setTitle($this->cast($anhang->anhangtitel));
                $attachment->setOriginalFilename($this->cast($anhang->original_dateiname));
                $attachmentAware->addAttachment($attachment);
            } elseif (isset($anhang->pfad)) {
                if (isset($anhang->gruppe)) {
                    $group = strtoupper($this->cast($anhang->gruppe));
                }
                $path = isset($anhang->orig) ? $anhang->orig : $anhang->pfad;
                $attachment = new Attachment($this->cast($path), $type, $group);
                if (isset($anhang->vorschaubild)) {
                    $attachment->mergeData(['vorschaubild' => $this->cast($anhang->vorschaubild)]);
                }
                $attachment->setTitle($this->cast($anhang->titel));
                $attachment->setOriginalFilename($this->cast($anhang->original_dateiname));
                $attachmentAware->addAttachment($attachment);
            }
        }
    }
}
