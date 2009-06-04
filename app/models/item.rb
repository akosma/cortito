# Copyright (c) 2009, Adrian Kosmaczewski / akosma software
# All rights reserved.
# BSD License. See LICENSE.txt for details.

class Item < ActiveRecord::Base
  
  SHORT_URL_LENGTH = 6
  
  def save
    # Code adapted from
    # http://travisonrails.com/2007/06/07/Generate-random-text-with-Ruby
    exists = true
    while exists do
      chars = 'abcdefghijklmnopqrstuvwxyz1234567890_'
      shortened = ''  
      SHORT_URL_LENGTH.times { |i| shortened << chars[rand(chars.length)] }
      exists = Item.exists?(:shortened => shortened)
    end
    self.shortened = shortened
    super
  end
  
end
