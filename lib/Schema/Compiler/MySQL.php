<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013-2015 Marius Sarca
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\Database\Schema\Compiler;

use Opis\Database\Schema\Compiler;
use Opis\Database\Schema\BaseColumn;
use Opis\Database\Schema\AlterTable;

class MySQL extends Compiler
{
    protected $wrapper = '`%s`';
    
    
    protected function handleTypeInteger(BaseColumn $column)
    {
        switch($column->get('size', 'normal'))
        {
            case 'tiny':
                return 'TINYINT';
            case 'small':
                return 'SMALLINT';
            case 'medium':
                return 'MEDIUMINT';
            case 'big':
                return 'BIGINT';
        }
        
        return 'INT';
    }
    
    protected function handleTypeDecimal(BaseColumn $column)
    {
        if(null !== $m = $column->get('M') && null !== $p = $column->get('P'))
        {
            return 'DECIMAL (' . $this->value($m) . ', ' . $this->value($p) . ')';
        }
        
        return 'DECIMAL';
    }
    
    protected function handleTypeBoolean(BaseColumn $column)
    {
        return 'TINYINT(1)';
    }
    
    protected function handleTypeText(BaseColumn $column)
    {
        switch($column->get('size', 'normal'))
        {
            case 'tiny':
            case 'small':
                return 'TINYTEXT';
            case 'medium':
                return 'MEDIUMTEXT';
            case 'big':
                return 'LONGTEXT';
        }
        
        return 'TEXT';
    }
    
    protected function handleTypeBinary(BaseColumn $column)
    {
        switch($column->get('size', 'normal'))
        {
            case 'tiny':
            case 'small':
                return 'TINYBLOB';
            case 'medium':
                return 'MEDIUMBLOB';
            case 'big':
                return 'LONGBLOB';
        }
        
        return 'BLOB';
    }
    
    protected function handleDropPrimaryKey(AlterTable $table, $data)
    {
        return 'ALTER TABLE ' . $this->wrap($table->getTableName()) . ' DROP PRIMARY KEY';
    }
    
    protected function handleDropUniqueKey(AlterTable $table, $data)
    {
        return 'ALTER TABLE ' . $this->wrap($table->getTableName()) . ' DROP INDEX ' . $this->wrap($data);
    }
    
    protected function handleDropIndex(AlterTable $table, $data)
    {
        return 'ALTER TABLE ' . $this->wrap($table->getTableName()) . ' DROP INDEX ' . $this->wrap($data);
    }
    
    protected function handleDropForeignKey(AlterTable $table, $data)
    {
        return 'ALTER TABLE ' . $this->wrap($table->getTableName()) . ' DROP FOREIGN KEY ' . $this->wrap($data);
    }
    
    protected function handleSetDefaultValue(AlterTable $table, $data)
    {
        return 'ALTER TABLE ' . $this->wrap($table->getTableName()) . ' ALTER '
        . $this->wrap($data['column']) . ' SET DEFAULT ' . $this->value($data['value']);
    }
    
    protected function handleDropDefaultValue(AlterTable $table, $data)
    {
        return 'ALTER TABLE ' . $this->wrap($table->getTableName()) . ' ALTER ' . $this->wrap($data) . ' DROP DEFAULT';
    }
    
    protected function handleRenameColumn(AlterTable $table, $data)
    {
        $table_name = $table->getTableName();
        $column_name = $data['from'];
        $new_name = $data['column']->getName();
        $columns = $this->connection->schema()->getColumns($table_name, false, false);
        $column_type = isset($columns[$column_name]) ? $columns[$column_name]['type'] : 'integer';
        
        return 'ALTER TABLE ' . $this->wrap($table_name) . ' CHANGE '. $this->wrap($column_name)
        . ' '.  $this->wrap($new_name) . ' ' . $column_type;
    }
    
}
