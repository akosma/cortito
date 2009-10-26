require 'test_helper'

class ItemTest < ActiveSupport::TestCase

  test "should generate its own shortened urls" do
    # The list of URLs comes from
    # http://www.fws.gov/wetlands/_documents/urllist.txt
    f = File.open("test/fixtures/urls.txt") or die "Unable to open file..."
    f.each_line do |url| 
      item = Item.new
      item.original = url
      item.save
      assert item.shortened.length <= Item::SHORT_URL_LENGTH
      assert item.shortened.length > 0
    end
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
