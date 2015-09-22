<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

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
            new EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle(),
            new RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle(),
            new Snc\RedisBundle\SncRedisBundle(),
            new Ant\Bundle\ApiSocialBundle\ApiSocialBundle(),
            new Ant\HelpBundle\HelpBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new Ant\WebSiteParametersBundle\WebSiteParametersBundle(),

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
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }

    protected function getContainerBaseClass()
    {
        if('test' == $this->environment){
            return '\AppBundle\DependencyInjection\CacheableContainer';
        }

        return parent::getContainerBaseClass();
    }

    /**
     * Initializes the service container.
     *
     * The cached version of the service container is used when fresh, otherwise the
     * container is built.
     */
    protected function initializeContainer()
    {
        //xabier: this is necessary because command  php app/console assetic:dump use the twig.loader.filesystem, and is override in
        // Ant\Bundle\PrettyBundle\DependencyInjection\Compiler\TemplateLoaderCompiler
        //@see http://stackoverflow.com/questions/11405202/symfony-2-cant-clear-cache-you-cannot-create-a-service-request-of-an-in
        parent::initializeContainer();
        if (PHP_SAPI == 'cli') {
            $this->getContainer()->enterScope('request');
            $this->getContainer()->set('request', new \Symfony\Component\HttpFoundation\Request(), 'request');
        }
    }
}
