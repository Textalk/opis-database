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

namespace Opis\Database\DSN;

use Opis\Database\Connection;

class PostgreSQL extends Connection
{
    protected $properties = array(
        'host' => 'localhost',
        'port' => '5432',
    );
    
    public function __construct($name)
    {
        parent::__construct('pgsql', $name);
    }
    
    public function database($name)
    {
        return $this->set('dbase', $name);
    }
    
    public function host($name)
    {
        return $this->set('host', $name);
    }
    
    public function port($value)
    {
        return $this->set('port', $value);
    }
}
