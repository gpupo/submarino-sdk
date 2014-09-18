SDK Não Oficial para integração a partir de aplicações PHP com as APIs do Submarino Marketplace


## License

MIT, see LICENSE.


## Install

The recommended way to install is [through composer](http://getcomposer.org).

    composer require gpupo/submarino-sdk:dev-master

---

# Dev

Install [through composer](http://getcomposer.org):

	composer install --dev;

Copy ``phpunit`` configuration file:

    cp phpunit.xml.dist phpunit.xml;

Customize parameters in ``phpunit.xml``:

```XML
    <!-- Customize your parameters ! -->
    <php>
        <const name="API_TOKEN" value=""/>
        <const name="VERBOSE" value="false"/>
    </php>
```

To run localy the test suite:

    $ phpunit
  

## Links

* [Composer Package](https://packagist.org/packages/gpupo/submarino-sdk) on packagist.org