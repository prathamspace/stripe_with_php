<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit189038107189722e3fe385714eb9a537
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
        'H' => 
        array (
            'Hp\\StripeWithPhp\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
        'Hp\\StripeWithPhp\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit189038107189722e3fe385714eb9a537::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit189038107189722e3fe385714eb9a537::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit189038107189722e3fe385714eb9a537::$classMap;

        }, null, ClassLoader::class);
    }
}
