<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc00638989928e4ac866d828b481fa14f
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'FinanceService\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'FinanceService\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc00638989928e4ac866d828b481fa14f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc00638989928e4ac866d828b481fa14f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}