# Copyright (c) 2009, Adrian Kosmaczewski / akosma software
# All rights reserved.
# BSD License. See LICENSE.txt for details.

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
