<?php
namespace HelpCompras\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Yaml\Yaml;

class YamlConfigServiceProvider implements ServiceProviderInterface
{
    protected $file;
    
    public function __construct($file) 
    {
        $this->file = $file;
    }
    
    public function register(Application $app) 
    {
        $config = Yaml::parse(file_get_contents($this->file));
        if (is_array($config)) {
            $this->importSearch($config, $app);
            if (isset($app['config']) && is_array($app['config'])) {
                $app['config'] = array_replace_recursive($app['config'], $config);
            } else {
                $app['config'] = $config;
            }
        }
    }
    
    /**
     * Looks for import directives..
     *
     * @param array $config
     *   The result of Yaml::parse().
     */
    public function importSearch(&$config, $app) 
    {
        foreach ($config as $key => $value) {
            if ($key == 'imports') {
                foreach ($value as $resource) {
                    $base_dir = str_replace(basename($this->file), '', $this->file);
                    $new_config = new YamlConfigServiceProvider($base_dir . $resource['resource']);
                    $new_config->register($app);
                }
                unset($config['imports']);
            }
        }
    }
    
    public function boot(Application $app) {}
    
    public function getConfigFile() 
    {
        return $this->file;
    }
}