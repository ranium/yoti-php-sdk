<?php
namespace YotiSandbox\Http;

use Yoti\Entity\Profile;
use YotiSandbox\Entity\SandboxAnchor;
use YotiSandbox\Entity\SandboxAttribute;
use YotiSandbox\Entity\SandboxAgeVerification;

class RequestBuilder
{
    /**
     * @var string
     */
    private $rememberMeId;

    /**
     * @var array
     */
    private $sandboxAttributes = [];

    /**
     * @param string $value
     */
    public function setRememberMeId($value)
    {
        $this->rememberMeId = $value;
    }

    public function setFullName($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_FULL_NAME,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function setFamilyName($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_FAMILY_NAME,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function setGivenNames($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_GIVEN_NAMES,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function setDateOfBirth(\DateTime $dateTime, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_DATE_OF_BIRTH,
            $dateTime->format('d-m-Y'),
            '',
            $optional,
            $anchors
        ));
    }

    public function setGender($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_GENDER,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function setNationality($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_NATIONALITY,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function setPhoneNumber($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_PHONE_NUMBER,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function setSelfie($value, $optional = 'false', array $anchors = [])
    {
        $base64Selfie = base64_encode($value);
        $this->setBase64Selfie($base64Selfie, $optional, $anchors);
    }

    public function setBase64Selfie($value, $optional = 'true', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_SELFIE,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function setEmailAddress($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_EMAIL_ADDRESS,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function setPostalAddress($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_POSTAL_ADDRESS,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function setStructuredPostalAddress($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_STRUCTURED_POSTAL_ADDRESS,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function setDocumentDetails($value, $optional = 'true', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_DOCUMENT_DETAILS,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function setAgeVerification(SandboxAgeVerification $ageVerification)
    {
        $this->addAttribute($ageVerification);
    }

    private function addAttribute(SandboxAttribute $attribute)
    {
        $this->sandboxAttributes[] = [
            'name' => $attribute->getName(),
            'value' => $attribute->getValue(),
            'derivation' => $attribute->getDerivation(),
            'optional' => $attribute->getOptional(),
            'anchors' => $this->formatAnchors($attribute->getAnchors())
        ];
    }

    private function formatAnchors(array $anchors)
    {
        $anchorsList = [];
        foreach ($anchors as $anchor) {
            /** @var SandboxAnchor $anchor */
            if (!($anchor instanceof SandboxAnchor)) {
                continue;
            }
            $anchorsList[] = [
                'type' => $anchor->getType(),
                'value' => $anchor->getValue(),
                'sub_type' => $anchor->getSubtype(),
                'timestamp' => $anchor->getTimestamp()
            ];
        }
        return $anchorsList;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $derivation
     *  Empty value means there is no derivation for this attribute
     * @param string $optional
     *  'false' value means this attribute is required
     * @param array $anchors
     *
     * @return SandboxAttribute
     */
    private function createAttribute($name, $value, $derivation, $optional, array $anchors)
    {
        return new SandboxAttribute($name, $value, $derivation, $optional, $anchors);
    }

    /**
     * @return TokenRequest
     */
    public function createRequest()
    {
        return new TokenRequest($this->rememberMeId, $this->sandboxAttributes);
    }
}