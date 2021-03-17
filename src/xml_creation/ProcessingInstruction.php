<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;
use alcamo\xml\Syntax;

/// XML processing instruction
class ProcessingInstruction extends AbstractNode
{
    protected $target_; ///< PI target.

    public function __construct(string $target, $content)
    {
        if (
            !preg_match(Syntax::NAME_REGEXP, $target)
            || strtolower(substr($target, 0, 3)) == 'xml'
        ) {
            throw new SyntaxError($target, null, '; not a valid XML PI target');
        }

        $this->target_ = $target;

        if (strpos($content, '?>') !== false) {
          /** @throw SyntaxError if $content contains "?>". */
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

    public function __toString()
    {
        return "<?{$this->target_} {$this->content_}?>";
    }
}
