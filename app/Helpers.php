<?php

declare(strict_types=1);

namespace App\Helpers;

use DateTimeZone;

if (! function_exists('get_timezones')) {
    function get_timezones(): array
    {
        return DateTimeZone::listIdentifiers(
            timezoneGroup: DateTimeZone::PER_COUNTRY,
            countryCode: 'US',
        );
    }
}
