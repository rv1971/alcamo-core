<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;

/**
 * @brief XML comment that can be serialized to XML text
 *
 * @sa [XML comments](https://www.w3.org/TR/xml/#sec-comments)
 *
 * @date Last reviewed 2021-06-15
 */
class Comment extends AbstractNode
{
    public function __construct($content)
    {
        if (strpos($content, '--') !== false) {
            /** @throw alcamo::exception::SyntaxError if $content contains
             *  double hyphen. */
            throw new SyntaxError(
                $content,
                strpos($content, '--'),
                '; double-hyphen in XML comment'
            );
        }

        parent::__construct($content);
    }

    /// @copydoc NodeInterface::__toString()
    public function __toString()
    {
        return "<!-- $this->content_ -->";
    }
}
