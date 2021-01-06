<?php

namespace VendorFPF;

if (!\interface_exists('VendorFPF\\WPDesk_Translable')) {
    require_once 'Translable.php';
}
interface WPDesk_Translatable extends \VendorFPF\WPDesk_Translable
{
    /** @return string */
    public function get_text_domain();
}
