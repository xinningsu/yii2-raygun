<?php

declare(strict_types=1);

namespace Sulao\YiiRaygun;

use Throwable;
use Yii;
use yii\log\Target;

class RaygunTarget extends Target
{
    //public $enabled = true;
    protected ?RaygunComponent $component = null;

    public function init()
    {
        parent::init();

        $this->component = Yii::$app->raygun;
    }

    /**
     * Exports the log messages to Raygun.
     *
     * This method iterates through the collected log messages and sends them to Raygun.
     * It handles both exceptions and error messages, sending them with appropriate context.
     */
    public function export()
    {
        foreach ($this->messages as $message) {
            [$context, $level, , , $traces] = $message;

            if ($context instanceof Throwable) {
                $this->component->sendException($context);
            } elseif (is_string($context)) {
                $this->component->sendError(
                    $level,
                    $context,
                    $traces[0]['file'] ?? '',
                    $traces[0]['line'] ?? 0
                );
            }
        }
    }

    /**
     * Generates the context information to be logged.
     * The default implementation will dump user information, system variables, etc.
     * Here returns an empty string.
     *
     * @return string the context information. If an empty string, it means no context information.
     */
    protected function getContextMessage()
    {
        return '';
    }
}
