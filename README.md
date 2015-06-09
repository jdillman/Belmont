# Simple PHP framework.

## Routes
Maps a url to a handler (inline function or controller)

```php
$routes = array(
  '/' => 'home',
  '/users/%s' => 'user',
  '/test' => array(
	 'controller' => 'test',
	 'methods' => 'POST'
  ),
  '/404' => function ($request) {
	 return 'Page not found';
  }
);
```

## Controllers
Takes a request and generates a response

