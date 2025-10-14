# Application
This package contains a minimal implementation for an Application class. This class is intended
to be the main class used inside your `index.php` file. This package right now, does not contain
advanced features such as bootstrapping, events, and more. These will most likely be added in a
future version.

##  Requirements
To use this package you need 2 things a `Router` and a `Logger`. The logger is any PSR compliant
logger, and the router is anything that implements the `SimpleRouterInterface`.

##  Functionality
The Application class will take a `PSR Request` object and route it using the `Router`. Using the
obtained `Route` it will execute the request. The `Response` will then be sent over to the user.
