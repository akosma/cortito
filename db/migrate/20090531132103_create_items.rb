class CreateItems < ActiveRecord::Migration
  def self.up
    create_table :items do |t|
      t.string :original
      t.string :shortened

      t.timestamps
    end
  end

  def self.down
    drop_table :items
  end
end
