AjaxBlocksBundle
================

AjaxBlocksBundles provides an easy way to render in a Twig template blocks that can be updated using ajax requests.

[![Build Status](https://travis-ci.org/jagilpe/ajax-blocks-bundle.svg?branch=master)](https://travis-ci.org/jagilpe/ajax-blocks-bundle)

# Installation

You can install the bundle using composer:

```bash
composer require jagilpe/ajax-blocks-bundle
```

or add the package to your composer.json file directly.

To enable the bundle, you just have to register the bundle in your AppKernel.php file:

```php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Jagilpe\AjaxBlocksBundle\AjaxBlocksBundle(),
    // ...
);
```

Finally you have to include the provided javascript file somewhere in your base template. 
If you use assetic to manage the assets:

```twig
{% block javascripts %}
    {{ parent() }}
    {% javascripts
        'bundles/ajaxblocks/js/ajax-blocks.js' %}
        <script src="{{ asset_url }}"></script>
    {% endjavascripts %}
{% endblock %}
```
