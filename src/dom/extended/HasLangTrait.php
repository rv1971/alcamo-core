<?php

namespace alcamo\dom\extended;

use alcamo\ietf\Lang;

trait HasLangTrait
{
    use RegisteredNodeTrait;

    private $lang_; ///< Lang object, or false.

    /// Return xml:lang of element or closest ancestor, or false.
    public function getLang()
    {
        if (!isset($this->lang_)) {
            // Ensure conservation of the derived object.
            $this->hash();

            /* For efficiency, first check if the element itself has an
             * xml:lang attribute since this is a frequent case in
             * practice. */
            if ($this->hasAttributeNS(Document::NS['xml'], 'lang')) {
                $this->lang_ = Lang::newFromString(
                    $this->getAttributeNS(Document::NS['xml'], 'lang')
                );
            } else {
                $langAttr = $this->query('ancestor::*[@xml:lang]/@xml:lang')[0];

                if (isset($langAttr)) {
                    $this->lang_ = Lang::newFromString($langAttr->value);
                } else {
                    $this->lang_ = false;
                }
            }
        }

        return $this->lang_;
    }
}