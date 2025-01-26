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
     * @param TypeSubQueryGenerator|null $typeSubQueryGenerate Generator for oFType sub queries
     */
    public function __construct(Client $client, ?TypeSubQueryGenerator $typeSubQueryGenerate=null)
    {
        $this->client = $client;
        $this->typeSubQueryGenerate = ($typeSubQueryGenerate ?? new TypeSubQueryGenerator());

        // End __construct().
    }


    /**
     * @param integer  $typeOfTypeDepth How deep it should go
     *
     * @return string
     */
    private function getTypeSubQuery(int $typeOfTypeDepth=4): string
    {
        return $this->typeSubQueryGenerate->getSubTypeQuery($typeOfTypeDepth);

        // End getTypeSubQuery().
    }


    /**
     * @param integer  $typeOfTypeDepth How deep it should go
     *
     * @return array
     */
    public function getQueryTypeSchema(int $typeOfTypeDepth=4): array
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
        ".$this->getTypeSubQuery($typeOfTypeDepth)."
        args{
          name
          description
          defaultValue
          ".$this->getTypeSubQuery($typeOfTypeDepth)."
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
     * @param string   $objectName      The name of the object
     * @param integer  $typeOfTypeDepth How deep it should go
     *
     * @return array
     */
    public function getObjectSchema(string $objectName, int $typeOfTypeDepth=4): array
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
      ".$this->getTypeSubQuery($typeOfTypeDepth)."
      args{
        name
        description
        defaultValue
        ".$this->getTypeSubQuery($typeOfTypeDepth)."
      }
    }
  }
}";
        $response = $this->client->runRawQuery($schemaQuery, true);

        return $response->getData()['__type'];

        // End getObjectSchema().
    }


    /**
     * @param string   $objectName      The name of the object
     * @param integer  $typeOfTypeDepth How deep it should go
     *
     * @return array
     */
    public function getInputObjectSchema(string $objectName, int $typeOfTypeDepth=4): array
    {
        $schemaQuery = "{
  __type(name: \"$objectName\") {
    name
    kind
    inputFields {
      name
      description
      defaultValue
      ".$this->getTypeSubQuery($typeOfTypeDepth)."
    }
  }
}";
        $response = $this->client->runRawQuery($schemaQuery, true);

        return $response->getData()['__type'];

        // End getInputObjectSchema().
    }


    /**
     * @param string  $objectName The name of the object
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
     * @param string  $objectName The name of the object
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
