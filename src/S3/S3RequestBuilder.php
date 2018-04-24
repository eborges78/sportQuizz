<?php
/**
 * Created by PhpStorm.
 * User: manu
 * Date: 24/04/2018
 * Time: 23:14
 */

namespace App\S3;

class S3RequestBuilder
{
    private $_builder = [];
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
     * @param string $key
     * @param array $values
     * @return S3RequestBuilder
     */
    public function addItemMap(string $key, array $values): self
    {
        $tmp = [];
        foreach ($values as $k => $value) {
            $tmp[$k] = ['S' => $value];
        }
        $this->_builder[$key] = ['M' => $tmp];
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
        $args = [
            'TableName' => $this->_table,
            'Item' => $this->_builder
        ];

        return $args;
    }
}
