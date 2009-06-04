require 'test_helper'

class ItemTest < ActiveSupport::TestCase

  test "should generate its own shortened urls" do
    item = Item.new
    item.original = "http://google.com"
    item.save
    assert_equal item.shortened.length, 6
  end

end
