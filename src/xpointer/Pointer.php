<?php

namespace alcamo\xpointer;

use alcamo\exception\SyntaxError;
use alcamo\xml\XName;
use alcamo\xml\exception\UnknownNamespacePrefix;

/*
 * @sa https://www.w3.org/TR/xptr-framework/
 */
class Pointer implements PointerInterface
{
    public const SCHEME_MAP = [
        'xmlns'    => XmlnsPart::class,
        'xpointer' => XPointerPart::class
    ];

    public const INITIAL_NS_BINDINGS = [
        'xml' => 'http://www.w3.org/XML/1998/namespace'
    ];

    /// Regular expression for XML NCName
    public const NAME_REGEXP =
    '/^[\pL:_][-\pL:.\d\x{B7}\x{0300}-\x{036F}\x{203F}-\x{2040}]*$/u';

    private $shorthand_;       ///< ?string
    private $parts_;           ///< ?array of pairs of scheme name and data

    /**
     * @warning Unescaped parentheses in scheme data are not supported, not
     * even when balanced.
     */
    public static function newFromString(string $fragment)
    {
        if (strpos($fragment, '(') === false) {
            return new static($fragment, null);
        } else {
            $pieces = preg_split(
                '/\(((?:\^\)|[^)])*)\)/',
                $fragment,
                -1,
                PREG_SPLIT_DELIM_CAPTURE
            );

            $parts = [];

            for ($i = 0; isset($pieces[$i]) && $pieces[$i]; $i += 2)
            {
                $schemeName = ltrim($pieces[$i]);
                $schemeData = $pieces[$i + 1];

                if (!preg_match(self::NAME_REGEXP, $schemeName)) {
                    throw new SyntaxError(
                        $schemeName,
                        null,
                        '; invalid scheme name'
                    );
                }

                if (
                    preg_match(
                        '/(?<!\^)\^[^^()]/',
                        $schemeData,
                        $matches2,
                        PREG_OFFSET_CAPTURE)
                ) {
                    throw new SyntaxError(
                        $schemeData,
                        $matches2[0][1],
                        '; invalid use of circumflex'
                    );
                }

                $schemeData = str_replace(
                    [ '^(', '^)', '^^' ],
                    [ '(', ')', '^^' ],
                    $schemeData
                );

                $parts[] = [ $schemeName, $schemeData ];
            }

            return new static(null, $parts);
        }
    }

    private function __construct(?string $shorthand, ?array $parts) {
        $this->shorthand_ = $shorthand;
        $this->parts_ = $parts;
    }

    public function process(\DOMDocument $doc)
    {
        if (isset($this->shorthand_)) {
            return $doc->getElementById($this->shorthand_);
        }

        $nsBindings = static::INITIAL_NS_BINDINGS;

        foreach ($this->parts_ as $part) {
            [ $schemeName, $schemeData ] = $part;

            try {
                $schemeName = (string)XName::newFromQNameAndMap(
                    $schemeName,
                    $nsBindings
                );
            } catch (UnknownNamespacePrefix $e) {
                continue;
            }

            if (isset(static::SCHEME_MAP[$schemeName])) {
                $class = static::SCHEME_MAP[$schemeName];

                $result =
                    (new $class())->process($nsBindings, $schemeData, $doc);
            }

            if (isset($result)) {
                return $result;
            }
        }
    }
}
