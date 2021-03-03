<?php

/**
 * @file
 *
 * @brief Class Element.
 */

namespace alcamo\dom\decorated;

use alcamo\dom\psvi\Element as BaseElement;

/**
 * @brief Element implementing the decorator pattern.
 *
 * The DOM framework has no means to generate different subclasses of
 * DOMElement for different XML element types. This class allows to delegate
 * element-type-specific functionality to a decorator object.
 */
class Element extends BaseElement
{
    private $decorator_ = false; ///< ?AbstractDecorator

    public function getDecorator()
    {
        if ($this->decorator_ === false) {
            // Ensure conservation of the derived object.
            $this->hash();

            $this->decorator_ = $this->ownerDocument->createDecorator($this);
        }

        return $this->decorator_;
    }

    public function __call($name, $params)
    {
        return call_user_func_array([ $this->getDecorator(), $name ], $params);
    }
}