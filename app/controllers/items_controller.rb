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

    if !params.has_key?(:url)
      render :template => "items/new"
      return
    end

    url = params[:url]
    short = nil
    
    if params.has_key?(:short)
      short = CGI::escape(params[:short])
    end
    
    if url.length == 0
      render :template => "items/invalid"
      return
    end

    if is_already_shortened_url?(url)
      render :template => "items/invalid"
      return
    end

    if url.length < ("http://".length + @host.length + 1 + Item::SHORT_URL_LENGTH)
      render :template => "items/short"
      return
    end
  
    @item = Item.find_by_original(url)
    if not @item
      @item = Item.new
      @item.original = params[:url]
      @item.shortened = short
    end

    @item.save

    respond_to do |format|
      format.html do
        @short_url = ["http://", @host, "/", @item.shortened].join
        @twitter_url = ["http://twitter.com/home?status=", @short_url].join
        newline = "%0D%0A"
        @email_url = ["mailto:?subject=Check out this URL shortened by cortito",
                      "&body=Check out this URL: ", @short_url, newline, 
                      "Originally: ", @item.original, newline, newline, 
                      "Shortened by cortito http://url.akosma.com/", newline, 
                      "by akosma software http://akosma.com/", newline].join
        render :template => "items/show"
      end
      format.xml { render :text => ["http://", @host, "/", @item.shortened].join }
      format.js { render :text => ["http://", @host, "/", @item.shortened].join }
    end

  end

private

  def is_already_shortened_url?(url)
    shortened_url_prefix = ["http://tinyurl.com/", "http://url.akosma.com/",
      "http://u.nu/", "http://snipurl.com/", "http://readthisurl.com/",
      "http://doiop.com/", "http://urltea.com/", "http://dwarfurl.com/", 
      "http://memurl.com/", "http://shorl.com/", "http://traceurl.com/", 
      "http://bit.ly/"]
    
    shortened_url_prefix.each do |prefix|
      if url.starts_with?(prefix)
        return true
      end
    end
    return false
  end

end
