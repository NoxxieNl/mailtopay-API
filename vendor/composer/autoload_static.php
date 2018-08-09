<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8fe9fd68d88dbb20e1852d05b4661048
{
    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'pdeans\\Builders\\' => 16,
        ),
        'M' => 
        array (
            'MailtoPay\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'pdeans\\Builders\\' => 
        array (
            0 => __DIR__ . '/..' . '/pdeans/xml-builder/src',
        ),
        'MailtoPay\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/MailToPay',
        ),
    );

    public static $prefixesPsr0 = array (
        'U' => 
        array (
            'Unirest\\' => 
            array (
                0 => __DIR__ . '/..' . '/mashape/unirest-php/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8fe9fd68d88dbb20e1852d05b4661048::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8fe9fd68d88dbb20e1852d05b4661048::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit8fe9fd68d88dbb20e1852d05b4661048::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
