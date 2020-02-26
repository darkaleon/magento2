<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Plugin;

use Alexx\HelloWorldApi\Api\Data\HelloApiInterface;

/**
 * Plugin that inserts prefix
 */
class PluginBefore
{
    /**
     * Get hello method interception
     *
     * @param HelloApiInterface $subject
     * @param string $result
     *
     * @return string
     */
    public function afterGetHello(HelloApiInterface $subject, string $result): string
    {
        return 'prefix_' . $result;
    }
}
