require 'test_helper'

class ItemTest < ActiveSupport::TestCase

  test "should generate its own shortened urls" do
    item = Item.new
    item.original = "http://google.com"
    item.save
    assert_equal item.shortened.length, Item::SHORT_URL_LENGTH
  end
  
  test "should increment count value" do
    item = Item.new
    item.original = "http://argentina.com"
    item.save
    assert_equal item.count, 1
    item.save
    assert_equal item.count, 2
  end

end
