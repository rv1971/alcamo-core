<?php

namespace alcamo\xml;

/**
 * @brief Object identifyable by an expanded name
 *
 * @date Last reviewed 2021-06-15
 */
interface HasXNameInterface
{
    public function getXName(): XName;
}
