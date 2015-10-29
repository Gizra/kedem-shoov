'use strict';

var shoovWebdrivercss = require('shoov-webdrivercss');
var projectName = 'Kedem-shoov';

// This can be executed by passing the environment argument like this:
// PROVIDER_PREFIX=browserstack SELECTED_CAPS=chrome mocha
// PROVIDER_PREFIX=browserstack SELECTED_CAPS=ie11 mocha
// PROVIDER_PREFIX=browserstack SELECTED_CAPS=iphone5 mocha

var capsConfig = {
  'chrome': {
    project: projectName,
    'browser' : 'Chrome',
    'browser_version' : '42.0',
    'os' : 'OS X',
    'os_version' : 'Yosemite',
    'resolution' : '1024x768'
  },
  'ie11': {
    project: projectName,
    'browser' : 'IE',
    'browser_version' : '11.0',
    'os' : 'Windows',
    'os_version' : '7',
    'resolution' : '1024x768'
  },
  'iphone5': {
    'browser' : 'Chrome',
    'browser_version' : '42.0',
    'os' : 'OS X',
    'os_version' : 'Yosemite',
    'chromeOptions': {
      'mobileEmulation': {
        'deviceName': 'Apple iPhone 5'
      }
    }
  }
};

var selectedCaps = process.env.SELECTED_CAPS || undefined;
var caps = selectedCaps ? capsConfig[selectedCaps] : undefined;

var providerPrefix = process.env.PROVIDER_PREFIX ? process.env.PROVIDER_PREFIX + '-' : '';
var testName = selectedCaps ? providerPrefix + selectedCaps : providerPrefix + 'default';

var baseUrl = process.env.BASE_URL ? process.env.BASE_URL : 'http://www.kedem-auctions.com';

describe('Visual monitor testing', function() {

  this.timeout(99999999);
  var client = {};

  before(function(done){
    client = shoovWebdrivercss.before(done, caps);
  });

  after(function(done) {
    shoovWebdrivercss.after(done);
  });

  it('should show the home page',function(done) {
    client
      .url(baseUrl)
      .pause(3000)
      .webdrivercss(testName + '.homepage', {
        name: '1',
        exclude:
          [
            // Top carousel.
            '#block-md-slider-1',
          ],
        hide:
          [
            // Auction title.
            '.views-field-title-field',
            // Auction date.
            '.date-display-single',
            // Button text
            '.views-field-php a',
            // Footer
            '.region-footer-left',
            '.region-footer-right',
          ],
        screenWidth: selectedCaps == 'chrome' ? [640, 1200] : undefined
      }, shoovWebdrivercss.processResults)
      .call(done);
  });

    it('should show the auction no 46 page',function(done) {
      client
        .url(baseUrl + '/node/19640')
        .webdrivercss(testName + '.auction46', {
          name: '1',
          exclude:
            [
              // Sale count down.
              '.pane-circuit-sale-circuitcountdown',
            ],
          screenWidth: selectedCaps == 'chrome' ? [640, 1200] : undefined
        }, shoovWebdrivercss.processResults)
        .call(done);
    });
});
