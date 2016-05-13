# vo-errbit-cake
Errbit error handler for CakePHP 2.x

# Installation

* In app/Lib/ folder

```
git clone --recursive git@github.com:vinco/vo-errbit-cake.git
```

* In app/Config/bootstrap.php

```php
/*
 * Errbit CakePHP
 */
App::uses('ErrbitCakePHP', 'Lib/vo-errbit-cake');
ErrbitCakePHP::$settings = array(
    'api_key' => 'YOUR_API_KEY',
    'host' => 'YOUR_HOST',
    'port' => 'YOUR_PORT',
    'environment_name' => 'YOUR_ENVIRONMENT_NAME',
    'showErrors' => true,
    'showWarnigns' => true,
    'showNotice' => true
);
Configure::write('Error.handler', 'ErrbitCakePHP::handleError');
```

This handler uses the errbit client for php [flippa](https://github.com/flippa/errbit-php)

