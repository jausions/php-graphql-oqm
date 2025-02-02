<?php

namespace GraphQL\Tests;

use GraphQL\SchemaGenerator\SchemaInspector\TypeSubQueryGenerator;
use PHPUnit\Framework\TestCase;

class TypeSubQueryGeneratorTest extends TestCase
{


    /**
     * @covers \GraphQL\SchemaGenerator\SchemaInspector\TypeSubQueryGenerator::getSubTypeQuery
     *
     * @return void
     */
    public function testItShouldGenerateSubQueryWith4OfTypeLevelsByDefault(): void
    {
        $sut = new TypeSubQueryGenerator();

        $actual = $sut->getSubTypeQuery();

        $expected = <<<QUERY
type{
  name
  kind
  description
  ofType{
    name
    kind
    ofType{
      name
      kind
      ofType{
        name
        kind
        ofType{
          name
          kind
        }
      }
    }
  }
}
QUERY;

        $this->assertEquals($expected, $actual);

        // End testItShouldGenerateSubQueryWith4OfTypeLevelsByDefault().
    }


    /**
     * @covers \GraphQL\SchemaGenerator\SchemaInspector\TypeSubQueryGenerator::getSubTypeQuery
     *
     * @return void
     */
    public function testItShouldGenerateSubQueryWithoutOfTypeForZeroDepth(): void
    {
        $sut = new TypeSubQueryGenerator(0);

        $actual = $sut->getSubTypeQuery();

        $expected = <<<QUERY
type{
  name
  kind
  description
}
QUERY;
        $this->assertEquals($expected, $actual);

        // End testItShouldGenerateSubQueryWithoutOfTypeForZeroDepth().
    }


}
