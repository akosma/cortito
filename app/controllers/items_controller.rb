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
    else
      url = params[:url]
      short = nil
      
      if params.has_key?(:short)
        short = CGI::escape(params[:short])
      end
      
      if url.length == 0
        render :template => "items/invalid"
      else
        shortened_url_prefix = ["http://tinyurl.com/", "http://url.akosma.com/",
          "http://u.nu/", "http://snipurl.com/", "http://readthisurl.com/",
          "http://doiop.com/", "http://urltea.com/", "http://dwarfurl.com/", 
          "http://memurl.com/", "http://shorl.com/", "http://traceurl.com/", 
          "http://bit.ly/"]
        
        shortened_url_prefix.each do |prefix|
          if url.starts_with?(prefix)
            render :template => "items/invalid"
            return
          end
        end

        if url.length < ("http://".length + @host.length + 1 + Item::SHORT_URL_LENGTH)
          render :template => "items/short"
        else
        
          @item = Item.find_by_original(url)
          if not @item
            @item = Item.new
            @item.original = params[:url]
            @item.shortened = short
            @item.save
          end
      
          respond_to do |format|
            format.html do
              @short_url = ["http://", @host, "/", @item.shortened].join
              @twitter_url = ["http://twitter.com/home?status=", @short_url].join
              render :template => "items/show"
            end
            format.xml { render :text => ["http://", @host, "/", @item.shortened].join }
            format.js { render :text => ["http://", @host, "/", @item.shortened].join }
          end
        end
      end

    end
  end

end
