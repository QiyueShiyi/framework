<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-20 10:56
 */
namespace Notadd\Foundation\Routing;
use Closure;
use Exception;
use FastRoute\Dispatcher as RouteDispatcher;
use FastRoute\RouteCollector;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Illuminate\Support\Arr;
use Notadd\Foundation\Http\Exceptions\MethodNotAllowedException;
use Notadd\Foundation\Http\Exceptions\RouteNotFoundException;
use Notadd\Foundation\Routing\Dispatchers\ApiDispatcher;
use Notadd\Foundation\Routing\Dispatchers\CallableDispatcher;
use Notadd\Foundation\Routing\Dispatchers\ControllerDispatcher;
use Notadd\Foundation\Routing\Registrars\ResourceRegistrar;
use Psr\Http\Message\ServerRequestInterface;
/**
 * Class Router
 * @package Notadd\Foundation\Routing
 */
class Router {
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;
    /**
     * @var array
     */
    protected $currentRoute;
    /**
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;
    /**
     * @var array
     */
    protected $groupAttributes = [];
    /**
     * @var array
     */
    protected $namedRoutes = [];
    /**
     * @var array
     */
    protected $middleware = [];
    /**
     * @var array
     */
    protected $middlewareGroups = [];
    /**
     * @var array
     */
    protected $middlewarePriority = [];
    /**
     * @var array
     */
    protected $routes = [];
    /**
     * Router constructor.
     * @param \Illuminate\Container\Container $container
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function __construct(Container $container, EventsDispatcher $events) {
        $this->container = $container;
        $this->events = $events;
    }
    /**
     * @param string $method
     * @param string $uri
     * @param mixed $action
     * @param null $type
     */
    public function addRoute($method, $uri, $action, $type = null) {
        $action = $this->parseAction($action, $type);
        if(isset($this->groupAttributes)) {
            if(isset($this->groupAttributes['prefix'])) {
                $uri = trim($this->groupAttributes['prefix'], '/') . '/' . trim($uri, '/');
            }
            if(isset($this->groupAttributes['suffix'])) {
                $uri = trim($uri, '/') . rtrim($this->groupAttributes['suffix'], '/');
            }
            $action = $this->mergeGroupAttributes($action);
        }
        $uri = $this->prefix($uri);
        $uri = '/' . trim($uri, '/');
        if(isset($action['as'])) {
            $this->namedRoutes[$action['as']] = $uri;
        }
        $this->routes[$method . $uri] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
        ];
    }
    /**
     * @param $uri
     * @param null $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function any($uri, $action = null) {
        $methods = [
            'GET',
            'HEAD',
            'POST',
            'PUT',
            'PATCH',
            'DELETE'
        ];
        foreach($methods as $method) {
            $this->addRoute($method, $uri, $action);
        }
        return $this;
    }
    /**
     * @param string $uri
     * @param null $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function api($uri, $action = null) {
        $this->addRoute('POST', $uri, $action, 'api');
        return $this;
    }
    /**
     * @return \FastRoute\Dispatcher
     */
    public function createDispatcher() {
        return $this->dispatcher ?: \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            foreach($this->routes as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        });
    }
    /**
     * @param string $uri
     * @param mixed $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function delete($uri, $action) {
        $this->addRoute('DELETE', $uri, $action);
        return $this;
    }
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return mixed
     */
    public function dispatch(ServerRequestInterface $request) {
        $method = $request->getMethod();
        $pathInfo = $request->getUri()->getPath() ?: '/';
        if(isset($this->routes[$method . $pathInfo])) {
            return $this->handleFoundRoute([
                true,
                $this->routes[$method . $pathInfo]['action'],
                []
            ]);
        }
        return $this->handleDispatcherResponse($this->createDispatcher()->dispatch($method, $pathInfo));
    }
    /**
     * @param array $routeInfo
     * @return mixed
     * @throws \Exception
     */
    protected function callActionOnArrayBasedRoute($routeInfo) {
        $action = $routeInfo[1];
        if($action['type'] == 'controller') {
            return (new ControllerDispatcher($this->container, $this))->dispatch($routeInfo);
        } elseif($action['type'] == 'api' && is_string($action['uses'])) {
            return (new ApiDispatcher($this->container, $this))->dispatch($routeInfo);
        } elseif($action['type'] == 'closure' || $action['uses'] instanceof Closure) {
            return (new CallableDispatcher($this->container))->dispatch($action['uses'], $routeInfo[2]);
        } else {
            throw new Exception('Can not Handle Router Action !');
        }
    }
    /**
     * @param array $new
     * @param array $old
     * @return string|null
     */
    protected static function formatGroupPrefix($new, $old) {
        $oldPrefix = isset($old['prefix']) ? $old['prefix'] : null;
        if(isset($new['prefix'])) {
            return trim($oldPrefix, '/') . '/' . trim($new['prefix'], '/');
        }
        return $oldPrefix;
    }
    /**
     * @param array $new
     * @param array $old
     * @return string|null
     */
    protected static function formatUsesPrefix($new, $old) {
        if(isset($new['namespace'])) {
            return isset($old['namespace']) ? trim($old['namespace'], '\\') . '\\' . trim($new['namespace'], '\\') : trim($new['namespace'], '\\');
        }
        return isset($old['namespace']) ? $old['namespace'] : null;
    }
    /**
     * @param array $middleware
     * @return array
     */
    public function gatherMiddlewareClassNames(array $middleware) {
        $middlewares = collect($middleware)->map(function ($name) {
            $map = $this->middleware;
            $result = null;
            if($name instanceof Closure) {
                $result = $name;
            } elseif(isset($map[$name]) && $map[$name] instanceof Closure) {
                $result = $map[$name];
            } elseif(isset($this->middlewareGroups[$name])) {
                $result = $this->parseMiddlewareGroup($name);
            } else {
                list($name, $parameters) = array_pad(explode(':', $name, 2), 2, null);
                $result = (isset($map[$name]) ? $map[$name] : $name) . ($parameters !== null ? ':' . $parameters : '');
            }
            return (array)$result;
        })->flatten();
        $sorted = [];
        $middlewares->each(function($middleware) use($middlewares, &$sorted) {
            if(!in_array($middleware, $sorted)) {
                if(($index = array_search($middleware, $this->middlewarePriority)) !== false) {
                    $sorted = array_merge($sorted, array_filter(array_slice($this->middlewarePriority, 0, $index), function ($middleware) use ($middlewares, $sorted) {
                        return $middlewares->contains($middleware) && !in_array($middleware, $sorted);
                    }));
                }
                $sorted[] = $middleware;
            }
        });
        return $sorted;
    }
    /**
     * @param string $uri
     * @param mixed $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function get($uri, $action) {
        $this->addRoute('GET', $uri, $action);
        return $this;
    }
    /**
     * @return array
     */
    public function getGroupAttributes() {
        return $this->groupAttributes;
    }
    /**
     * @return string
     */
    public function getLastGroupPrefix() {
        if(!empty($this->groupAttributes)) {
            $last = end($this->groupAttributes);
            return isset($last['prefix']) ? $last['prefix'] : '';
        }
        return '';
    }
    /**
     * @param array $attributes
     * @param \Closure $callback
     * @return void
     */
    public function group(array $attributes, Closure $callback) {
        $this->updateGroupAttributes($attributes);
        call_user_func($callback, $this);
        array_pop($this->groupAttributes);
    }
    /**
     * @param $routeInfo
     * @return mixed
     * @throws \Notadd\Foundation\Http\Exceptions\MethodNotAllowedException
     * @throws \Notadd\Foundation\Http\Exceptions\RouteNotFoundException
     */
    protected function handleDispatcherResponse($routeInfo) {
        switch($routeInfo[0]) {
            case RouteDispatcher::NOT_FOUND:
                throw new RouteNotFoundException;
            case RouteDispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException;
            case RouteDispatcher::FOUND:
                return $this->handleFoundRoute($routeInfo);
        }
        return null;
    }
    /**
     * @param array $routeInfo
     * @return mixed
     */
    public function handleFoundRoute(array $routeInfo) {
        $this->currentRoute = $routeInfo;
        $action = $routeInfo[1];
        if(isset($action['middleware'])) {
            $middleware = $this->gatherMiddlewareClassNames((array)$action['middleware']);
            return $this->sendThroughPipeline($middleware, function () use ($routeInfo) {
                return $this->callActionOnArrayBasedRoute($routeInfo);
            });
        }
        return $this->callActionOnArrayBasedRoute($routeInfo);
    }
    /**
     * @return bool
     */
    public function hasGroupAttributes() {
        return !empty($this->groupAttributes);
    }
    /**
     * @param $methods
     * @param $uri
     * @param null $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function match($methods, $uri, $action = null) {
        $methods = array_map('strtoupper', (array)$methods);
        foreach($methods as $method) {
            $this->addRoute($method, $uri, $action);
        }
        return $this;
    }
    /**
     * @param array $new
     * @param array $old
     * @return array
     */
    public static function mergeGroup($new, $old) {
        $new['namespace'] = static::formatUsesPrefix($new, $old);
        $new['prefix'] = static::formatGroupPrefix($new, $old);
        if(isset($new['domain'])) {
            unset($old['domain']);
        }
        $new['where'] = array_merge(isset($old['where']) ? $old['where'] : [], isset($new['where']) ? $new['where'] : []);
        if(isset($old['as'])) {
            $new['as'] = $old['as'] . (isset($new['as']) ? $new['as'] : '');
        }
        return array_merge_recursive(Arr::except($old, [
            'namespace',
            'prefix',
            'where',
            'as'
        ]), $new);
    }
    /**
     * @param array $action
     * @return array
     */
    protected function mergeGroupAttributes(array $action) {
        $groupAttributes = end($this->groupAttributes);
        return $this->mergeNamespaceGroup($this->mergeMiddlewareGroup($action, $groupAttributes), $groupAttributes);
    }
    /**
     * @param array $action
     * @param array $groupAttributes
     * @return array
     */
    protected function mergeMiddlewareGroup($action, $groupAttributes) {
        if(isset($groupAttributes['middleware'])) {
            if(isset($action['middleware'])) {
                $action['middleware'] = array_merge($groupAttributes['middleware'], $action['middleware']);
            } else {
                $action['middleware'] = $groupAttributes['middleware'];
            }
        }
        return $action;
    }
    /**
     * @param array $action
     * @param array $groupAttributes
     * @return array
     */
    protected function mergeNamespaceGroup(array $action, $groupAttributes) {
        if(isset($groupAttributes['namespace']) && isset($action['uses'])) {
            $action['uses'] = $groupAttributes['namespace'] . '\\' . $action['uses'];
        }
        return $action;
    }
    /**
     * @param string $uri
     * @param mixed $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function options($uri, $action) {
        $this->addRoute('OPTIONS', $uri, $action);
        return $this;
    }
    /**
     * @param mixed $action
     * @param null $type
     * @return array
     */
    protected function parseAction($action, $type = null) {
        if(is_string($action)) {
            return ['uses' => $action, 'type' => $type == 'api' ? $type : 'controller'];
        } elseif($action instanceof Closure) {
            return ['uses' => $action, 'type' => $type == 'api' ? $type : 'closure'];
        }
        if(isset($action['middleware']) && is_string($action['middleware'])) {
            $action['middleware'] = explode('|', $action['middleware']);
        }
        isset($action['type']) || $action['type'] = $type == 'api' ? $type : 'controller';
        return $action;
    }
    /**
     * @param string $name
     * @return array
     */
    protected function parseMiddlewareGroup($name) {
        $results = [];
        foreach($this->middlewareGroups[$name] as $middleware) {
            if(isset($this->middlewareGroups[$middleware])) {
                $results = array_merge($results, $this->parseMiddlewareGroup($middleware));
                continue;
            }
            list($middleware, $parameters) = array_pad(explode(':', $middleware, 2), 2, null);
            if(isset($this->middleware[$middleware])) {
                $middleware = $this->middleware[$middleware];
            }
            $results[] = $middleware . ($parameters ? ':' . $parameters : '');
        }
        return $results;
    }
    /**
     * @param string $uri
     * @param mixed $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function patch($uri, $action) {
        $this->addRoute('PATCH', $uri, $action);
        return $this;
    }
    /**
     * @param string $uri
     * @param mixed $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function post($uri, $action) {
        $this->addRoute('POST', $uri, $action);
        return $this;
    }
    /**
     * @param string $uri
     * @return string
     */
    protected function prefix($uri) {
        return trim(trim($this->getLastGroupPrefix(), '/') . '/' . trim($uri, '/'), '/') ?: '/';
    }
    /**
     * @param string $uri
     * @param mixed $action
     * @return \Notadd\Foundation\Routing\Router
     */
    public function put($uri, $action) {
        $this->addRoute('PUT', $uri, $action);
        return $this;
    }
    /**
     * @param string $path
     * @param string $controller
     * @param array $options
     */
    public function resource($path, $controller, array $options = []) {
        if($this->container->bound(ResourceRegistrar::class)) {
            $registrar = $this->container->make(ResourceRegistrar::class);
        } else {
            $registrar = new ResourceRegistrar($this->container, $this);
        }
        $registrar->register($path, $controller, $options);
    }
    /**
     * @param array $middleware
     * @return $this
     */
    public function middleware(array $middleware) {
        $this->middleware = array_merge($this->middleware, $middleware);
        return $this;
    }
    /**
     * @param string $name
     * @param array $middleware
     * @return $this
     */
    public function middlewareGroup($name, array $middleware) {
        $this->middlewareGroups[$name] = $middleware;
        return $this;
    }
    /**
     * @param \FastRoute\Dispatcher $dispatcher
     */
    public function setDispatcher(RouteDispatcher $dispatcher) {
        $this->dispatcher = $dispatcher;
    }
    /**
     * @param array $middleware
     * @param \Closure $then
     * @return mixed
     */
    public function sendThroughPipeline(array $middleware, Closure $then) {
        $shouldSkipMiddleware = $this->container->bound('middleware.disable') && $this->container->make('middleware.disable') === true;
        if(count($middleware) > 0 && !$shouldSkipMiddleware) {
            return (new Pipeline($this->container))->send($this->container->make(ServerRequestInterface::class))->through($middleware)->then($then);
        }
        return $then();
    }
    /**
     * @param array $attributes
     */
    protected function updateGroupAttributes(array $attributes) {
        if(!empty($this->groupAttributes)) {
            $attributes = $this->mergeGroup($attributes, end($this->groupAttributes));
        }
        $this->groupAttributes[] = $attributes;
    }
}