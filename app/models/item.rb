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
    super
  end

private
  
  def shortened_url_exists?
    self.shortened.nil? || Item.exists?(:shortened => shortened)
  end

  def shorten_url
    # Code adapted from
    # http://travisonrails.com/2007/06/07/Generate-random-text-with-Ruby
    chars = 'abcdefghijklmnopqrstuvwxyz1234567890_'
    while shortened_url_exists? do
      result = ''  
      SHORT_URL_LENGTH.times { |i| result << chars[rand(chars.length)] }
      self.shortened = result
    end
  end
  
end
