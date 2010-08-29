require 'test_helper'

class ItemsControllerTest < ActionController::TestCase

  test "should get form" do
    get :shorten
    assert_response :success
    assert_template :new
  end

  test "should shorten new url using POST" do
    assert_difference('Item.count') do
      post :shorten, :url => "http://www.google.com/search?q=ruby+on+rails"
    end
    assert_template :show
  end
  
  test "should shorten new url using GET" do
    assert_difference('Item.count') do
      get :shorten, :url => "http://www.google.com/search?q=matz"
    end
    assert_template :show
  end

  test "should accept a 'short' parameter with a proposed short url" do
    assert_difference('Item.count') do
      get :shorten, :url => "http://www.google.com/search?q=argentina", :short => 'argentina'
    end
    assert_template :show
  end

  test "should refuse short parameters longer than 6 characters included mixed upper and lowercase chars" do
    # this could be spam
    get :shorten, :url => "http://www.google.com/search?q=argentina", :short => 'aRgEnTiNaWhAtEvEr'
    assert_template :invalid
  end
  
  test "should propose a random url if the 'short' parameter already exists" do
    assert_difference('Item.count') do
      get :shorten, :url => "http://www.google.com/search?q=switzerland", :short => 'switzerland'
    end
    post :shorten, :url => "http://www.google.com/search?q=geneva", :short => 'switzerland'
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
  
  test "should ignore very short urls" do
    post :shorten, :url => "http://a"
    assert_template :short
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

  test "should accept JSON requests" do
    @request.env["HTTP_ACCEPT"] = "application/javascript"
    get :shorten, :url => "http://kosmaczewski.net/2008/08/11/saving-a-failing-project/"
    assert_response :success
    assert @response.body.ends_with?("blzrur")
  end

  test "should accept XML requests" do
    @request.env["HTTP_ACCEPT"] = "text/xml"
    get :shorten, :url => "http://kosmaczewski.net/2008/08/11/saving-a-failing-project/"
    assert_response :success
    assert @response.body.ends_with?("blzrur")
  end
  
  test "should refuse URLs not starting with http://, https:// or ftp://" do
    get :shorten, :url => "whatever text"
    assert_template :invalid

    get :shorten, :url => "javascript:alert('here')"
    assert_template :invalid

    get :shorten, :url => "mailto:some@address.here"
    assert_template :invalid

    get :shorten, :url => "ftp://ftp.tex.ac.uk/tex-archive/macros/latex/contrib/listings/listings.pdf"
    assert_template :show
    assert_response :success

    get :shorten, :url => "https://github.com/account"
    assert_template :show
    assert_response :success

    get :shorten, :url => "__ sdfasdfka sdf8a0s98df as09dff asdff"
    assert_template :invalid
  end
  
  test "should accept a 'reverse' parameter which returns the original URL" do
    get :shorten, :reverse => "blzrur"
    assert_response :success
  end
  
  test "should return the original URL with XML requests and the 'reverse' parameter" do
    @request.env["HTTP_ACCEPT"] = "text/xml"
    get :shorten, :reverse => "blzrur"
    assert_response :success
    assert_equal "http://kosmaczewski.net/2008/08/11/saving-a-failing-project/", @response.body
  end

  test "should return the original URL with JSON requests and the 'reverse' parameter" do
    @request.env["HTTP_ACCEPT"] = "application/javascript"
    get :shorten, :reverse => "blzrur"
    assert_response :success
    assert_equal "http://kosmaczewski.net/2008/08/11/saving-a-failing-project/", @response.body
  end

end
