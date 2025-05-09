<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use L5Swagger\ConfigFactory;
use L5Swagger\Http\Middleware\Config as L5SwaggerConfig;

Route::group(['namespace' => 'L5Swagger'], static function (Router $router) {
    $configFactory = resolve(ConfigFactory::class);

    /** @var array<string,string> $documentations */
    $documentations = config('l5-swagger.documentations', []);

    foreach (array_keys($documentations) as $name) {
        $config = $configFactory->documentationConfig($name);

        if (! isset($config['routes'])) {
            continue;
        }

        $groupOptions = $config['routes']['group_options'] ?? [];

        if (! isset($groupOptions['middleware'])) {
            $groupOptions['middleware'] = [];
        }

        if (is_string($groupOptions['middleware'])) {
            $groupOptions['middleware'] = [$groupOptions['middleware']];
        }

        $groupOptions['l5-swagger.documentation'] = $name;
        $groupOptions['middleware'][] = L5SwaggerConfig::class;

        Route::group($groupOptions, static function (Router $router) use ($name, $config) {
            if (isset($config['routes']['api'])) {
                $router->get($config['routes']['api'], [
                    'as' => 'l5-swagger.'.$name.'.api',
                    'middleware' => $config['routes']['middleware']['api'] ?? [],
                    'uses' => '\L5Swagger\Http\Controllers\SwaggerController@api',
                ]);
            }

            if (isset($config['routes']['docs'])) {
                $router->get($config['routes']['docs'], [
                    'as' => 'l5-swagger.'.$name.'.docs',
                    'middleware' => $config['routes']['middleware']['docs'] ?? [],
                    'uses' => '\L5Swagger\Http\Controllers\SwaggerController@docs',
                ]);

                $router->get($config['routes']['docs'].'/asset/{asset}', [
                    'as' => 'l5-swagger.'.$name.'.asset',
                    'middleware' => $config['routes']['middleware']['asset'] ?? [],
                    'uses' => '\L5Swagger\Http\Controllers\SwaggerAssetController@index',
                ]);
            }

            if (isset($config['routes']['oauth2_callback'])) {
                $router->get($config['routes']['oauth2_callback'], [
                    'as' => 'l5-swagger.'.$name.'.oauth2_callback',
                    'middleware' => $config['routes']['middleware']['oauth2_callback'] ?? [],
                    'uses' => '\L5Swagger\Http\Controllers\SwaggerController@oauth2Callback',
                ]);
            }
        });
    }
});
