# Copyright (c) 2009, Adrian Kosmaczewski / akosma software
# All rights reserved.
# BSD License. See LICENSE.txt for details.

class Item < ActiveRecord::Base
  
  SHORT_URL_LENGTH = 6
  
  def save
    if self.new_record?
      if shortened_url_exists?
        shorten_url
      end
    end
    self.count += 1
    super
  end

private
  
  def shortened_url_exists?
    self.shortened.nil? || self.shortened.length == 0 || Item.exists?(:shortened => shortened)
  end

end
