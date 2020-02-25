<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Plugin;


class PluginAfter
{

    public function afterGetHello($subject, $result)
    {
        return $result . '_suffix';
    }


}
