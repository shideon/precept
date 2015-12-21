<?php
/**
 * @package johnpancoast/precept
 * @copyright (c) 2014-2015 John Pancoast
 * @author John Pancoast <johnpancoaster@gmail.com>
 * @license MIT
 */

namespace Pancoast\Precept;

/**
 * Input
 *
 * @package johnpancoast/precept
 * @copyright (c) 2014-2015 John Pancoast
 * @author John Pancoast <johnpancoaster@gmail.com>
 */
class Input implements InputInterface
{
    /**
     * @var array Input
     */
    private $input = [];

    /**
     * Constructor
     * @param array $input
     */
    public function __construct(array $input = [])
    {
        $this->setInput($input);
    }

    /**
     * {@inheritDoc}
     */
    public function setInput(array $input = array())
    {
        $this->input = $input;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getInputValue($key)
    {
        return isset($this->input[$key]) ? $this->input[$key] : null;
    }
}