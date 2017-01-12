<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Yaml\Parser;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppBundle\AppBundle(),
            new Ant\Bundle\ChateaClientBundle\ChateaClientBundle(),
            new Ant\Bundle\ChateaSecureBundle\ChateaSecureBundle(),
            new Beelab\Recaptcha2Bundle\BeelabRecaptcha2Bundle(),
            new RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle(),
            //new Snc\RedisBundle\SncRedisBundle(),
            new Ant\Bundle\ApiSocialBundle\ApiSocialBundle(),
            new Ant\HelpBundle\HelpBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
        );


        if(class_exists("Ant\Bundle\PrettyBundle\PrettyBundle")){
            array_push($bundles, new Ant\Bundle\PrettyBundle\PrettyBundle());
        }

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $yaml = new Parser();
        $parametersPath = $this->getRootDir().'/config/parameters.yml';
        $parameters = $yaml->parse(file_get_contents($parametersPath));

        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
        /*
        if(isset($parameters['parameters']['redis_session']) && $parameters['parameters']['redis_session'] === true){
            $loader->load($this->getRootDir().'/config/redis_session.yml');
        }
        */
    }

    protected function getContainerBaseClass()
    {
        if('test' == $this->environment){
            return '\AppBundle\DependencyInjection\CacheableContainer';
        }

        return parent::getContainerBaseClass();
    }
}
