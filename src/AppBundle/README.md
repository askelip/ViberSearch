AppBundle Search Engine
=======================

The search engine form enables you to query several search engines and retrieve
aggregated results.

Installation
------------
 * After pulling project files, run composer install to retrieve vendor bundles
 * Configure database credentials in config.yml and run doctrine:migrations:migrate 

Adding A Search Engine
----------------------
 * Configure a Guzzle client in config.yml
 * Extent SearchEngineClient class to retrieve urls from a specific engine response
   and add it as a service to services.yml with your specific configuration (query parameter
   name, format, etc.)
 * Add the engine to the SearchEngineCollection in services.yml