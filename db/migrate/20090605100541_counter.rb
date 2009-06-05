class Counter < ActiveRecord::Migration
  def self.up
    add_column    :items, :count,     :integer, :default => 0,  :null => false
    change_column :items, :original,  :text,    :limit => 1024, :null => false
    change_column :items, :shortened, :text,    :limit => 1024, :null => false
  end

  def self.down
    remove_column :items, :count
  end
end
