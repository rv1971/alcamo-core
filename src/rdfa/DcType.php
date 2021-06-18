<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Type;

/**
 * @brief dc:type RDFa statement
 *
 * @sa [dc:type](http://purl.org/dc/terms/type)
 * @sa [DCMIType](http://purl.org/dc/terms/DCMIType)
 *
 * @date Last reviewed 2021-06-18
 */
class DcType extends AbstractEnumeratorStmt
{
    public const PROPERTY_CURIE = 'dc:type';

    public const VALUES = [
        'Collection',
        'Dataset',
        'Event',
        'Image',
        'InteractiveResource',
        'MovingImage',
        'PhysicalObject',
        'Service',
        'Software',
        'Sound',
        'StillImage',
        'Text'
    ];
}
