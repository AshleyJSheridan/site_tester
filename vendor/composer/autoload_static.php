<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6a5f39cb85dc25a01bcc23460d239221
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Tester\\WebContent\\' => 18,
            'Tester\\Tests\\' => 13,
            'Tester\\Helpers\\' => 15,
            'Tester\\Exceptions\\' => 18,
            'Tester\\Entities\\' => 16,
            'Tester\\ContentLists\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Tester\\WebContent\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/WebContent',
        ),
        'Tester\\Tests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Tests',
        ),
        'Tester\\Helpers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Helpers',
        ),
        'Tester\\Exceptions\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Exceptions',
        ),
        'Tester\\Entities\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Entities',
        ),
        'Tester\\ContentLists\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/ContentLists',
        ),
    );

    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Sabberworm\\CSS' => 
            array (
                0 => __DIR__ . '/..' . '/sabberworm/php-css-parser/lib',
            ),
        ),
        'D' => 
        array (
            'DaveChild\\TextStatistics' => 
            array (
                0 => __DIR__ . '/..' . '/davechild/textstatistics/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6a5f39cb85dc25a01bcc23460d239221::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6a5f39cb85dc25a01bcc23460d239221::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit6a5f39cb85dc25a01bcc23460d239221::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
