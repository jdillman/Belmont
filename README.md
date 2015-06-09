# Simple PHP web framework

Includes regex router, a 1-way data binding model and template-less templates

```php
<?php

require('Belmont.class.php');

$belmont = new Belmont(array(
  '/about' => 'AboutController',
  '/explore/([^/])+' => 'ProjectController',
  '/users/([0-9])+' => 'UserController',
  '/test' => function ($request) {
    return 'Inline Function Handler';
  },
  '/([^/]+)' => 'HomeController',
));

$response = $belmont->handleRequest();
$response->send();
```

## Routes
Maps a url to a handler (inline function, string or array)

Use {} to tokenize values (supports regex match)
```php

$routes['/explore/{product}' = array(
  'controller' => 'ProductController',
  'methods' => 'GET'
);

$routes['GET /user/{id:[0-9]+}' = array(
  'controller' => 'UserController',
  'methods' => 'GET|POST'
);
```

## Controllers
Takes a request and generates a response

## Models
Data binding

## Templates
Just use PHP!

2 variables are available to templates, $html and $model;

$html is a helper class to build markup
$model is the data available to the template

```php`
// /belmont/templates header.html.tpl
echo '<h1>Header</h1>';
echo $html->p($model->get('name'));
echo $html->makeLink('/user/' . $model->get('uid'));
```

