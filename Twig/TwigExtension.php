<?php

namespace mikemeier\PHPNodeBridge\Twig;

use mikemeier\PHPNodeBridge\Service\Bridge;
use Symfony\Component\HttpFoundation\Session\Session;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var Bridge
     */
    protected $bridge;
    /**
     * @var Session
     */
    protected $session;

    public function __construct(Bridge $bridge, Session $session)
    {
        $this->bridge = $bridge;
        $this->session = $session;
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
    public function phpnodebridgejs($varName = 'phpnodebridge')
    {
        $this->session->start();
        $identification = hash_hmac('sha512', $this->session->getId(), 'mykey');

        return '
            <script type="text/javascript" src="'. $this->bridge->getSocketIoClientUri() .'"></script>
            <script type="text/javascript">
                var '. $varName .' = io.connect(\''. $this->bridge->getSocketIoServerConnectionUri($identification) .'\');
            </script>
        ';
    }

}