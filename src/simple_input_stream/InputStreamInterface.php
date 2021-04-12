<?php

namespace alcamo\simple_input_stream;

interface InputStreamInterface
{
    /// Whether more characters can be extracted
    public function isGood(): bool;

    /**
     * @brief Return the next character without extracting it
     *
     * @return `null` if isGood() == false, else next character
     */
    public function peek(): ?string;

    /**
     * @brief Extract a fixed number of characters.
     *
     * @return `null` if isGood() == false, else nonempty string.
     *
     * @throw Eof if there are characters left but less than `$count`.
     */
    public function extract(int $count = 1): ?string;

    /**
     * @brief Go back one character.
     *
     * @throw Underflow when at beginning of stream.
     */
    public function putback();

    /**
     * @brief characters up to a separator.
     *
     * @param $sep string Separator string. May be more than one character.
     *
     * @param $maxCount int Maximum number of characters to extract. `null`
     * means unlimited.
     *
     * @param $extractSep bool Whether to extract the separator itself and to
     * include it in the result.
     *
     * @return `null` if isGood() == false, else possibly empty string.
     */
    public function extractUntil(
        string $sep,
        ?int $maxCount = null,
        ?bool $extractSep = null
    ): ?string;
}
