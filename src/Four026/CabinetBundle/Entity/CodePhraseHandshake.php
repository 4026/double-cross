<?php
/**
 * Class to specify the data necessary in a request from the user to assign them a specified
 * partner using a code phrase.
 */

namespace Four026\CabinetBundle\Entity;


class CodePhraseHandshake
{
    /**
     * The name of the user's prospective partner.
     * @var string
     */
    private $partner_name;

    /**
     * The code phrase being used to authenticate the partner.
     * @var string
     */
    private $code_phrase;

    /**
     * @return string
     */
    public function getPartnerName()
    {
        return $this->partner_name;
    }

    /**
     * @param string $partner_name
     */
    public function setPartnerName($partner_name)
    {
        $this->partner_name = $partner_name;
    }

    /**
     * @return string
     */
    public function getCodePhrase()
    {
        return $this->code_phrase;
    }

    /**
     * @param string $code_phrase
     */
    public function setCodePhrase($code_phrase)
    {
        $this->code_phrase = $code_phrase;
    }
}