<?php
/*
Copyright (c) 2009-2014, Adrian Kosmaczewski
All rights reserved.

Redistribution and use in source and binary forms, with or without modification,
are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list
of conditions and the following disclaimer.  Redistributions in binary form must
reproduce the above copyright notice, this list of conditions and the following
disclaimer in the documentation and/or other materials provided with the
distribution.  Neither the name of Adrian Kosmaczewski or akosma software nor
the names of its contributors may be used to endorse or promote products derived
from this software without specific prior written permission.  THIS SOFTWARE IS
PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR
IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT
SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE
OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

class Connection
{
    var $server;
    var $user;
    var $password;
    var $database;
    var $connection;

    function Connection($server, $database, $user, $password)
    {
        $this->server = $server;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
    }

    function open()
    {
        $this->connection = mysqli_connect($this->server, $this->user, $this->password, $this->database)
            or trigger_error(mysqli_error(), E_USER_ERROR);
    }

    function execute($query)
    {
        mysqli_select_db($this->connection, $this->database);
        $results = mysqli_query($this->connection, $query) or die(mysql_error());
        $recordset = new Recordset($results);
        return $recordset;
    }

    function close()
    {
        $this->connection = null;
    }
}

class Recordset
{
    var $recordset;
    var $count;

    function Recordset($rs)
    {
        $this->recordset = $rs;
        $this->count = mysqli_num_rows($rs);
    }

    function next()
    {
        return mysqli_fetch_assoc($this->recordset);
    }

    function count()
    {
        return $this->count;
    }

    function close()
    {
        mysqli_free_result($this->recordset);
        $this->recordset = null;
    }
}

function execute($query, $server, $database, $user, $password)
{
    $conn = new Connection($server, $database, $user, $password);
    $conn->open();
    $recordset = $conn->execute($query);
    $conn->close();
    return $recordset;
}

