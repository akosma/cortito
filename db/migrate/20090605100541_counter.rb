class Counter < ActiveRecord::Migration
  def self.up
    add_column    :items, :count,     :integer, :default => 0,  :null => false
    change_column :items, :original,  :string,  :limit => 512, :null => false
    change_column :items, :shortened, :string,  :limit => 512, :null => false
  end

  def self.down
    remove_column :items, :count
  end
end
