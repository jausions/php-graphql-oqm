<?php

namespace GraphQL\SchemaGenerator;

use GraphQL\Client;
use GraphQL\SchemaGenerator\SchemaInspector\TypeSubQueryGenerator;

/**
 * Class SchemaInspector
 *
 * @codeCoverageIgnore
 *
 * @package GraphQL\SchemaGenerator
 */
class SchemaInspector
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var TypeSubQueryGenerator
     */
    private $typeSubQueryGenerate;


    /**
     * SchemaInspector constructor.
     *
     * @param Client                     $client
     * @param TypeSubQueryGenerator|null $typeSubQueryGenerate Generator of sub queries for types
     */
    public function __construct(Client $client, ?TypeSubQueryGenerator $typeSubQueryGenerate=null)
    {
        $this->client = $client;
        $this->typeSubQueryGenerate = ($typeSubQueryGenerate ?? new TypeSubQueryGenerator(4));

        // End __construct().
    }


    /**
     * @return string
     */
    private function getTypeSubQuery(): string
    {
        return $this->typeSubQueryGenerate->getSubTypeQuery();

        // End getTypeSubQuery().
    }


    /**
     * @return array
     */
    public function getQueryTypeSchema(): array
    {
        $schemaQuery = "{
  __schema{
    queryType{
      name
      kind
      description
      fields(includeDeprecated: true){
        name
        description
        isDeprecated
        deprecationReason
        ".$this->getTypeSubQuery()."
        args{
          name
          description
          defaultValue
          ".$this->getTypeSubQuery()."
        }
      }
    }
  }
}";
        $response = $this->client->runRawQuery($schemaQuery, true);

        return $response->getData()['__schema']['queryType'];

        // End getQueryTypeSchema().
    }


    /**
     * @param string  $objectName The name of the object
     *
     * @return array
     */
    public function getObjectSchema(string $objectName): array
    {
        $schemaQuery = "{
  __type(name: \"$objectName\") {
    name
    kind
    fields(includeDeprecated: true){
      name
      description
      isDeprecated
      deprecationReason
      ".$this->getTypeSubQuery()."
      args{
        name
        description
        defaultValue
        ".$this->getTypeSubQuery()."
      }
    }
  }
}";
        $response = $this->client->runRawQuery($schemaQuery, true);

        return $response->getData()['__type'];

        // End getObjectSchema().
    }


    /**
     * @param string  $objectName The name of the object
     *
     * @return array
     */
    public function getInputObjectSchema(string $objectName): array
    {
        $schemaQuery = "{
  __type(name: \"$objectName\") {
    name
    kind
    inputFields {
      name
      description
      defaultValue
      ".$this->getTypeSubQuery()."
    }
  }
}";
        $response = $this->client->runRawQuery($schemaQuery, true);

        return $response->getData()['__type'];

        // End getInputObjectSchema().
    }


    /**
     * @param string  $objectName The name of the enum object
     *
     * @return array
     */
    public function getEnumObjectSchema(string $objectName): array
    {
        $schemaQuery = "{
  __type(name: \"$objectName\") {
    name
    kind
    enumValues {
      name
      description
    }
  }
}";
        $response = $this->client->runRawQuery($schemaQuery, true);

        return $response->getData()['__type'];

        // End getEnumObjectSchema().
    }


    /**
     * @param string  $objectName The name of the union object
     *
     * @return array
     */
    public function getUnionObjectSchema(string $objectName): array
    {
        $schemaQuery = "{
  __type(name: \"$objectName\") {
    name
    kind
    possibleTypes {
      kind
      name
    }
  }
}";
        $response = $this->client->runRawQuery($schemaQuery, true);

        return $response->getData()['__type'];

        // End getUnionObjectSchema().
    }


}
