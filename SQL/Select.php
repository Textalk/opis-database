<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013 Marius Sarca
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

namespace Opis\Database\SQL;

use Opis\Database\Database;

class Select extends SelectStatement
{
    
    protected $database;
    
    public function __construct(Database $database)
    {
        parent::__construct($database->getCompiler());
        $this->database = $database;
    }
    
    public static function factory(Database $database)
    {
        return new self($database);
    }
    
    public function execute($first = false)
    {
        if($first === true)
        {
            return $this->database->cmdSelectFirst((string) $this, $this->compiler->getParams());
        }
        elseif(is_string($first))
        {
            $index = 0;
            foreach($this->columns as $key => &$column)
            {
                if($column['alias'] == $first || $column['name'] == $first)
                {
                    $index = $key;
                    break;
                }
            }
            
            return $this->database->cmdSelectColumn((string) $this, $this->compiler->getParams(), $index);
        }
        elseif(is_int($first))
        {
            return $this->database->cmdSelectColumn((string) $this, $this->compiler->getParams(), $first);
        }
        
        return $this->database->cmdSelect((string) $this, $this->compiler->getParams());
    }
    
}