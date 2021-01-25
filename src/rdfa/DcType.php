<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Type;

/**
 * @sa [dc:type](http://purl.org/dc/terms/type).
 * @sa [DCMIType](http://purl.org/dc/terms/DCMIType)
 */
class DcType extends AbstractEnumeratorStmt {
  const PROPERTY = 'dc:type';

  const VALUES = [
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
