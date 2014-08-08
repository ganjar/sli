<?php
/**
 * SLIVersion
 * @author Ganjar@ukr.net
 */

class SLIVersion {

    const SITE_URL = 'sli.su';
    const CURRENT_VERSION = '1.1';

    public static function getDownloadLink()
    {
        return 'http://'.self::SITE_URL.'/download/';
    }

    public static function getDonateLink()
    {
        return 'http://'.self::SITE_URL.'/#donate';
    }

    public static function getContactLink()
    {
        return 'http://'.self::SITE_URL.'/contacts/';
    }

    public static function getDocumentationLink()
    {
        return 'http://'.self::SITE_URL.'/documentation/';
    }

    public static function getContactEmail()
    {
        return 'support@sli.su';
    }
}