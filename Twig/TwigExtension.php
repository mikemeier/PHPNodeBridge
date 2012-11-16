<?php

namespace mikemeier\PHPNodeBridge\Twig;

use mikemeier\PHPNodeBridge\Service\Bridge;
use mikemeier\PHPNodeBridge\Identification\IdentificationStrategyInterface;

class TwigExtension extends \Twig_Extension
{

    /**
     * @var Bridge
     */
    protected $bridge;

    /**
     * @var IdentificationStrategyInterface
     */
    protected $identificationStrategy;

    /**
     * @param Bridge $bridge
     * @param IdentificationStrategyInterface $identificationStrategy
     */
    public function __construct(Bridge $bridge, IdentificationStrategyInterface $identificationStrategy)
    {
        $this->bridge = $bridge;
        $this->identificationStrategy = $identificationStrategy;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mikemeierPHPNodeBridge';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'phpnodebridgejs' => new \Twig_Function_Method(
                $this,
                'phpnodebridgejs', array('pre_escape' => 'html', 'is_safe' => array('html'))
            )
        );
    }

    /**
     * @param string $varName
     * @return string
     */
    public function phpnodebridgejs($varname = 'bridge')
    {
        $identification = $this->identificationStrategy->getEncryptedIdentification();

        return '
            <script type="text/javascript" src="'. $this->bridge->getSocketIoClientUri() .'"></script>
            <script type="text/javascript">
                if(typeof mikemeier_php_node_bridge != "function"){
                    throw "Include first \'@mikemeierPHPNodeBridgeBundle/Resources/public/js/bridge.js\'";
                }
                var '. $varname .' = new mikemeier_php_node_bridge(io.connect(\''. $this->bridge->getSocketIoServerConnectionUri($identification) .'\'));
            </script>
        ';
    }

}