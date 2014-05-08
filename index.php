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
$domain = $app->request->getHost();
$config = new Config;
$helper = new Helper;

$render = function ($subtemplate = "form.php", $code = 200) use ($app, $host, $config, $domain, $helper) {
    // Decide on the type of answer, depending on the request
    $req = $app->request->headers()->get('ACCEPT');
    if ($req == 'application/javascript' || $req == 'text/xml') {
        // API call
        http_response_code($code);
        echo($subtemplate);
        $app->stop();
    }
    else {
        // Normal browser
        $app->render('root.php',
            array(
                'host' => $host,
                'domain' => $domain,
                'brand_name' => $config->getBrandName(),
                'brand_url' => $config->getBrandUrl(),
                'cortito_version' => '2.1',
                'subtemplate' => $subtemplate,
                'helper' => $helper
            )
        );
    }
};

$render_with_url = function ($original, $shortened) use ($app, $host, $config, $domain, $helper) {
    $short_url = "$host/$shortened";
    $short_url_sanitized = urlencode($short_url);
    $newline = "%0D%0A";
    $app->render('root.php',
        array(
            'host' => $host,
            'domain' => $domain,
            'brand_name' => $config->getBrandName(),
            'brand_url' => $config->getBrandUrl(),
            'cortito_version' => '2.1',
            'subtemplate' => 'show.php',
            'helper' => $helper,
            'shortened' => $shortened,
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

$is_valid = function ($url) use ($config, $host, $helper) {
    $max_length = $config->getMaxShortLength();

    // Let's make sure the length of the input URL is bigger than zero
    if (strlen($url) == 0) {
        return false;
    }

    // Let's hope the user is not shortening URLs with wrong protocols
    if (!($helper->starts_with($url, "http://") || $helper->starts_with($url, "https://") || $helper->starts_with($url, "ftp://"))) {
        return false;
    }

    // Also, let's make sure that the end result will be shorter than the
    // original URL - otherwise, what's the point in shortening it!
    if (strlen($url) < strlen($host) + 1 + $max_length) {
        return false;
    }

    // Finally, make sure that the URL is not already shortened by another
    // URL shortener
    $exclusions = $config->getExcludedUrlShorteners();
    if (in_array($url, $exclusions)) {
        return false;
    }

    return true;
};

$redirect = function ($shortened) use ($app, $helper) {
    $row = $helper->find_by_shortened($shortened);
    if(isset($row)) {
        $id = $row["id"];
        $count = $row["count"];
        $helper->update_count($id, $count + 1);

        $original = $row["original"];
        $app->redirect($original);
    }
    else {
        $app->redirect('/');
    }
};

$shorten = function () use ($app, $render, $config, $render_with_url, $is_valid, $helper) {
    $url = $app->request->params('url');
    if (isset($url)) {
        $max_length = $config->getMaxShortLength();

        // Check whether we have a 'short' parameter, with a
        // suggestion by the user for a suitable shortcode
        $short = $app->request->params('short');
        if (isset($short)) {
            $short = urlencode(strtolower($short));
            if (strlen($short) > $max_length) {
                $render("invalid.php", 422);
                $app->stop();
            }
        }
        else {
            $short = $helper->generate_random_string($max_length);
        }

        // Let's make sure the shortcode is not used already
        $row = $helper->find_by_shortened($short);
        if (isset($row)) {
            // If the shortening code is already used, generate a new one until
            // we have a winner!
            while (isset($row)) {
                $short = $helper->generate_random_string($max_length);
                $row = $helper->find_by_shortened($short);
            }
        }

        // Let's make sure the length of the input URL is bigger than zero
        if ($is_valid($url)) {
            // Let's figure out if the URL has not been shortened already
            $row = $helper->find_by_original($url);
            if (isset($row)) {
                $short = $row["shortened"];
            }
            else {
                // If we arrive here, everything is OK; insert and display!
                $helper->insert_url($url, $short);
            }

            // Decide on the type of answer, depending on the request
            $req = $app->request->headers()->get('ACCEPT');
            if ($req == 'application/javascript' || $req == 'text/xml') {
                // API call
                echo($short);
                $app->stop();
            }
            else {
                // Normal browser
                $render_with_url($url, $short);
            }
        }
        else {
            $render("invalid.php", 422);
        }
    }
    else {
        // Simply render the home page
        $render();
    }
};

$reverse = function ($shortened) use ($render_with_url, $render, $app, $helper) {
    $row = $helper->find_by_shortened($shortened);
    if (isset($row)) {
        $original = $row["original"];

        // Decide on the type of answer, depending on the request
        $req = $app->request->headers()->get('ACCEPT');
        if ($req == 'application/javascript' || $req == 'text/xml') {
            // API call
            echo($original);
            $app->stop();
        }
        else {
            // Normal browser
            $render_with_url($original, $shortened);
        }
    }
    else {
        $render("not_found.php", 404);
    }
};

$app->get('/:shortened', $redirect);
$app->get('/reverse/:shortened', $reverse);
$app->map('/', $shorten)->via('GET', 'POST');

$app->run();

