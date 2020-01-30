<?php

namespace MadeByMikkel\Subscriptions;

class Subscriptions {

    /**
     * The version of the Subscriptions library
     *
     * @var string
     */
    const VERSION = '1.0.0';


    public static function options ( array $options = [] ) {
        return array_merge([
            'api_key' => config('subscriptions.secret'),
        ], $options);
    }
}
