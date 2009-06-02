# Be sure to restart your server when you modify this file.

# Your secret key for verifying cookie session data integrity.
# If you change this key, all old sessions will become invalid!
# Make sure the secret is at least 30 characters and all random, 
# no regular words or you'll be exposed to dictionary attacks.
ActionController::Base.session = {
  :key         => '_cortito_session',
  :secret      => '6529b8c921154e81db3ea8f5a608820140fa73dce9e118b5df25d8491f1f370ef9b7bf5636c1d76b380c1515b36d8af5a49c282cd14b04d52d3758b32a68277d'
}

# Use the database for sessions instead of the cookie-based default,
# which shouldn't be used to store highly confidential information
# (create the session table with "rake db:sessions:create")
# ActionController::Base.session_store = :active_record_store
