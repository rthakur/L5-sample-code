<?php

namespace App\Services;

use Illuminate\Validation\Validator as IlluminateValidator;

class ValidatorExtended extends IlluminateValidator {

    private $_custom_messages = array(
        "vat" => "The :attribute is not a valid VAT.",
        "phonenumber" => "The :attribute number is not valid.",
        //place for more customized messages
    );

    public function __construct( $translator, $data, $rules, $messages = array(), $customAttributes = array() ) {
        parent::__construct( $translator, $data, $rules, $messages, $customAttributes );

        $this->_set_custom_stuff();
    }

    protected function _set_custom_stuff() {
        //setup our custom error messages
        $this->setCustomMessages( $this->_custom_messages );
    }

    protected function validateVat( $attribute, $value ) {
        // You can extend your RegEx with other Countries, if you like

        return (bool) preg_match( "/((DK|FI|HU|LU|MT|SI)(-)?\d{8})|(GB(-)?\d{9}|\d{12}|(GD|HA)\d{3})|(CY(-)?\d{8}[A_Z])|((EC)(-)?\d{13})|((BO)(-)?\d{7})|(BE(-)?\d{10})|((BE|EE|DE|EL|LT|PT|BG|RS|BY|IL)(-)?\d{9})|((CA|ID)(-)?\d{15})|(IS(-)?\d{6})|((PL|SK|TR)(-)?\d{10})|((IT|HR|LV|AR|PE|IN)(-)?\d{11})|((LT|SE|PH)(-)?\d{12})|(AT(-)?U\d{8})|(CY(-)?\d{8}[A-Z])|((RO|CZ)(-)?\d{8,10})|(FR(-)?[\dA-HJ-NP-Z]{2}\d{9})|(IE(-)?[A-Z\d]\d{5}[A-Z])|(IE(-)?[A-Z\d]{8}|[A-Z\d]{9})|(NL(-)?\d{9}B\d{2})|(ES(-)?[A-Z\d]\d{7}[A-Z\d])|(VE(-)?[JGVE\d]\(-)?\d{9}|(AL(-)?[JK\d]\d{8}[A-Z\d])|(IS(-)?\d{5,7})|(DO(-)?\d{9})|(DO(-)?\d{11})|(SM(-)?\d{5})|(RU(-)?\d{10,12})/", $value );
    }
    
    protected function validatePhonenumber( $attribute, $value)
    {
        //15 for Argentina
        // For Israel: The minimum phone number length (excluding the country code) is 8 digits. - Official Source (Country Code 972)
        // For Sweden : The minimum number length (excluding the country code) is 7 digits. - Official Source‎ (country code 46)
        // For Solomon Islands its 5 for fixed line phones. - Source (country code 677)

        return (bool) preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) <= 15 && strlen($value) >= 5;
    }
    
    //place for more protected functions for other custom validations

}