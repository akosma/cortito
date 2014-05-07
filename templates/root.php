<?php
$short_url = $this->data["short_url"];
$host = $this->data["host"];
$domain = $this->data["domain"];
$shortened = $this->data["shortened"];
$brand_name = $this->data["brand_name"];
$brand_url = $this->data["brand_url"];
$cortito_version = $this->data['cortito_version'];
$subtemplate = $this->data["subtemplate"];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?= $domain ?> - URL shortener by <?= $brand_name ?></title>
<link href="/public/stylesheets/cortito.css" media="screen" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="container">

<div id="poweredby">URL shortener powered by cortito <?= $cortito_version ?></div>

            <div class="thickline"></div>
            <div id="menu">
<div id="githublink"><a href="http://github.com/akosma/cortito">Cortito on Github</a></div>
<div id="otherlinks">
    <ul>
        <li><a id="bookmarkletlink" class="selected" href="#">Bookmarklet</a></li>
        <li><a id="apilink" href="#">API</a></li>
        <li><a id="termsofservicelink" href="#">Terms of Service</a></li>
        <li><a id="privacylink" href="#">Privacy</a></li>
        <li><a id="creditslink" href="#">Credits</a></li>
    </ul>
</div>
            </div>
            <div class="thinline"></div>

            <div id="branding">
<a href="/">cort<span id="ihighlighted">i</span>to <img src="/public/images/cortito_logo.png" width="74" height="74" alt="cortito logo" /></a><span id="hostname"><?php
if (isset($shortened))
{
    echo ("$domain/$shortened");
}
else
{
    echo ($domain);
}
?></span>
        </div>
        <div class="thickline"></div>

        <div id="main">
            <?php include($subtemplate); ?>
        </div>

        <div class="thickline"></div>

        <!-- Bookmarklet -->

        <div class="instructionsection documentation" id="bookmarklet"><p class="title">Bookmarklet</p>
            <p class="contents">For your browser: <?= link_to("shorten with $domain", "javascript:location.href='$host/?url='+encodeURIComponent(location.href);") ?></p>
            <p class="contents">(drag and drop on your bookmark toolbar)</p>
        </div>

        <!-- API -->

        <div class="instructionsection documentation" id="api">
            <p class="title">API</p>

            <p class="contents">Send a POST or GET request to <span class="sample"><?= $host ?></span> with a "url" parameter, with either an <span class="sample">Accept: application/javascript</span> or <span class="sample">Accept: text/xml</span> header. You'll get a simple text response with the shortened URL:</p>

            <div class="sample">$ curl --request GET --header "Accept: application/javascript" <?= $host ?>/\?url=http://shop.oreilly.com/product/0636920026877.do<br />
<?= $host ?>/st2ur<br />
$ _</div>

            <p class="contents">You can also use the "reverse" parameter with the shortened key (not the whole URL, just the key) and you'll get the original URL in return:</p>

            <div class="sample">$ curl --request GET --header "Accept: text/xml" <?= $host ?>/reverse/st2ur<br />
http://shop.oreilly.com/product/0636920026877.do<br />
$ _</div>
        </div>

        <!-- Terms of Service -->

        <div class="instructionsection documentation" id="termsofservice">
            <div id="termsofservicecontents"></div>
        </div>

        <!-- Privacy -->

        <div class="instructionsection documentation" id="privacy">
            <div id="privacycontents"></div>
        </div>

        <!-- Credits -->

        <div class="instructionsection documentation" id="credits">
            <p class="title">Credits</p>
            <p class="contents">Concept and programming by <a href="http://akosma.com/">akosma software</a>.</p>
            <p class="contents">Visual design by <a href="http://zerofee.org/">Zerofee</a>.</p>
            <p class="contents"><a href="http://validator.w3.org/check?charset=utf-8&amp;doctype=HTML5&amp;group=0&amp;uri=http%3A%2F%2F<?= @host ?>">HTML5</a> and <a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2F<?= @host ?>%2Fstylesheets%2Fscaffold.css&amp;profile=css3&amp;usermedium=all&amp;warning=1&amp;lang=en">CSS3</a> validated.</p>
        </div>

        <div class="thickline"></div>

    </div>

    <script type="text/javascript" src="/public/javascripts/jquery.js"></script>
    <script type="text/javascript">
        <!--
        var termsOfServiceLoaded = false;
        var privacyLoaded = false;

        $(document).ready(function() {
                $('div#otherlinks a').click(function(evt) {
                    $('div#menu a').removeClass("selected");
                    $(this).addClass("selected");
                    $('div.documentation').hide();
                    var divName = 'div#' + $(this).attr("id").replace("link", "");

                    if (!termsOfServiceLoaded && (divName == "div#termsofservice")) {
                    $('div#termsofservicecontents').load('/public/termsofservice.html', function() {
                        $('<strong><?= $brand_name ?></strong>').replaceAll("div#termsofservicecontents strong");
                        $('<a href="<?= $brand_url ?>"><strong><?= $brand_name ?></strong></a>').replaceAll("div#termsofservicecontents a");
                        termsOfServiceLoaded = true;
                        $(divName).fadeIn();
                        });
                    }
                    else if (!privacyLoaded && (divName == "div#privacy")) {
                    $('div#privacycontents').load('/public/privacy.html', function() {
                        $('<strong><?= $brand_name ?></strong>').replaceAll("div#privacycontents strong");
                        privacyLoaded = true;
                        $(divName).fadeIn();
                        });
                    }
                    else {
                        $(divName).fadeIn();
                    }
                });
        });
-->
    </script>

</body>
</html>

