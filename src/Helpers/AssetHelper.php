<?php
namespace App\Helpers;

class AssetHelper
{
    /**
     * Base assets directory
     */
    private static $basePath = '/payment.nexora/public/assets/';

    /**
     * Load CSS files
     */
    public static function css(array $files, bool $defer = false): string
    {
        $html = '';
        foreach ($files as $file) {
            $html .= sprintf(
                '<link rel="stylesheet" href="%s" %s>',
                self::$basePath . 'css/' . $file . '.css',
                $defer ? 'media="print" onload="this.media=\'all\'"' : ''
            ) . "\n";
        }
        return $html;
    }

    /**
     * Load JavaScript files
     */
    public static function js(array $files, bool $defer = true, bool $async = false): string
    {
        $html = '';
        foreach ($files as $file) {
            $html .= sprintf(
                '<script src="%s" %s %s></script>',
                self::$basePath . 'js/' . $file . '.js',
                $defer ? 'defer' : '',
                $async ? 'async' : ''
            ) . "\n";
        }
        return $html;
    }

    /**
     * Load image with optional attributes
     */
    public static function img(string $file, array $attrs = []): string
    {
        $attributes = '';
        foreach ($attrs as $key => $value) {
            $attributes .= sprintf(' %s="%s"', $key, htmlspecialchars($value));
        }
        
        return sprintf(
            '<img src="%s"%s>',
            self::$basePath . 'images/' . $file,
            $attributes
        );
    }
}