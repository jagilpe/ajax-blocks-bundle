/*
 * This file is part of AjaxBlocksBundle package.
 *
 * (c) Copyright Javier Gil Pereda <javier@gilpereda.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

describe('Ajax Block jQuery Plugin', function() {

  var ajaxParams;

  var mockAjaxCall = function(data) {
    spyOn($, 'ajax').and.callFake(function(params) {
      // Store the passed parameters for later check in the tests
      ajaxParams = params;

      params = params || {};
      params.success = params.success || function() {};

      var deferred = $.Deferred();

      deferred.resolve(data);
      ajaxParams.success(data);

      return  deferred.promise();
    });
  };

  beforeEach(function() {

  });

  it('should call the right url and method', function() {

    // Mock the Ajax call
    mockAjaxCall({ block: '' });

    var dataUrl = 'testing-url?test1=test1&test2=test2';
    var block = $('<div data-src="' + dataUrl + '">');
    block.append('<div>initial content</div>');

    // Initialize the plugin
    $(block).jgpAjaxBlock();

    // Reload the page
    $(block).jgpAjaxBlock('reloadBlock');

    expect(ajaxParams.url).toBe(dataUrl);
    expect(ajaxParams.method.toUpperCase()).toBe('GET');

  });

  it('should replace the content with the response', function () {

    var oldBlock = '<div>old content</div>';
    var newBlock = '<div>new content</div>';

    mockAjaxCall({ block: newBlock });

    var block = $('<div data-src="testing-url?test1=test1&test2=test2">');
    block.append(oldBlock);

    $(block).jgpAjaxBlock();

    expect(block.html()).toBe(oldBlock);

    $(block).jgpAjaxBlock('reloadBlock');

    expect(block.html()).toBe(newBlock);

  });

  it('should keep the original block if the received data is not right', function() {

    var oldBlock = '<div>old content</div>';

    mockAjaxCall();

    var block = $('<div data-src="testing-url?test1=test1&test2=test2">');
    block.append(oldBlock);

    $(block).jgpAjaxBlock();

    expect(block.html()).toBe(oldBlock);

    $(block).jgpAjaxBlock('reloadBlock');

    expect(block.html()).toBe(oldBlock);
  });

  it('should keep the original block if an error is received', function() {

    var oldBlock = '<div>old content</div>';

    spyOn($, 'ajax').and.callFake(function(params) {
      params = params || {};
      params.error = params.error || function(jqXHR, textStatus, errorThrown) {};
      var deferred = $.Deferred();

      params.error(undefined, 'error', 'error');
      deferred.reject(undefined, 'error', 'error');

      return deferred.promise();
    });

    var block = $('<div data-src="testing-url?test1=test1&test2=test2">');
    block.append(oldBlock);

    $(block).jgpAjaxBlock();

    expect(block.html()).toBe(oldBlock);

    $(block).jgpAjaxBlock('reloadBlock');

    expect(block.html()).toBe(oldBlock);
  });

  it('should autoload the block', function() {
    var oldBlock = '<div>old content</div>';
    var newBlock = '<div>new content</div>';

    mockAjaxCall({ block: newBlock });

    var block = $('<div data-src="testing-url?test1=test1&test2=test2">');
    block.append(oldBlock);

    $(block).jgpAjaxBlock({
      autoload: true
    });

    expect(block.html()).toBe(newBlock);

  });

});