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

class Config
{
    var $server;
    var $user;
    var $password;
    var $database;
    var $ini_file;

    function Config()
    {
        $ini_file = parse_ini_file("config/app.ini", true);
        $this->server = $ini_file["database"]["host"];
        $this->database = $ini_file["database"]["database"];
        $this->username = $ini_file["database"]["username"];
        $this->password = $ini_file["database"]["password"];
    }

    function getServer()
    {
        return $this->server;
    }

    function getDatabase()
    {
        return $this->database;
    }

    function getUsername()
    {
        return $this->username;
    }

    function getPassword()
    {
        return $this->password;
    }
}

