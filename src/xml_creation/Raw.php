<?php

namespace alcamo\xml_creation;

/**
 * @brief Raw XML code to be serialized unchanged
 *
 * @date Last reviewed 2021-06-15
 */
class Raw extends AbstractNode
{
    /// @copydoc NodeInterface::__toString()
    public function __toString()
    {
        return (string)$this->content_;
    }
}
