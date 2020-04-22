<?php

namespace Dmyers\Phoney;

use Illuminate\Support\Facades\Facade as FacadeProvider;

class Facade extends FacadeProvider
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'phoney';
    }
}
