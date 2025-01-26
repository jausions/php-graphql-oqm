<?php

namespace GraphQL\SchemaGenerator\SchemaInspector;

class TypeSubQueryGenerator
{

    /**
     * Cache the sub-queries to not rebuild them everytime
     *
     * @var array<integer, string>
     */
    private $ofTypes = [];


    /**
     * @param integer  $typeOfTypeDepth How deep it should go
     *
     * @return string
     */
    public function getSubTypeQuery(int $typeOfTypeDepth=4): string
    {
        $ofType = $this->getOfTypeSubQuery($typeOfTypeDepth);

        return "type{
  name
  kind
  description{$ofType}
}";

        // End getSubTypeQuery().
    }


    /**
     * @param integer  $depth How deep it should go
     *
     * @return string
     */
    private function getOfTypeSubQuery($depth): string
    {
        if (isset($this->ofTypes[$depth]) === false) {
            $this->ofTypes[$depth] = $this->generateOfTypeSubQuery($depth, '  ');
        }

        return $this->ofTypes[$depth];

        // End getOfTypeSubQuery().
    }


    /**
     * @param integer  $depth How deep it should go
     * @param string   $indent Current indentation
     *
     * @return string
     */
    private function generateOfTypeSubQuery(int $depth, string $indent): string
    {
        if ($depth <= 0) {
            return '';
        }

        $subQuery = $this->generateOfTypeSubQuery(($depth - 1), $indent.'  ');

        return "
{$indent}ofType{
{$indent}  name
{$indent}  kind{$subQuery}
{$indent}}";

        // End generateOfTypeSubQuery().
    }

}
