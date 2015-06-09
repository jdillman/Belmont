Simple PHP framework.

Easy to configure routes. Straightforward controllers and a simple Model class.

Routes
Maps a url to a handler

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

Controllers
Takes a request and generates a response

