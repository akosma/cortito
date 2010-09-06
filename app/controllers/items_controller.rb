# Copyright (c) 2009, Adrian Kosmaczewski / akosma software
# All rights reserved.
# BSD License. See LICENSE.txt for details.

class ItemsController < ApplicationController

  def redirect
    @item = Item.find_by_shortened(params[:shortened])
    if @item
      redirect_to @item.original
    else
      redirect_to :shorten
    end
  end

  def shorten
    @host = request.host_with_port
    @brand_name = APP_CONFIG["brand_name"]
    @brand_url = APP_CONFIG["brand_url"]

    if !params.has_key?(:url) && !params.has_key?(:reverse)
      render :template => "items/new"
      return
    end

    if params.has_key?(:reverse)
      reverse = params[:reverse]

      if reverse.length == 0
        render_error "items/invalid"
        return
      end

      @item = Item.find_by_shortened(reverse)
      if not @item
        render_error "items/not_found"
        return
      end
    else
      url = params[:url]
      short = nil
    
      if params.has_key?(:short)
        short = CGI::escape(params[:short])

        if short.length > Item::SHORT_URL_LENGTH
          if short.downcase != short
            # probably spam
            render_error "items/invalid"
            return
          end
        end
      end
    
      if url.length == 0
        render_error "items/invalid"
        return
      end

      if !(url.starts_with?("http://") || url.starts_with?("https://") || url.starts_with?("ftp://"))
        render_error "items/invalid"
        return
      end

      if is_already_shortened_url?(url)
        render_error "items/invalid"
        return
      end

      if url.length < ("http://".length + @host.length + 1 + Item::SHORT_URL_LENGTH)
        render_error "items/short"
        return
      end
      
      @item = Item.find_by_original(url)
      if not @item
        @item = Item.new
        @item.original = url
        @item.shortened = short
      end
      @item.save
    end

    respond_to do |format|
      format.html do
        @short_url = ["http://", @host, "/", @item.shortened].join
        
        @short_url_sanitized = CGI.escape(@short_url)
        @twitter_url = ["http://twitter.com/home?status=", @short_url_sanitized].join
        newline = "%0D%0A"
        @email_url = ["mailto:?subject=Check%20out%20this%20URL",
                      "&body=", @short_url_sanitized, newline, newline, 
                      "Shortened%20by%20cortito%20http://akos.ma/%20by%20akosma%20software%20http://akosma.com/", newline].join
        @echofon_url = ["echofon:", @short_url_sanitized].join
        @twitterrific_url = ["twitterrific:///post?message=", @short_url_sanitized].join
        @tweetie_url = ["tweetie:///post?message=", @short_url_sanitized].join
        @twittelator_url = ["twit:///post?message=", @short_url_sanitized].join
        @twinkle_url = ["twinkle://?message=", @short_url_sanitized].join
        render :template => "items/show"
      end
      format.xml { render_for_api }
      format.js { render_for_api }
    end

  end

private

  def render_error(error_template)
    url = ""
    if params.has_key?(:url)
      url = params[:url]
    end
    respond_to do |format|
      format.html { render :template => error_template }
      format.xml { render :text => url }
      format.js { render :text => url }
    end
  end

  def render_for_api
    if params.has_key?(:reverse)
      render :text => @item.original
    else
      render :text => ["http://", @host, "/", @item.shortened].join
    end
  end

  def is_already_shortened_url?(url)
    shortened_url_prefix = APP_CONFIG["excluded_url_shorteners"]
    
    shortened_url_prefix.each do |prefix|
      if url.starts_with?(prefix)
        return true
      end
    end
    return false
  end

end
