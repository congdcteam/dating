<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdcd3066b675834517ab90cb89b8da04a
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdcd3066b675834517ab90cb89b8da04a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdcd3066b675834517ab90cb89b8da04a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdcd3066b675834517ab90cb89b8da04a::$classMap;

        }, null, ClassLoader::class);
    }
}
