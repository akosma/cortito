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

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
error_reporting(E_ALL|E_STRICT);

require 'lib/Slim/Slim.php';
require 'lib/helpers.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim(array(
    'templates.path' => './templates'
));

// Global objects
$host = $app->request->getUrl();
$config = new Config;

$render = function ($subtemplate = "form.php") use ($app, $host, $config) {
    $app->render('root.php',
        array(
            'host' => $host,
            'brand_name' => $config->getBrandName(),
            'brand_url' => $config->getBrandUrl(),
            'cortito_version' => '2.0',
            'subtemplate' => $subtemplate
        )
    );
};

$show_url = function ($original, $shortened) use ($app, $host, $config) {
    $short_url = "$host/$shortened";
    $short_url_sanitized = urlencode($short_url);
    $newline = "%0D%0A";
    $app->render('root.php',
        array(
            'host' => $host,
            'brand_name' => $config->getBrandName(),
            'brand_url' => $config->getBrandUrl(),
            'cortito_version' => '2.0',
            'subtemplate' => 'show.php',
            'short_url' => $short_url,
            'original' => $original,
            'twitter_web_url' => "http://twitter.com/home?status=$short_url_sanitized",
            'email_url' => "mailto:?subject=Check%20out%20this%20URL" .
            "&body=$short_url_sanitized$newline$newline" .
            "Shortened%20by%20cortito%20$host/$newline",
            'echofon_url' => "echofon:$short_url_sanitized",
            'twitterrific_url' => "twitterrific:///post?message=$short_url_sanitized",
            'twitter_app_url' => "twitter://post?message=$short_url_sanitized",
            'twittelator_url' => "twit:///post?message=$short_url_sanitized",
            'tweetbot_url' => "tweetbot:///post?text=$short_url_sanitized"
        )
    );
};

$root = function () use ($render) {
};

$redirect = function ($shortened) use ($app) {
    $row = find_by_shortened($shortened);
    if(isset($row)) {
        $id = $row["id"];
        $count = $row["count"];
        update_count($id, $count + 1);

        $original = $row["original"];
        $app->redirect($original);
    }
    else {
        $app->redirect('/');
    }
};

$shorten = function () use ($app, $render, $config, $host, $show_url) {
    $url = $app->request->params('url');
    if (isset($url)) {
        $max_length = $config->getMaxShortLength();
        $short = $app->request->params('short');
        if (isset($short)) {
            $short = urlencode(strtolower($short));
            if (strlen($short) > $max_length) {
                $render("invalid.php");
                $app->stop();
            }
        }
        else {
            $short = generate_random_string($max_length);
        }
        while ($row = find_by_shortened($short));
        {
            // If the shortening code is already used, generate a new one until
            // we have a winner!
            $short = generate_random_string($max_length);
        }
        if (strlen($url) == 0) {
            $render("invalid.php");
            $app->stop();
        }
        if (!(starts_with($url, "http://") || starts_with($url, "https://") || starts_with($url, "ftp://"))) {
            $render("invalid.php");
            $app->stop();
        }
        $row = find_by_original($url);
        if (isset($row)) {
            $render("invalid.php");
            $app->stop();
        }
        if (strlen($url) < strlen("$host") + 1 + $max_length) {
            $render("short.php");
            $app->stop();
        }
        insert_url($url, $short);
        $show_url($url, $short);
    }
    else {
        // Simply render the home page
        $render();
    }
};

$reverse = function ($shortened) use ($show_url, $render, $host) {
    $row = find_by_shortened($shortened);
    if (isset($row)) {
        $original = $row["original"];
        $show_url($original, $shortened);
    }
    else {
        $render("not_found.php");
    }
};

$app->get('/:shortened', $redirect);
$app->get('/reverse/:shortened', $reverse);
$app->map('/', $shorten)->via('GET', 'POST');

$app->run();

