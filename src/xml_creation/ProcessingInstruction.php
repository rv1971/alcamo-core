<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;
use alcamo\xml\Syntax;

/**
 * @brief XML processing instruction that can be serialized to XML text
 *
 * @sa [XML processing instructions](https://www.w3.org/TR/xml/#sec-pi)
 *
 * @date Last reviewed 2021-06-15
 */
class ProcessingInstruction extends AbstractNode
{
    protected $target_; ///< string

    public function __construct(string $target, $content)
    {
        if (!preg_match(Syntax::NAME_REGEXP, $target)) {
            /** @throw alcamo::exception::SyntaxError if $traget is not a
             *  valid PI target. */
            throw new SyntaxError($target, null, '; not a valid XML PI target');
        }

        $this->target_ = $target;

        if (strpos($content, '?>') !== false) {
          /** @throw alcamo::exception::SyntaxError if $content contains
           *  "?>". */
            throw new SyntaxError(
                $content,
                strpos($content, '?>'),
                '; "?>" in XML PI'
            );
        }

        parent::__construct($content);
    }

    public function getTarget(): string
    {
        return $this->target_;
    }

    /// @copydoc NodeInterface::__toString()
    public function __toString()
    {
        return "<?$this->target_ $this->content_?>";
    }
}
