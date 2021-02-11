<?php

namespace alcamo\dom\schema;

use PHPUnit\Framework\TestCase;
use alcamo\dom\extended\Document;
use alcamo\dom\schema\component\PredefinedType;
use alcamo\dom\xsd\Document as Xsd;
use alcamo\xml\XName;

class SchemaTest extends TestCase
{
    public const XML_NS = 'http://www.w3.org/XML/1998/namespace';
    public const XSD_NS = 'http://www.w3.org/2001/XMLSchema';
    public const XSI_NS = 'http://www.w3.org/2001/XMLSchema-instance';

    public function testNewFromDocument()
    {
        $baz = Document::newFromUrl(
            'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
            . 'baz.xml'
        );

        $schema1 = Schema::newFromDocument($baz);

        $schema2 = Schema::newFromDocument($baz);

        $this->assertSame($schema1, $schema2);

        $xsds = [ 'XMLSchema.xsd', 'xml.xsd' ];

        $this->assertSame(count($xsds), count($schema1->getXsds()));

        $i = 0;
        foreach ($schema1->getXsds() as $url => $xsd) {
            $this->assertSame($xsds[$i++], basename($url));
        }

        $this->assertEquals(
            'anyType',
            $schema1->getAnyType()->getXsdElement()['name']
        );

        $this->assertEquals(
            new PredefinedType(
                $schema1,
                new XName(self::XSD_NS, 'anySimpleType'),
                $schema1->getAnyType()
            ),
            $schema1->getAnySimpleType()
        );
    }

    public function testNewFromXsds()
    {
        $baseUrl = 'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR;

        $xsds = [
            Xsd::newFromUrl("$baseUrl/foo.xsd"),
            Xsd::newFromUrl("$baseUrl/bar.xsd"),
        ];

        $schema1 = Schema::newFromXsds($xsds);

        $schema2 = Schema::newFromXsds($xsds);

        $this->assertSame($schema1, $schema2);

        $xsds = [
            'foo.xsd',
            'rdfs.xsd',
            'dc.xsd',
            'xml.xsd',
            'bar.xsd',
            'XMLSchema.xsd'
        ];

        $this->assertSame(count($xsds), count($schema1->getXsds()));

        $i = 0;
        foreach ($schema1->getXsds() as $url => $xsd) {
            $this->assertSame($xsds[$i++], basename($url));
        }
    }

    /**
     * @dataProvider getGlobalAttrProvider
     */
    public function testGetGlobalAttr($schema, $attrNs, $attrLocalName)
    {
        $comp = $schema->getGlobalAttr(new XName($attrNs, $attrLocalName));

        $this->assertInstanceOf(component\Attr::class, $comp);
        $this->assertSame($schema, $comp->getSchema());
    }

    public function getGlobalAttrProvider()
    {
        $schema = Schema::newFromDocument(
            Document::newFromUrl(
                'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'baz.xml'
            )
        );

        return [
            'xml:lang' =>  [ $schema, self::XML_NS, 'lang' ],
            'xml:space' => [ $schema, self::XML_NS, 'space' ],
            'xml:base' =>  [ $schema, self::XML_NS, 'base' ],
            'xml:id' =>    [ $schema, self::XML_NS, 'id' ]
        ];
    }

    /**
     * @dataProvider getGlobalAttrGroupProvider
     */
    public function testGetGlobalAttrGroup(
        $schema,
        $attrGroupNs,
        $attrGroupLocalName
    ) {
        $comp = $schema->getGlobalAttrGroup(
            new XName($attrGroupNs, $attrGroupLocalName)
        );

        $this->assertInstanceOf(component\AttrGroup::class, $comp);
        $this->assertSame($schema, $comp->getSchema());
    }

    public function getGlobalAttrGroupProvider()
    {
        $schema = Schema::newFromDocument(
            Document::newFromUrl(
                'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'baz.xml'
            )
        );

        return [
            'xml:specialAttrs' => [ $schema, self::XML_NS, 'specialAttrs' ],
            'xsd:occurs' => [ $schema, self::XSD_NS, 'occurs' ],
            'xsd:defRef' => [ $schema, self::XSD_NS, 'defRef' ]
        ];
    }

    /**
     * @dataProvider getGlobalComplexTypeProvider
     */
    public function testGetGlobalComplexType(
        $schema,
        $complexTypeNs,
        $complexTypeLocalName
    ) {
        $comp = $schema->getGlobalType(
            new XName($complexTypeNs, $complexTypeLocalName)
        );

        $this->assertInstanceOf(component\ComplexType::class, $comp);
        $this->assertSame($schema, $comp->getSchema());
    }

    public function getGlobalComplexTypeProvider()
    {
        $schema = Schema::newFromDocument(
            Document::newFromUrl(
                'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'baz.xml'
            )
        );

        return [
            'xsd:openAttrs' => [ $schema, self::XSD_NS, 'openAttrs' ],
            'xsd:annotated' => [ $schema, self::XSD_NS, 'annotated' ],
            'xsd:attribute' => [ $schema, self::XSD_NS, 'attribute' ],
            'xsd:topLevelAttribute' => [ $schema, self::XSD_NS, 'topLevelAttribute' ],
            'xsd:complexType' => [ $schema, self::XSD_NS, 'complexType' ],
            'xsd:topLevelComplexType' => [ $schema, self::XSD_NS, 'topLevelComplexType' ],
            'xsd:localComplexType' => [ $schema, self::XSD_NS, 'localComplexType' ],
            'xsd:restrictionType' => [ $schema, self::XSD_NS, 'restrictionType' ],
            'xsd:complexRestrictionType' => [ $schema, self::XSD_NS, 'complexRestrictionType' ],
            'xsd:extensionType' => [ $schema, self::XSD_NS, 'extensionType' ],
            'xsd:simpleRestrictionType' => [ $schema, self::XSD_NS, 'simpleRestrictionType' ],
            'xsd:simpleExtensionType' => [ $schema, self::XSD_NS, 'simpleExtensionType' ],
            'xsd:element' => [ $schema, self::XSD_NS, 'element' ],
            'xsd:topLevelElement' => [ $schema, self::XSD_NS, 'topLevelElement' ],
            'xsd:localElement' => [ $schema, self::XSD_NS, 'localElement' ],
            'xsd:group' => [ $schema, self::XSD_NS, 'group' ],
            'xsd:realGroup' => [ $schema, self::XSD_NS, 'realGroup' ],
            'xsd:namedGroup' => [ $schema, self::XSD_NS, 'namedGroup' ],
            'xsd:groupRef' => [ $schema, self::XSD_NS, 'groupRef' ],
            'xsd:explicitGroup' => [ $schema, self::XSD_NS, 'explicitGroup' ],
            'xsd:simpleExplicitGroup' => [ $schema, self::XSD_NS, 'simpleExplicitGroup' ],
            'xsd:narrowMaxMin' => [ $schema, self::XSD_NS, 'narrowMaxMin' ],
            'xsd:all' => [ $schema, self::XSD_NS, 'all' ],
            'xsd:wildcard' => [ $schema, self::XSD_NS, 'wildcard' ],
            'xsd:attributeGroup' => [ $schema, self::XSD_NS, 'attributeGroup' ],
            'xsd:namedAttributeGroup' => [ $schema, self::XSD_NS, 'namedAttributeGroup' ],
            'xsd:attributeGroupRef' => [ $schema, self::XSD_NS, 'attributeGroupRef' ],
            'xsd:keybase' => [ $schema, self::XSD_NS, 'keybase' ],
            'xsd:anyType' => [ $schema, self::XSD_NS, 'anyType' ],
            'xsd:simpleType' => [ $schema, self::XSD_NS, 'simpleType' ],
            'xsd:topLevelSimpleType' => [ $schema, self::XSD_NS, 'topLevelSimpleType' ],
            'xsd:localSimpleType' => [ $schema, self::XSD_NS, 'localSimpleType' ],
            'xsd:facet' => [ $schema, self::XSD_NS, 'facet' ],
            'xsd:noFixedFacet' => [ $schema, self::XSD_NS, 'noFixedFacet' ],
            'xsd:numFacet' => [ $schema, self::XSD_NS, 'numFacet' ]
        ];
    }

    /**
     * @dataProvider getGlobalElementProvider
     */
    public function testGetGlobalElement($schema, $elementNs, $elementLocalName)
    {
        $comp = $schema->getGlobalElement(new XName($elementNs, $elementLocalName));

        $this->assertInstanceOf(component\Element::class, $comp);
        $this->assertSame($schema, $comp->getSchema());
    }

    public function getGlobalElementProvider()
    {
        $schema = Schema::newFromDocument(
            Document::newFromUrl(
                'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'baz.xml'
            )
        );

        return [
            'xsd:schema' => [ $schema, self::XSD_NS, 'schema' ],
            'xsd:anyAttribute' => [ $schema, self::XSD_NS, 'anyAttribute' ],
            'xsd:complexContent' => [ $schema, self::XSD_NS, 'complexContent' ],
            'xsd:simpleContent' => [ $schema, self::XSD_NS, 'simpleContent' ],
            'xsd:complexType' => [ $schema, self::XSD_NS, 'complexType' ],
            'xsd:element' => [ $schema, self::XSD_NS, 'element' ],
            'xsd:all' => [ $schema, self::XSD_NS, 'all' ],
            'xsd:choice' => [ $schema, self::XSD_NS, 'choice' ],
            'xsd:sequence' => [ $schema, self::XSD_NS, 'sequence' ],
            'xsd:group' => [ $schema, self::XSD_NS, 'group' ],
            'xsd:any' => [ $schema, self::XSD_NS, 'any' ],
            'xsd:attribute' => [ $schema, self::XSD_NS, 'attribute' ],
            'xsd:attributeGroup' => [ $schema, self::XSD_NS, 'attributeGroup' ],
            'xsd:include' => [ $schema, self::XSD_NS, 'include' ],
            'xsd:redefine' => [ $schema, self::XSD_NS, 'redefine' ],
            'xsd:import' => [ $schema, self::XSD_NS, 'import' ],
            'xsd:selector' => [ $schema, self::XSD_NS, 'selector' ],
            'xsd:field' => [ $schema, self::XSD_NS, 'field' ],
            'xsd:unique' => [ $schema, self::XSD_NS, 'unique' ],
            'xsd:key' => [ $schema, self::XSD_NS, 'key' ],
            'xsd:keyref' => [ $schema, self::XSD_NS, 'keyref' ],
            'xsd:notation' => [ $schema, self::XSD_NS, 'notation' ],
            'xsd:appinfo' => [ $schema, self::XSD_NS, 'appinfo' ],
            'xsd:documentation' => [ $schema, self::XSD_NS, 'documentation' ],
            'xsd:annotation' => [ $schema, self::XSD_NS, 'annotation' ]
        ];
    }

    /**
     * @dataProvider getGlobalEnumerationTypeProvider
     */
    public function testGetGlobalEnumerationType(
        $schema,
        $enumerationTypeNs,
        $enumerationTypeLocalName
    ) {
        $comp = $schema->getGlobalType(
            new XName($enumerationTypeNs, $enumerationTypeLocalName)
        );

        $this->assertInstanceOf(component\EnumerationType::class, $comp);
        $this->assertSame($schema, $comp->getSchema());
    }

    public function getGlobalEnumerationTypeProvider()
    {
        $schema = Schema::newFromDocument(
            Document::newFromUrl(
                'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'baz.xml'
            )
        );

        return [
            'xsd:formChoice' => [ $schema, self::XSD_NS, 'formChoice' ],
            'xsd:reducedDerivationControl' => [ $schema, self::XSD_NS, 'reducedDerivationControl' ],
            'xsd:typeDerivationControl' => [ $schema, self::XSD_NS, 'typeDerivationControl' ],
            'xsd:derivationControl' => [ $schema, self::XSD_NS, 'derivationControl' ]
        ];
    }

    /**
     * @dataProvider getGlobalGroupProvider
     */
    public function testGetGlobalGroup($schema, $groupNs, $groupLocalName)
    {
        $comp = $schema->getGlobalGroup(new XName($groupNs, $groupLocalName));

        $this->assertInstanceOf(component\Group::class, $comp);
        $this->assertSame($schema, $comp->getSchema());
    }

    public function getGlobalGroupProvider()
    {
        $schema = Schema::newFromDocument(
            Document::newFromUrl(
                'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'baz.xml'
            )
        );

        return [
            'xsd:schemaTop' => [ $schema, self::XSD_NS, 'schemaTop' ],
            'xsd:redefinable' => [ $schema, self::XSD_NS, 'redefinable' ],
            'xsd:typeDefParticle' => [ $schema, self::XSD_NS, 'typeDefParticle' ],
            'xsd:nestedParticle' => [ $schema, self::XSD_NS, 'nestedParticle' ],
            'xsd:particle' => [ $schema, self::XSD_NS, 'particle' ],
            'xsd:attrDecls' => [ $schema, self::XSD_NS, 'attrDecls' ],
            'xsd:complexTypeModel' => [ $schema, self::XSD_NS, 'complexTypeModel' ],
            'xsd:allModel' => [ $schema, self::XSD_NS, 'allModel' ],
            'xsd:identityConstraint' => [ $schema, self::XSD_NS, 'identityConstraint' ],
            'xsd:simpleDerivation' => [ $schema, self::XSD_NS, 'simpleDerivation' ],
            'xsd:facets' => [ $schema, self::XSD_NS, 'facets' ],
            'xsd:simpleRestrictionModel' => [ $schema, self::XSD_NS, 'simpleRestrictionModel' ]
        ];
    }

    /**
     * @dataProvider getGlobalListTypeProvider
     */
    public function testGetGlobalListType(
        $schema,
        $listTypeNs,
        $listTypeLocalName
    ) {
        $comp = $schema->getGlobalType(
            new XName($listTypeNs, $listTypeLocalName)
        );

        $this->assertInstanceOf(component\ListType::class, $comp);
        $this->assertSame($schema, $comp->getSchema());
    }

    public function getGlobalListTypeProvider()
    {
        $schema = Schema::newFromDocument(
            Document::newFromUrl(
                'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'baz.xml'
            )
        );

        return [
            'xsd:IDREFS' => [ $schema, self::XSD_NS, 'IDREFS' ],
            'xsd:ENTITIES' => [ $schema, self::XSD_NS, 'ENTITIES' ],
            'xsd:NMTOKENS' => [ $schema, self::XSD_NS, 'NMTOKENS' ]
        ];
    }

    /**
     * @dataProvider getGlobalNotationProvider
     */
    public function testGetGlobalNotation($schema, $notationNs, $notationLocalName)
    {
        $comp = $schema->getGlobalNotation(new XName($notationNs, $notationLocalName));

        $this->assertInstanceOf(component\Notation::class, $comp);
        $this->assertSame($schema, $comp->getSchema());
    }

    public function getGlobalNotationProvider()
    {
        $schema = Schema::newFromDocument(
            Document::newFromUrl(
                'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'baz.xml'
            )
        );

        return [
            'xsd:XMLSchemaStructures' => [ $schema, self::XSD_NS, 'XMLSchemaStructures' ],
            'xsd:XML' => [ $schema, self::XSD_NS, 'XML' ]
        ];
    }

    /**
     * @dataProvider getGlobalPredefinedAttrProvider
     */
    public function testGetGlobalPredefinedAttr(
        $schema,
        $predefinedAttrNs,
        $predefinedAttrLocalName
    ) {
        $comp = $schema->getGlobalAttr(
            new XName($predefinedAttrNs, $predefinedAttrLocalName)
        );

        $this->assertInstanceOf(component\PredefinedAttr::class, $comp);
        $this->assertSame($schema, $comp->getSchema());
    }

    public function getGlobalPredefinedAttrProvider()
    {
        $schema = Schema::newFromDocument(
            Document::newFromUrl(
                'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'baz.xml'
            )
        );

        return [
            'xsd:' => [ $schema, self::XSI_NS, 'type' ]
        ];
    }

    /**
     * @dataProvider getGlobalPredefinedTypeProvider
     */
    public function testGetGlobalPredefinedType(
        $schema,
        $predefinedTypeNs,
        $predefinedTypeLocalName
    ) {
        $comp = $schema->getGlobalType(
            new XName($predefinedTypeNs, $predefinedTypeLocalName)
        );

        $this->assertInstanceOf(component\PredefinedType::class, $comp);
        $this->assertSame($schema, $comp->getSchema());
    }

    public function getGlobalPredefinedTypeProvider()
    {
        $schema = Schema::newFromDocument(
            Document::newFromUrl(
                'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'baz.xml'
            )
        );

        return [
            'xsd:anySimpleType' => [ $schema, self::XSD_NS, 'anySimpleType' ]
        ];
    }

    /**
     * @dataProvider getGlobalSimpleTypeProvider
     */
    public function testGetGlobalSimpleType(
        $schema,
        $simpleTypeNs,
        $simpleTypeLocalName
    ) {
        $comp = $schema->getGlobalType(
            new XName($simpleTypeNs, $simpleTypeLocalName)
        );

        $this->assertInstanceOf(component\AbstractSimpleType::class, $comp);
        $this->assertSame($schema, $comp->getSchema());
    }

    public function getGlobalSimpleTypeProvider()
    {
        $schema = Schema::newFromDocument(
            Document::newFromUrl(
                'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'baz.xml'
            )
        );

        return [
            'xsd:formChoice' => [ $schema, self::XSD_NS, 'formChoice' ],
            'xsd:reducedDerivationControl' => [ $schema, self::XSD_NS, 'reducedDerivationControl' ],
            'xsd:derivationSet' => [ $schema, self::XSD_NS, 'derivationSet' ],
            'xsd:typeDerivationControl' => [ $schema, self::XSD_NS, 'typeDerivationControl' ],
            'xsd:fullDerivationSet' => [ $schema, self::XSD_NS, 'fullDerivationSet' ],
            'xsd:allNNI' => [ $schema, self::XSD_NS, 'allNNI' ],
            'xsd:blockSet' => [ $schema, self::XSD_NS, 'blockSet' ],
            'xsd:namespaceList' => [ $schema, self::XSD_NS, 'namespaceList' ],
            'xsd:public' => [ $schema, self::XSD_NS, 'public' ],
            'xsd:string' => [ $schema, self::XSD_NS, 'string' ],
            'xsd:boolean' => [ $schema, self::XSD_NS, 'boolean' ],
            'xsd:float' => [ $schema, self::XSD_NS, 'float' ],
            'xsd:double' => [ $schema, self::XSD_NS, 'double' ],
            'xsd:decimal' => [ $schema, self::XSD_NS, 'decimal' ],
            'xsd:duration' => [ $schema, self::XSD_NS, 'duration' ],
            'xsd:dateTime' => [ $schema, self::XSD_NS, 'dateTime' ],
            'xsd:time' => [ $schema, self::XSD_NS, 'time' ],
            'xsd:date' => [ $schema, self::XSD_NS, 'date' ],
            'xsd:gYearMonth' => [ $schema, self::XSD_NS, 'gYearMonth' ],
            'xsd:gYear' => [ $schema, self::XSD_NS, 'gYear' ],
            'xsd:gMonthDay' => [ $schema, self::XSD_NS, 'gMonthDay' ],
            'xsd:gDay' => [ $schema, self::XSD_NS, 'gDay' ],
            'xsd:gMonth' => [ $schema, self::XSD_NS, 'gMonth' ],
            'xsd:hexBinary' => [ $schema, self::XSD_NS, 'hexBinary' ],
            'xsd:base64Binary' => [ $schema, self::XSD_NS, 'base64Binary' ],
            'xsd:anyURI' => [ $schema, self::XSD_NS, 'anyURI' ],
            'xsd:QName' => [ $schema, self::XSD_NS, 'QName' ],
            'xsd:NOTATION' => [ $schema, self::XSD_NS, 'NOTATION' ],
            'xsd:normalizedString' => [ $schema, self::XSD_NS, 'normalizedString' ],
            'xsd:token' => [ $schema, self::XSD_NS, 'token' ],
            'xsd:language' => [ $schema, self::XSD_NS, 'language' ],
            'xsd:IDREFS' => [ $schema, self::XSD_NS, 'IDREFS' ],
            'xsd:ENTITIES' => [ $schema, self::XSD_NS, 'ENTITIES' ],
            'xsd:NMTOKEN' => [ $schema, self::XSD_NS, 'NMTOKEN' ],
            'xsd:NMTOKENS' => [ $schema, self::XSD_NS, 'NMTOKENS' ],
            'xsd:Name' => [ $schema, self::XSD_NS, 'Name' ],
            'xsd:NCName' => [ $schema, self::XSD_NS, 'NCName' ],
            'xsd:ID' => [ $schema, self::XSD_NS, 'ID' ],
            'xsd:IDREF' => [ $schema, self::XSD_NS, 'IDREF' ],
            'xsd:ENTITY' => [ $schema, self::XSD_NS, 'ENTITY' ],
            'xsd:integer' => [ $schema, self::XSD_NS, 'integer' ],
            'xsd:nonPositiveInteger' => [ $schema, self::XSD_NS, 'nonPositiveInteger' ],
            'xsd:negativeInteger' => [ $schema, self::XSD_NS, 'negativeInteger' ],
            'xsd:long' => [ $schema, self::XSD_NS, 'long' ],
            'xsd:int' => [ $schema, self::XSD_NS, 'int' ],
            'xsd:short' => [ $schema, self::XSD_NS, 'short' ],
            'xsd:byte' => [ $schema, self::XSD_NS, 'byte' ],
            'xsd:nonNegativeInteger' => [ $schema, self::XSD_NS, 'nonNegativeInteger' ],
            'xsd:unsignedLong' => [ $schema, self::XSD_NS, 'unsignedLong' ],
            'xsd:unsignedInt' => [ $schema, self::XSD_NS, 'unsignedInt' ],
            'xsd:unsignedShort' => [ $schema, self::XSD_NS, 'unsignedShort' ],
            'xsd:unsignedByte' => [ $schema, self::XSD_NS, 'unsignedByte' ],
            'xsd:positiveInteger' => [ $schema, self::XSD_NS, 'positiveInteger' ],
            'xsd:derivationControl' => [ $schema, self::XSD_NS, 'derivationControl' ],
            'xsd:simpleDerivationSet' => [ $schema, self::XSD_NS, 'simpleDerivationSet' ]
        ];
    }

    /**
     * @dataProvider getGlobalUnionTypeProvider
     */
    public function testGetGlobalUnionType(
        $schema,
        $unionTypeNs,
        $unionTypeLocalName
    ) {
        $comp = $schema->getGlobalType(
            new XName($unionTypeNs, $unionTypeLocalName)
        );

        $this->assertInstanceOf(component\UnionType::class, $comp);
        $this->assertSame($schema, $comp->getSchema());
    }

    public function getGlobalUnionTypeProvider()
    {
        $schema = Schema::newFromDocument(
            Document::newFromUrl(
                'file:///' . dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'baz.xml'
            )
        );

        return [
            'xsd:derivationSet' => [ $schema, self::XSD_NS, 'derivationSet' ],
            'xsd:fullDerivationSet' => [ $schema, self::XSD_NS, 'fullDerivationSet' ],
            'xsd:allNNI' => [ $schema, self::XSD_NS, 'allNNI' ],
            'xsd:blockSet' => [ $schema, self::XSD_NS, 'blockSet' ],
            'xsd:namespaceList' => [ $schema, self::XSD_NS, 'namespaceList' ],
            'xsd:simpleDerivationSet' => [ $schema, self::XSD_NS, 'simpleDerivationSet' ]
        ];
    }
}
