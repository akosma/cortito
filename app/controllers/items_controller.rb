class ItemsController < ApplicationController

  # This is for the form of the "new" action, when it submits to "shorten"
  skip_before_filter :verify_authenticity_token

  def redirect
    @item = Item.find_by_shortened(params[:shortened])
    if @item
      redirect_to @item.original
    else
      redirect_to :shorten
    end
  end

  def shorten
    if request.get?
      render :template => "items/new"
    else
      url = params[:url]
      
      if !params.has_key?(:url) || url.length == 0
        redirect_to :shorten
      else
        @item = Item.find_by_original(url)
        if not @item
          @item = Item.new
          @item.original = params[:url]
          @item.save
        end
      
        host = request.host_with_port
      
        respond_to do |format|
          format.html do
            @short_url = ["http://", host, "/", @item.shortened].join
            render :template => "items/show"
          end
          format.xml { render :text => ["http://", host, "/", @item.shortened].join }
          format.js { render :text => ["http://", host, "/", @item.shortened].join }
        end
      
      end

    end
  end

end
