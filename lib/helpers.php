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

function find_by_shortened($shortened) {
    $config = new Config;
    $server = $config->getServer();
    $database = $config->getDatabase();
    $username = $config->getUsername();
    $password = $config->getPassword();

    $conn = mysqli_connect($server, $username, $password, $database)
        or trigger_error(mysqli_error(), E_USER_ERROR);

    $query = sprintf("SELECT * FROM items WHERE shortened = '%s'",
        mysqli_real_escape_string($conn, $shortened));
    $rs = execute($query, $server, $database, $username, $password);
    $row = $rs->next();
    return $row;
}

function find_by_original($original) {
    $config = new Config;
    $server = $config->getServer();
    $database = $config->getDatabase();
    $username = $config->getUsername();
    $password = $config->getPassword();

    $conn = mysqli_connect($server, $username, $password, $database)
        or trigger_error(mysqli_error(), E_USER_ERROR);

    $query = sprintf("SELECT * FROM items WHERE original = '%s'",
        mysqli_real_escape_string($conn, $original));
    $rs = execute($query, $server, $database, $username, $password);
    $row = $rs->next();
    return $row;
}

function generate_random_string($length = 6) {
    // Adapted from
    // http://stackoverflow.com/a/4356295/133764
    $characters = 'abcdefghijklmnopqrstuvwxyz1234567890_';
    $randomString = '';
    $len = mt_rand(1, $length);
    for ($i = 0; $i < $len; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function insert_url($original, $shortened) {
    $config = new Config;
    $server = $config->getServer();
    $database = $config->getDatabase();
    $username = $config->getUsername();
    $password = $config->getPassword();

    $conn = mysqli_connect($server, $username, $password, $database)
        or trigger_error(mysqli_error(), E_USER_ERROR);

    $query = sprintf("INSERT INTO items (original, shortened) VALUES ('%s', '%s')",
        mysqli_real_escape_string($conn, $original),
        mysqli_real_escape_string($conn, $shortened));
    $results = mysqli_query($conn, $query) or die(mysql_error());
}

function update_count($id, $count) {
    $config = new Config;
    $server = $config->getServer();
    $database = $config->getDatabase();
    $username = $config->getUsername();
    $password = $config->getPassword();

    $conn = mysqli_connect($server, $username, $password, $database)
        or trigger_error(mysqli_error(), E_USER_ERROR);

    $query = "UPDATE items SET count = $count WHERE id = $id";
    $results = mysqli_query($conn, $query) or die(mysql_error());
}

function is_already_shortened($original) {
    $config = new Config;
    $server = $config->getServer();
    $database = $config->getDatabase();
    $username = $config->getUsername();
    $password = $config->getPassword();

    $conn = mysqli_connect($server, $username, $password, $database)
        or trigger_error(mysqli_error(), E_USER_ERROR);
}

function link_to($text, $href, $options = array()) {
    // Adapted from
    // https://github.com/l3ck/php-helpers/blob/master/link_to.php

    return "<a href=\"$href\" " . to_attr($options) . ">$text</a>";
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

function is_shortener($url, $exclusions) {
    foreach ($exclusions as $exclusion) {
        if (starts_with($url, $exclusion)) {
            return true;
        }
    }
    return false;
}

