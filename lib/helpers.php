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

require 'database.php';
require 'config.php';

class Helper {
    var $config;
    var $conn;

    function __construct() {
        $this->config = new Config;
        $server = $this->config->getServer();
        $database = $this->config->getDatabase();
        $username = $this->config->getUsername();
        $password = $this->config->getPassword();
        $this->conn = new Connection($server, $database, $username, $password);
    }

    function __destruct() {
        $this->config = null;
        $this->conn = null;
    }

    function find_by_shortened($shortened) {
        $query = sprintf("SELECT * FROM items WHERE shortened = '%s'",
            $this->conn->escape($shortened));
        $recordset = $this->conn->read($query);
        $row = $recordset->next();
        return $row;
    }

    function find_by_original($original) {
        $query = sprintf("SELECT * FROM items WHERE original = '%s'",
            $this->conn->escape($original));
        $recordset = $this->conn->read($query);
        $row = $recordset->next();
        return $row;
    }

    function insert_url($original, $shortened) {
        $query = sprintf("INSERT INTO items (original, shortened) VALUES ('%s', '%s')",
            $this->conn->escape($original),
            $this->conn->escape($shortened));
        $this->conn->write($query);
    }

    function update_count($id, $count) {
        $query = "UPDATE items SET count = $count WHERE id = $id";
        $this->conn->write($query);
    }

    function generate_random_string($length = 6) {
        // Adapted from
        // http://stackoverflow.com/a/4356295/133764
        $characters = $this->config->getRandomStringCharacters();
        $randomString = '';
        $len = mt_rand(1, $length);
        for ($i = 0; $i < $len; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    function link_to($text, $href, $options = array()) {
        // Adapted from
        // https://github.com/l3ck/php-helpers/blob/master/link_to.php

        return "<a href=\"$href\" " . $this->to_attr($options) . ">$text</a>";
    }

    function to_attr($attributes = array()) {
        // Adapted from
        // https://github.com/l3ck/php-helpers/blob/master/to_attr.php
        if (empty($attributes)) {
            return '';
        }
        $attr_return = array();

        foreach ($attributes as $key => $value) {
            array_push($attr_return, "$key=\"$value\"");
        }

        return implode(' ', $attr_return);
    }

    function starts_with($haystack, $needle) {
        // Adapted from
        // http://stackoverflow.com/a/834355/133764
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    function ends_with($haystack, $needle) {
        // Adapted from
        // http://stackoverflow.com/a/834355/133764
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
}

