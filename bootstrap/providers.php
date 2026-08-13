<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\RepositoryProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    RepositoryProvider::class,
];
