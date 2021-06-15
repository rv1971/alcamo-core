<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;

/**
 * @brief XML declaration that can be serialized to XML text
 *
 * @date Last reviewed 2021-06-15
 */
class XmlDecl implements NodeInterface
{
    public const ENCODING_REGEXP = '/^[A-Za-z][-A-Za-z0-9._]*$/';
    public const VERSION_REGEXP = '/^1.\d+$/';

    protected $version_;
    protected $encoding_;
    protected $standalone_; ///< bool

    /**
     * @param $version XML version, defaults to 1.0
     *
     * @param $encoding Encoding, defaults to UTF-8
     *
     * @param $standalone Standalone document declaration, defaults to
     * `false`.
     */
    public function __construct(
        ?string $version = null,
        ?string $encoding = null,
        ?bool $standalone = null
    ) {
        if (
            isset($version) && !preg_match(self::VERSION_REGEXP, $version)
        ) {
            /** @throw alcamo::exception::SyntaxError if $version is not a
             *  valid version. */
            throw new SyntaxError($version, null, '; not a valid XML version');
        }

        $this->version_ = $version ?? '1.0';

        if (
            isset($encoding) && !preg_match(self::ENCODING_REGEXP, $encoding)
        ) {
            /** @throw alcamo::exception::SyntaxError if $encoding is not a
             *  valid encoding. */
            throw new SyntaxError($encoding, null, '; not a valid XML encoding');
        }

        $this->encoding_ = $encoding ?? 'UTF-8';

        $this->standalone_ = $standalone ?? false;
    }

    /// @copydoc NodeInterface::getContent()
    public function getContent()
    {
        return null;
    }

    public function getVersion(): string
    {
        return $this->version_;
    }

    public function getEncoding(): string
    {
        return $this->encoding_;
    }

    public function getStandalone(): bool
    {
        return $this->standalone_;
    }

    /// @copydoc NodeInterface::__toString()
    public function __toString()
    {
        $result =
            "<?xml version=\"$this->version_\" encoding=\"$this->encoding_\"";

        if ($this->standalone_) {
            $result .= ' standalone="yes"';
        }

        return "$result?>";
    }
}
