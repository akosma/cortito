class Indexes < ActiveRecord::Migration
  def self.up
    add_index("items", "original", { :name => "original_index", :unique => true })
    add_index("items", "shortened", { :name => "shortened_index", :unique => true })
  end

  def self.down
    remove_index("items", "original_index")
    remove_index("items", "shortened_index")
  end
end
