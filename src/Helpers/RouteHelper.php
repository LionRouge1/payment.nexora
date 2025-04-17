<?php
namespace App\Helpers;

class RouteHelper
{
    /**
     * Generate a URL for a given route name and parameters.
     *
     * @param string $routeName The name of the route.
     * @param array $params An associative array of parameters to include in the URL.
     * @return string The generated URL.
     */
    public static function url(string $routeName, array $params = []): string
    {
        // Assuming you have a method to get the base URL
        $baseUrl = '/'; // Replace with your actual base URL

        // Build the query string from the parameters
        $queryString = http_build_query($params);

        // Construct the full URL
        return $baseUrl . $routeName . ($queryString ? '?' . $queryString : '');
    }
}