ActionController::Routing::Routes.draw do |map|
  # The priority is based upon order of creation: first created -> highest priority.
  map.redirect ':shortened', :controller => 'items', :action => 'redirect', :conditions => {:method => :get}
  map.shorten  '',           :controller => 'items', :action => 'shorten'
end
