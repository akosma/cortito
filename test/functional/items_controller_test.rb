require 'test_helper'

class ItemsControllerTest < ActionController::TestCase

  test "should get form" do
    get :shorten
    assert_response :success
    assert_template :new
  end

  test "should shorten new url" do
    assert_difference('Item.count') do
      post :shorten, :url => "http://test.com"
    end
    assert_template :show
  end
  
  test "should ignore empty url" do
    post :shorten, :url => ""
    assert_template :invalid
  end

  test "should ignore null url" do
    post :shorten
    assert_template :invalid
  end
  
  test "should warn about invalid shortened urls" do
    post :shorten, :url => "http://tinyurl.com/123456"
    assert_template :invalid
  end
  
  test "should redirect for valid shortened url" do
    get :redirect, :shortened => "123456"
    assert_redirected_to "http://123456.com"
  end

  test "should redirect to form for invalid shortened url" do
    get :redirect, :shortened => "invalid_non_existent"
    assert_redirected_to :shorten
  end

end
