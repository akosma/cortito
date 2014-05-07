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

class Connection {
    var $connection;

    function __construct($server, $database, $user, $password) {
        $this->connection = mysqli_connect($server, $user, $password, $database)
            or trigger_error(mysqli_error(), E_USER_ERROR);
        mysqli_select_db($this->connection, $database);
    }

    function __destruct() {
        $this->connection->close();
        $this->connection = null;
    }

    function read($query) {
        $results = mysqli_query($this->connection, $query) or die(mysql_error());
        $recordset = new Recordset($results);
        return $recordset;
    }

    function write($query) {
        mysqli_query($this->connection, $query) or die(mysql_error());
    }

    function escape($string) {
        return mysqli_real_escape_string($this->connection, $string);
    }
}

class Recordset {
    var $recordset;
    var $count;

    function __construct($rs) {
        $this->recordset = $rs;
        $this->count = mysqli_num_rows($rs);
    }

    function __destruct() {
        mysqli_free_result($this->recordset);
        $this->recordset = null;
    }

    function next() {
        return mysqli_fetch_assoc($this->recordset);
    }

    function count() {
        return $this->count;
    }
}

