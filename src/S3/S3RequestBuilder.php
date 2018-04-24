<?php
/**
 * Emmanuel BORGES
 * contact@eborges.fr
 */
namespace App\S3;

class S3RequestBuilder
{
    private $_builder = [];
    private $_map = [];
    private $_table = '';

    /**
     * @param string $key
     * @param string $value
     * @return S3RequestBuilder
     */
    public function addItem(string $key, string $value): self
    {
        $this->_builder[$key] = ['S' => $value];
        return $this;
    }

    /**
     * @param string $mapKey
     * @param string $key
     * @param string $value
     * @return S3RequestBuilder
     */
    public function addItemMap(string $mapKey, string $key, string $value): self
    {
        $this->_map[$mapKey][$key] = $value;
        return $this;
    }

    /**
     * @param string $tableName
     * @return S3RequestBuilder
     */
    public function setTable(string $tableName): self
    {
        $this->_table = $tableName;
        return $this;
    }
    /**
     * @return array
     */
    public function build(): array
    {
        $this->_builder['lastUpdate'] = [
            'S' => (new \DateTime())->format('c')
        ];

        foreach ($this->_map as $key => $values) {
            $tmp = [];
            foreach ($values as $k => $v) {
                $tmp[$k] = ['S' => $v];
            }
            $this->_builder[$key] = [
                'M' => $tmp
            ];
        }
        $args = [
            'TableName' => $this->_table,
            'Item' => $this->_builder
        ];

        return $args;
    }
}
