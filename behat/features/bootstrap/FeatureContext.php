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
   * @When I search :search_string
   */
  public function iSearch($search_string='')
  {
    // Find search form.
    $element = $this->getSession()->getPage()->find('css', '#edit-keys-1');
    // Insert the text that you looking for.
    $element->setValue($search_string);
    // Find the submit.
    $submit = $this->getSession()->getPage()->find('css', '#search-api-page-search-form-search-page .form-submit');
    $submit->click();
  }

  /**
   * @Then the number of search results is between :min and :max
   */
  public function theNumberOfSearchResultsIsBetweenAnd($min, $max)
  {
    $element = $this->getSession()->getPage()->find('css', '#block-current-search-standard .current-search-item h3');
    $result = $element->getText();
    preg_match('!\d+!', $result, $number);

    if ($number[0]<$min || $number[0]>$max) {
      throw new \Exception('The results are not in certain values.');
    };
  }
}

