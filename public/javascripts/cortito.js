var termsOfServiceLoaded = false;
var privacyLoaded = false;

$(document).ready(function() {
    $('div#otherlinks a').click(function(evt) {
        $('div#menu a').removeClass("selected");
        $(this).addClass("selected");
        $('div.documentation').hide();
        var divName = 'div#' + $(this).attr("id").replace("link", "");
        $(divName).fadeIn();
        
        if (!termsOfServiceLoaded && (divName == "div#termsofservice"))
        {
            $('div#termsofservicecontents').load('termsofservice.html', function() {
                $('<strong><%= @brand_name %></strong>').replaceAll("div#termsofservicecontents strong");
                $('<a href="<%= @brand_url %>"><strong><%= @brand_name %></strong></a>').replaceAll("div#termsofservicecontents a");
                termsOfServiceLoaded = true;
            });
        }
        
        if (!privacyLoaded && (divName == "div#privacy"))
        {
            $('div#privacycontents').load('privacy.html', function() {
                $('<strong><%= @brand_name %></strong>').replaceAll("div#privacycontents strong");
                privacyLoaded = true;
            });
        }
    });
});
