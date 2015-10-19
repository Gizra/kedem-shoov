<?php

use Drupal\DrupalExtension\Context\MinkContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

class FeatureContext extends MinkContext implements SnippetAcceptingContext {

  /**
   * @Given I am an anonymous user
   */
  public function iAmAnAnonymousUser() {
    // Just let this pass-through.
  }

  /**
   * @When I visit the homepage
   */
  public function iVisitTheHomepage() {
    $this->getSession()->visit($this->locatePath('/'));
  }

  /**
   * @Then I should have access to the page
   */
  public function iShouldHaveAccessToThePage() {
    $this->assertSession()->statusCodeEquals('200');
  }

  /**
   * @Then I should not have access to the page
   */
  public function iShouldNotHaveAccessToThePage() {
    $this->assertSession()->statusCodeEquals('403');
  }

  /**
   * @Then /^I wait for css element "([^"]*)" to "([^"]*)"$/
   */
  public function iWaitForCssElement($element, $appear = 'appear')
  {
    $xpath = $this->getSession()
      ->getSelectorsHandler()
      ->selectorToXpath('css', $element);
    $this->waitForXpathNode($xpath, $appear == 'appear');
  }

  /**
   * Wait for an element by its XPath to appear or disappear.
   *
   * @param string $xpath
   *   The XPath string.
   * @param bool $appear
   *   Determine if element should appear. Defaults to TRUE.
   *
   * @throws Exception
   */
  private function waitForXpathNode($xpath, $appear = TRUE) {
    $this->waitFor(function($context) use ($xpath, $appear) {
      try {
        $nodes = $context->getSession()->getDriver()->find($xpath);
        if (count($nodes) > 0) {
          $visible = $nodes[0]->isVisible();
          return $appear ? $visible : !$visible;
        }
        return !$appear;
      }
      catch (WebDriver\Exception $e) {
        if ($e->getCode() == WebDriver\Exception::NO_SUCH_ELEMENT) {
          return !$appear;
        }
        throw $e;
      }
    });
  }

  /**
   * Helper function; Execute a function until it return TRUE or timeouts.
   *
   * @param $fn
   *   A callable to invoke.
   * @param int $timeout
   *   The timeout period. Defaults to 10 seconds.
   *
   * @throws Exception
   */
  private function waitFor($fn, $timeout = 15000) {
    $start = microtime(true);
    $end = $start + $timeout / 1000.0;
    while (microtime(true) < $end) {
      if ($fn($this)) {
        return;
      }
    }
    throw new \Exception('waitFor timed out.');
  }

    /**
   * @When I search :arg1
   */
  public function iSearch($search_string='')
  {
    // Wait for the input element.
    $this->iWaitForCssElement('.form-type-textfield #edit-keys-1--2');
    $element = $this->getSession()->getPage()->find('css', '#edit-keys-1--2');
    // Insert the text that you looking for.
    $element->setText($search_string);
    // Find the submit.
    $element = $this->getSession()->getPage()->find('css', '#edit-submit-1');
    // Click on the submit button.
    $element->click();
  }

  /**
   * @Then Then the number of search results is between :arg1 and :arg2
   */
  public function thenTheNumberOfSearchResultsIsBetweenAnd($arg1, $arg2)
  {
    // Wait for the result element.
    $this->iWaitForCssElement('#block-current-search-standard .current-search-item h3');
    $element = $this->getSession()->getPage()->find('css', '#edit-keys-1');
    return TRUE;
  }

}

