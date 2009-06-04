# Copyright (c) 2009, Adrian Kosmaczewski / akosma software
# All rights reserved.
# BSD License. See LICENSE.txt for details.

ActionController::Routing::Routes.draw do |map|
  map.redirect ':shortened', :controller => 'items', :action => 'redirect', :conditions => {:method => :get}
  map.shorten  '',           :controller => 'items', :action => 'shorten'
end
