# DoctrinePrefixBundle

The bundle added prefix before entity table name. This bundle is for [Symfony](http://symfony.com/) Framework.

## Configure

Require the bundle with composer:

    $ composer require mkurc1/doctrine-prefix-bundle

Enable the bundle in the kernel:

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new DoctrinePrefixBundle\DoctrinePrefixBundle(),
            // ...
        );
    }
    
Configure your application:

    # app/config/config.yml
    doctrine_prefix:
        table_prefix: 'your_prefix'
    
Update your database schema:

    $ php app/console doctrine:schema:update --force

## License

The bundle is released under the [MIT License](LICENSE).