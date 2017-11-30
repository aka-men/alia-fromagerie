<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 08/06/2017
 * Time: 17:51
 */

namespace AppBundle\Manager;

use Doctrine\Bundle\DoctrineCacheBundle\Tests\DependencyInjection\YmlDoctrineCacheExtensionTest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

class ConfigurationManager
{
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var array
     */
    private $configuration;

    /**
     * ConfigurationManager constructor.
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->fileName = realpath($this->kernel->getRootDir()."/../src/AppBundle/Resources/config/configuration.yml");
        $this->load();
    }

    /**
     * Retourner le chemin absolute du dossier web
     * @return bool|string
     */
    public function absoluteWebPath(){
        return realpath($this->kernel->getRootDir()."/../web");
    }

    private function load(){
        $config = Yaml::parse(file_get_contents($this->fileName));
        $this->configuration = array_key_exists("configuration",$config) ? $config['configuration'] : array();
    }

    /**
     * Verifier si la configuration demmande existe dans le fichier de configuration
     * @param string $config
     * @return bool
     */
    public function has($config){
        return array_key_exists($config,$this->configuration);
    }

    /**
     * Retourner la valeur de la configuration
     * @param string $config
     * @param null $default
     * @return bool
     */
    public function get($config,$default = null){
        if($this->has($config))
            return $this->configuration[$config];
        return $default;
    }

    /**
     * Mettre a jour la valeur d'une configuration ou ajouter une nouvelle configuration
     * @param string $config
     * @param $value
     * @param bool $save
     */
    public function set($config,$value,$save = false){
        if(is_string($value) and in_array($value,array('true','false')))
            $value = $value === 'true' ? true : false;
        elseif($value === 'null' or empty($value))
            $value = null;
        $this->configuration[$config] = $value;
        if($save)
            $this->save();
    }

    /**
     * Mettre a jour le fichier d'une configuration ou ajouter une nouvelle configuration
     * @param string $config
     * @param string $name
     * @param UploadedFile $file
     * @param bool $save
     */
    public function setFile($config,$name,UploadedFile $file,$save = false){
        $this->configuration[$config] = $name;
        $oldFile = realpath($this->kernel->getRootDir().'/../web/images/'.$name);
        if (file_exists($oldFile) and is_file($oldFile))
            unlink($oldFile);
        $file->move(realpath($this->kernel->getRootDir().'/../web/images'),$name);
        if($save)
            $this->save();
    }

    /**
     * Mettre a jour une collection des configurations (modification,insertion)
     * @param string $collectionName
     * @param array $data
     * @param bool $skipMissing
     * @param bool $save
     */
    public function setCollection($collectionName,$data,$skipMissing =true,$save = false){
        if(!$skipMissing){
            foreach ($this->configuration as $key => $value){
                if(substr($key, 0, strlen($collectionName)) === $collectionName){
                    if(!array_key_exists($key,$data))
                        $this->set($key,null);
                }
            }
        }
        foreach ($data as $key => $value){
            $this->set($key,$value);
        }

        if($save)
            $this->save();
    }

    /**
     * Enregistrer les modifications
     */
    public function save(){
        $yml = Yaml::dump(array("configuration"=>$this->configuration));
        file_put_contents($this->fileName,$yml);
    }


}