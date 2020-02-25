<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Plugin;


class PluginBefore
{


    public function afterGetHello($subject, $result)
    {
        return 'prefix_'. $result ;
    }

}
