<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use PHPUnit\Framework\TestCase;
use yii\base\InvalidConfigException;
use yii\caching\ArrayCache;
use yii\log\Logger;
use yii\web\Application;
use Sulao\YiiRaygun\RaygunComponent;
use Sulao\YiiRaygun\RaygunTarget;

class RaygunTest extends TestCase
{
    protected $config = [
        'id' => 'basic',
        'basePath' => __DIR__ . '/../',
        'bootstrap' => ['log'],
        'components' => [
            'cache' => [
                'class' => ArrayCache::class,
            ],
            'raygun' => [
                'class' => RaygunComponent::class,
                'config' => [],
            ],
            'log' => [
                'traceLevel' => 3,
                'targets' => [
                    [
                        'class' => RaygunTarget::class,
                        'levels' => ['error', 'warning'],
                    ],
                ],
            ],
        ],
    ];

    public function testConfigException()
    {
        $config = $this->config;
        $config['components']['log'] = [];
        $config['components']['raygun']['config'] = [
            'api_key' => '',
        ];
        $this->expectException(InvalidConfigException::class);
        $app = new Application($config);
        $app->raygun->init();
    }

    public function testComponent()
    {
        $config = $this->config;
        $config['components']['raygun']['config'] = [
            'api_key' => getenv('RAYGUN_API_KEY'),
            'filter_params' => [
                'password',
                'authorization' => true,
                'ccv' => fn () => '123',
            ],
            'version' => '1.0.0',
            'user' => 'test@test.com',
        ];

        $app = new Application($config);

        $exception = null;
        try {
            $app->raygun->init();
        } catch (\Exception $e) {
            $exception = $e;
        }
        $this->assertNull(
            $exception,
            'Exception should not be thrown during Raygun Component initialization'
        );
    }

    public function testTarget()
    {
        $config = $this->config;
        $config['components']['raygun']['config'] = [
            'api_key' => getenv('RAYGUN_API_KEY'),
            'user' => fn() => 'test@test.com',
        ];

        $app = new Application($config);

        $exception = null;
        try {
            $messages = [
                [
                    new Exception('Test Raygun exception'),
                    Logger::LEVEL_ERROR,
                    'application',
                    time(),
                    [],
                ],
                [
                    'Test Raygun exception',
                    Logger::LEVEL_ERROR,
                    'application',
                    time(),
                    [],
                ]
            ];
            $app->log->targets[0]->collect($messages, true);
        } catch (Exception $e) {
            $exception = $e;
        }
        $this->assertNull($exception, 'Exception should not be thrown during Raygun logging');
    }

    public function setUp(): void
    {
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        defined('YII_ENV') or define('YII_ENV', 'dev');

        require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
    }

    /**
     * To avoid PHPUnit warning:
     * Test code or tested code did not remove its own exception handlers
     */
    public function tearDown(): void
    {
        parent::tearDown();
        restore_error_handler();
        restore_exception_handler();
    }
}
