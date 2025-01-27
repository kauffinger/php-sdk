<?php

namespace Justimmo\Model;

class Employee
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var null|string
     */
    protected $category = null;

    /**
     * @var array
     */
    protected $attachments = [];

    /**
     * @var null|string
     */
    protected $firstName = null;

    /**
     * @var null|string
     */
    protected $lastName = null;

    /**
     * @var null|string
     */
    protected $phone = null;

    /**
     * @var null|string
     */
    protected $email = null;

    /**
     * @var null|string
     */
    protected $position = null;

    /**
     * @var null|string
     */
    protected $mobile = null;

    /**
     * @var null|string
     */
    protected $fax = null;

    /**
     * @var null|string
     */
    protected $title = null;

    /**
     * @var null|string
     */
    protected $suffix = null;

    /**
     * @var null|string
     */
    protected $biography = null;

    /**
     * @var null|string
     */
    protected $number = null;

    /**
     * @var null|string
     */
    protected $street = null;

    /**
     * @var null|string
     */
    protected $postal = null;

    /**
     * @var null|string
     */
    protected $city = null;

    /**
     * @var null|string
     */
    protected $url = null;

    /**
     * @param  array  $attachments
     * @return $this
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * @return Attachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return $this
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @return $this
     */
    public function setMobile($handy)
    {
        $this->mobile = $handy;

        return $this;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param  mixed  $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return $this
     */
    public function setLastName($nachname)
    {
        $this->lastName = $nachname;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return $this
     */
    public function setPhone($tel)
    {
        $this->phone = $tel;

        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return $this
     */
    public function setFirstName($vorname)
    {
        $this->firstName = $vorname;

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param  null|string|bool  $group
     * @return array
     */
    public function getAttachmentsByType($type, $group = false)
    {
        $attachments = [];

        /** @var \Justimmo\Model\Attachment $attachment */
        foreach ($this->attachments as $attachment) {
            if ($attachment->getType() == $type && ($group === false || $group == $attachment->getGroup())) {
                $attachments[] = $attachment;
            }
        }

        return $attachments;
    }

    /**
     * @param  null|string|bool  $group
     * @return Attachment[]
     */
    public function getPictures($group = false)
    {
        return $this->getAttachmentsByType('picture', $group);
    }

    /**
     * @param  null|string|bool  $group
     * @return Attachment[]
     */
    public function getVideos($group = false)
    {
        return $this->getAttachmentsByType('video', $group);
    }

    /**
     * @param  null|string|bool  $group
     * @return Attachment[]
     */
    public function getDocuments($group = false)
    {
        return $this->getAttachmentsByType('document', $group);
    }

    /**
     * @param  null|string|bool  $group
     * @return Attachment[]
     */
    public function getLinks($group = false)
    {
        return $this->getAttachmentsByType('link', $group);
    }

    /**
     * Returns the profile picture of the user
     *
     * @return Attachment
     */
    public function getProfilePicture()
    {
        $profilePictures = $this->getAttachmentsByType('picture', 'PROFILBILD');

        return ! empty($profilePictures) ? $profilePictures[0] : null;
    }

    /**
     * @return $this
     */
    public function addAttachment(Attachment $attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @return $this
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * @param  null|string  $biography
     * @return $this
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param  null|string  $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return null|string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param  null|string  $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return null|string
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * @param  null|string  $postal
     */
    public function setPostal($postal)
    {
        $this->postal = $postal;
    }

    /**
     * @return null|string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param  null|string  $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return null|string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param  null|string  $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}
