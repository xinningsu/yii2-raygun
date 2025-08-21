<?php

declare(strict_types=1);

namespace Sulao\YiiRaygun;

use Closure;
use GuzzleHttp\Client;
use Raygun4php\RaygunClient;
use Raygun4php\Transports\GuzzleSync;
use Throwable;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class RaygunComponent extends Component
{
    public $config = [];

    protected ?RaygunClient $client = null;

    public function init()
    {
        parent::init();

        if (empty($this->config['api_key'])) {
            throw new InvalidConfigException('Raygun API key is not set in the configuration.');
        }

        $this->client ??= $this->getRaygunClient();
    }

    /**
     * Returns a RaygunClient instance configured with the provided settings
     *
     * @return RaygunClient
     */
    protected function getRaygunClient(): RaygunClient
    {
        $httpClient = new Client([
            'base_uri' => 'https://api.raygun.com',
            'headers' => [
                'X-ApiKey' => $this->config['api_key']
            ]
        ]);

        $transport = new GuzzleSync($httpClient);
        $raygunClient = new RaygunClient($transport);

        if (array_key_exists('version', $this->config) && !is_null($this->config['version'])) {
            $raygunClient->setVersion($this->config['version']);
        }

        if (array_key_exists('filter_params', $this->config) && is_array($this->config['filter_params'])) {
            $params = [];
            foreach ($this->config['filter_params'] as $key => $value) {
                if (is_string($key) && (is_bool($value) || $value instanceof Closure)) {
                    $params[$key] = $value;
                } elseif (is_int($key) && is_string($value)) {
                    $params[$value] = true;
                }
            }

            $raygunClient->setFilterParams($params);
        }

        if (array_key_exists('user', $this->config)) {
            $identity = $this->config['user'] instanceof Closure
                ? call_user_func($this->config['user'])
                : $this->config['user'];
            $raygunClient->SetUser($identity);
        }

        return $raygunClient;
    }

    /**
     * Transmits an error to the Raygun API
     *
     * @param int    $errno    The error number
     * @param string $errstr   The error string
     * @param string $errfile  The file the error occurred in
     * @param int    $errline  The line the error occurred on
     * @return bool
     */
    public function sendError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ): bool {
        return $this->client->SendError(
            $errno,
            $errstr,
            $errfile,
            $errline,
            $this->config['tags'] ?? null,
            $this->config['custom_data'] ?? null
        );
    }

    /**
     * Transmits an exception to the Raygun API
     *
     * @param  Throwable $throwable An exception object to transmit
     * @return bool
     */
    public function sendException(Throwable $throwable): bool
    {
        return $this->client->SendException(
            $throwable,
            $this->config['tags'] ?? null,
            $this->config['custom_data'] ?? null
        );
    }
}
