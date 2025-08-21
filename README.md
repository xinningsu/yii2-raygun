# Raygun Error Logger for Yii2
Raygun integration for Yii2, Raygun Error Logger.

[![MIT licensed](https://img.shields.io/badge/license-MIT-blue.svg)](./LICENSE)
[![Build Status](https://scrutinizer-ci.com/g/xinningsu/yii2-raygun/badges/build.png?b=master)](https://scrutinizer-ci.com/g/xinningsu/yii2-raygun/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/xinningsu/yii2-raygun/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/xinningsu/yii2-raygun/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xinningsu/yii2-raygun/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/xinningsu/yii2-raygun)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/xinningsu/yii2-raygun/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/g/xinningsu/yii2-raygun)

# Installation

```
composer require xinningsu/yii2-raygun

```

# Usage

Once you have finished the Raygun installation, set up Raygun component and Raygun log target in Yii common config file, e.g., `config/web.php`:
```php
[
    // ...
    'components' => [
        // ...
        'raygun' => [
            'class' => \Sulao\YiiRaygun\RaygunComponent::class,
            'config' => [
                'api_key' => 'your_raygun_api_key', // Update with your Raygun API key
                // For more configuration options, please refer to 
                // https://github.com/xinningsu/yii2-raygun/blob/master/config/raygun.php
            ],
        ],
        'log' => [
            // ...
            'targets' => [
                // ...
                [
                    'class' => \Sulao\YiiRaygun\RaygunTarget::class,
                    'levels' => ['error', 'warning'],
                    'except' => [
                        \yii\web\HttpException::class,
                    ],
                ],
            ],
        ],
        // ...
    ],
    // ...
],
```

# Testing

In the  `controller` file, add the code below
```php
\Yii::error('test error');;
```
Or trigger an exception
```php
throw new \Exception('test exception');
```
Then, check if the report is available in the Raygun Crash Reporting.

# License

[MIT](./LICENSE)
