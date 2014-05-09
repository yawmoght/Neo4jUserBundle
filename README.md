Neo4jUserBundle
===============

Symfony 2 bundle to manage users in a Neo4j graph database

[![Build Status](https://travis-ci.org/swiss-php-friends/Neo4jUserBundle.svg?branch=master)](https://travis-ci.org/swiss-php-friends/Neo4jUserBundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/frne/Neo4jUserBundle/badges/quality-score.png?s=59f1833cfc72c484f605f50f7965d67bbcdcc2b7)](https://scrutinizer-ci.com/g/frne/Neo4jUserBundle/) [![Code Coverage](https://scrutinizer-ci.com/g/frne/Neo4jUserBundle/badges/coverage.png?s=52c98b9d7def1b62cf71eed0defa7f9cdee12eb7)](https://scrutinizer-ci.com/g/frne/Neo4jUserBundle/) [![Latest Stable Version](https://poser.pugx.org/frne/neo4j-user-bundle/v/stable.png)](https://packagist.org/packages/frne/neo4j-user-bundle)

## Installation

The installation is as easy as every other symfony bundle...

Add composer dep:

```bash
composer.phar require frne/neo4j-user-bundle:dev-master
```

Register bundle:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        //...
        new Frne\Bundle\Neo4jUserBundle\Neo4jUserBundle(),
        //...
        )
}
```

## Usage

Configure the user provider in your ```app/config/security.yml```:

```yml
# ...
encoders:
    Frne\Bundle\Neo4jUserBundle\Entity\User: sha512
# ...
providers:
    neo4j:
        id: neo4j_user_provider
```

## Configuration

You can replace the User entity or the Dehydrator with your own implementation. See the default settings and classes to do so:

```yml
# Default configuration for extension with alias: "neo4j_user"
neo4j_user:

    # A fully qualified classname to use for user entities
    entity_classname:     Frne\Bundle\Neo4jUserBundle\Entity\User

    # A fully qualified classname to use for dehydrating users
    entity_dehydrator:    Frne\Bundle\Neo4jUserBundle\Security\User\Neo4jUserDehydrator
```

## Creating Users

### Console

Users can be created manually with the console command:

```bash
neo4j:user-bundle:create-user -u|--username="..." -p|--password="..." [-r|--roles="..."]
```

The username and password parameters are mandatory. If you don't assign custom roles to the created user,
```ROLE_USER``` will be assigned.

**Example:**

```bash
php app/console neo4j:user-bundle:create-user --username=testuser --password=1234 --roles=ROLE_FOO,ROLE_BAR
```

## Check Neo4j server availability

There is a check, ensuring your Neo4j server is online. To use it, first install LiipMonitorBundle
(```"liip/monitor-bundle"```) as suggested from composer. Once the monitor is working, the Neo4j check
will be automaticly added and executed.

## Contribution

If you find any bugs or improvements, please open an issue or propose a pull request [here](https://github.com/frne/Neo4jUserBundle/issues).

The code must follow the Symfony [coding standarts](http://symfony.com/doc/current/contributing/code/standards.html) and [conventions](http://symfony.com/doc/current/contributing/code/conventions.html). Untested code will not be merged ;)
