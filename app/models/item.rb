class Item < ActiveRecord::Base
  
  def shorten
    # Code adapted from
    # http://travisonrails.com/2007/06/07/Generate-random-text-with-Ruby
    exists = true
    while exists do
      chars = 'abcdefghijklmnopqrstuvwxyz1234567890_'
      shortened = ''  
      6.times { |i| shortened << chars[rand(chars.length)] }
      exists = Item.exists?(:shortened => shortened)
    end
    self.shortened = shortened
  end
  
end
