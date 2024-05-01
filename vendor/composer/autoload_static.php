<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit86eb0d392eb0ee4655f51cce198d16ae
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Procoders\\Omni\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Procoders\\Omni\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Procoders\\Omni\\Admin\\ClassAdmin' => __DIR__ . '/../..' . '/src/Admin/ClassAdmin.php',
        'Procoders\\Omni\\Admin\\ClassAssets' => __DIR__ . '/../..' . '/src/Admin/ClassAssets.php',
        'Procoders\\Omni\\Admin\\ClassNav' => __DIR__ . '/../..' . '/src/Admin/ClassNav.php',
        'Procoders\\Omni\\ClassLoader' => __DIR__ . '/../..' . '/src/ClassLoader.php',
        'Procoders\\Omni\\Includes\\TemplateLoader' => __DIR__ . '/../..' . '/src/Includes/TemplateLoader.php',
        'Procoders\\Omni\\Includes\\api' => __DIR__ . '/../..' . '/src/Includes/api.php',
        'Procoders\\Omni\\Includes\\debugger' => __DIR__ . '/../..' . '/src/Includes/debugger.php',
        'Procoders\\Omni\\Public\\ClassAssets' => __DIR__ . '/../..' . '/src/Public/ClassAssets.php',
        'Procoders\\Omni\\Public\\ClassPublic' => __DIR__ . '/../..' . '/src/Public/ClassPublic.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit86eb0d392eb0ee4655f51cce198d16ae::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit86eb0d392eb0ee4655f51cce198d16ae::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit86eb0d392eb0ee4655f51cce198d16ae::$classMap;

        }, null, ClassLoader::class);
    }
}