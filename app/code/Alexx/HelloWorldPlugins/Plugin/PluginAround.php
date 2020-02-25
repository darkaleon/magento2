<?php


namespace Alexx\HelloWorldPlugins\Plugin;


class PluginAround
{
    public function aroundGetHello($subject,callable $proceed)
    {
        $result = $proceed();
        return "<h1>" . $result . "</h1>";
    }
}
