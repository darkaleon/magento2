<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Plugin;

use Alexx\HelloWorldApi\Api\Data\HelloApiInterface;

/**
 * Plugin that inserts suffix
 */
class PluginAfter
{
    /**
     * Get hello method interception
     *
     * @param HelloApiInterface $subject
     * @param string $result
     *
     * @return string
     */
    public function afterGetHello($subject, $result)
    {
        return $result . '_suffix';
    }
}
