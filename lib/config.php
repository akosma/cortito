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

class Config {
    var $ini;

    function __construct() {
        $message = "This application requires an 'app.ini' file. Please copy the existing 'app.sample.ini' file and customize it.";
        $this->ini = parse_ini_file("config/app.ini", true) or die($message);
    }

    function getServer() {
        return $this->ini["database"]["host"];
    }

    function getDatabase() {
        return $this->ini["database"]["database"];
    }

    function getUsername() {
        return $this->ini["database"]["username"];
    }

    function getPassword() {
        return $this->ini["database"]["password"];
    }

    function getBrandName() {
        return $this->ini["branding"]["name"];
    }

    function getBrandUrl() {
        return $this->ini["branding"]["url"];
    }

    function getMaxShortLength() {
        return $this->ini["other"]["max_short_length"];
    }

    function getExcludedUrlShorteners() {
        $exclusions = $this->ini["other"]["excluded_url_shorteners"];
        return explode(',', $exclusions);
    }

    function getRandomStringCharacters() {
        return $this->ini["other"]["random_string_characters"];
    }
}

