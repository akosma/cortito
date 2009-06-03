class ItemsController < ApplicationController

  # This is for the form of the "new" action, when it submits to "shorten"
  skip_before_filter :verify_authenticity_token

  def redirect
    @item = Item.find_by_shortened(params[:shortened])
    redirect_to @item.original
  end

  def shorten
    if request.get?
      render :template => "items/new"
    else
      url = params[:url]
      if Item.exists?(:original => url)
        @item = Item.find_by_original(url)
      else
        @item = Item.new
        @item.original = params[:url]
        @item.shorten
        @item.save
      end
      
      respond_to do |format|
        format.html do
          @short_url = ["http://", request.server_name, "/", @item.shortened].join
          render :template => "items/show"
        end
        format.xml { render :text => ["http://url.akosma.com/", @item.shortened].join }
        format.js { render :text => ["http://url.akosma.com/", @item.shortened].join }
      end

    end
  end

end
