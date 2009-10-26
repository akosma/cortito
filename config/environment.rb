# Copyright (c) 2009, Adrian Kosmaczewski / akosma software
# All rights reserved.
# BSD License. See LICENSE.txt for details.

RAILS_GEM_VERSION = '2.3.2' unless defined? RAILS_GEM_VERSION

CORTITO_VERSION = '1.4.2'

require File.join(File.dirname(__FILE__), 'boot')

Rails::Initializer.run do |config|
  config.time_zone = 'UTC'
end