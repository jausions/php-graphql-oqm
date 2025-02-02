<?php

namespace GraphQL\SchemaGenerator\SchemaInspector;

class TypeSubQueryGenerator
{

    /**
     * @var integer
     */
    private $depth;

    /**
     * Cache the sub-queries to not rebuild them everytime
     *
     * @var array<integer, string>
     */
    private static $ofTypes = [];


    /**
     * @param integer  $depth How many levels of `ofType` to generate
     */
    public function __construct(int $depth=4)
    {
        $this->depth = $depth;

        // End __construct().
    }


    /**
     * @return string
     */
    public function getSubTypeQuery(): string
    {
        $ofType = $this->getOfTypeSubQuery($this->depth);

        return "type{
  name
  kind
  description{$ofType}
}";

        // End getSubTypeQuery().
    }


    /**
     * @param integer  $depth How many levels of `ofType` to generate
     *
     * @return string
     */
    private function getOfTypeSubQuery($depth): string
    {
        if (isset(self::$ofTypes[$depth]) === false) {
            self::$ofTypes[$depth] = $this->generateOfTypeSubQuery($depth, '  ');
        }

        return self::$ofTypes[$depth];

        // End getOfTypeSubQuery().
    }


    /**
     * @param integer  $depth  How many levels of `ofType` to generate
     * @param string   $indent Indentation for the sub-query
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
