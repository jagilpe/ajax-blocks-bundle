AjaxBlocksBundle
================

AjaxBlocksBundles is a Symfony bundle that provides an easy way to render in a Twig template blocks that can be updated using ajax requests.

[![Build Status](https://travis-ci.org/jagilpe/ajax-blocks-bundle.svg?branch=master)](https://travis-ci.org/jagilpe/ajax-blocks-bundle)
[![codecov](https://codecov.io/gh/jagilpe/ajax-blocks-bundle/branch/master/graph/badge.svg)](https://codecov.io/gh/jagilpe/ajax-blocks-bundle)
[![Latest Stable Version](https://poser.pugx.org/jagilpe/ajax-blocks-bundle/v/stable)](https://packagist.org/packages/jagilpe/ajax-blocks-bundle)
[![License](https://poser.pugx.org/jagilpe/ajax-blocks-bundle/license)](https://packagist.org/packages/jagilpe/ajax-blocks-bundle)


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

Then you have to add the route required for reloading the blocks. In app/config/routing.yml

```yaml
jgp_ajax_blocks:
    resource: "@AjaxBlocksBundle/Resources/config/routing.xml"
    prefix: "/jgp-ajax-blocks"
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
This javascript depends on jQuery, so you have to load it somewhere in the template before this file.

# Usage

## Basic usage

### Creating an ajax block

You can write a block to be rendered as an ajax block exactly as you would write if you would embed it in a 
template rendering directly the output of a controller.

Write a controller that builds and returns the desired block as usual:

```php
class DefaultController extends Controller
{
    // ...
    public function myAjaxBlockAction()
    {
        $variables = array();
        
        // Get the required variables for the template as one would usually do
        // ...
        
        return $this->render('::my_ajax_block.html.twig', $variables);
    }
}

```

There is no need to define a route for this controller.

### Embed the block in the page

To include the block in the page simply insert it the template using the `jgp_ajax_block` twig function, passing 
the controller as the first parameter.

```twig
{{ jgp_ajax_block('AppBundle:Default:myAjaxBlock') }}
```

### Reloading the block

The main goal of this bundle is to easily divide the page in blocks that can be independently refreshed without 
having to reload all the page. The logic that triggers this block reload is part of the logic of the application 
and should be implemented in javascript as part of the application's frontend.

We can access the ajax block through the selector `[data-target="jgp-ajax-block"]` and reload its content invoking the reloadBlock action of the jgpAjaxBlock jQuery plugin.

The following code would reload the content of all the ajax blocks present in the page:

```javascript
$('[data-target="jgp-ajax-block"]').jgpAjaxBlock('reloadBlock');
```

If we only want to reload one determined block, we should wrap it with another element and select it through it:

```javascript
$('#a-determined-block [data-target="jgp-ajax-block"]').jgpAjaxBlock('reloadBlock');
```

## Advanced usage

### Passing parameters to the block

You can pass parameters to the block exactly the same way you would do it with another controller, but with one 
important restriction: they must be strings. This is because the block is reloaded using an ajax call, and therefore 
this parameters must be encoded in the url of this call. If for example you wanted to pass an entity to the controller,
you should pass the id of the entity and then load it in the controller's code.

The parameters should be passed as an array in the second parameter of the `jgp_ajax_block` function in the template.

```php
class DefaultController extends Controller
{
    // ...
    public function myAjaxBlockAction($entityId, $otherParameter)
    {
        $variables = array();
        
        // Get the required variables for the template as one would usually do
        // ...
        
        return $this->render('::my_ajax_block.html.twig', $variables);
    }
}

```

```twig
{{ jgp_ajax_block('AppBundle:Default:myAjaxBlock', { entityId: 1, otherParameter: 'my value' }) }}
```

### Passing options to the jQuery plugin

By default the jQuery plugin is automatically loaded with the page load. To be able to customize the load of the plugin
we have to disable the auto load of the plugin. This can be done by passing the option `autoload` as `false` in the 
third parameter of the `jgp_ajax_block` twig function.

```twig
{{ jgp_ajax_block('AppBundle:Default:myAjaxBlock', { }, { autoload: false }) }}
```

After that you should load the plugin in the javascript code of your frontend with the custom options you want.

```javascript
$('[data-target="jgp-ajax-block"]').jgpAjaxBlock({
  'onReload': function(block) {
    // Do something with the loaded block
  }
});
```

#### Adding a reload callback to the block

You can pass a reload callback to the jQuery plugin that controls the block by passing it as an option. This callback
receives the reloaded block as parameter. For this to work the autoload option of the block must be disabled.

```javascript
$('[data-target="jgp-ajax-block"]').jgpAjaxBlock({
  'onReload': function(block) {
    // Do something with the loaded block
  }
});
```

