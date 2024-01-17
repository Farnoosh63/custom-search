<?php

namespace Drupal\custom_search\Controller;

use Drupal\Core\Utility\Error;
use \Psr\Log\LogLevel;
use Drupal\search_api_autocomplete\Controller\AutocompleteController;
use Drupal\search_api_autocomplete\SearchApiAutocompleteException;
use Drupal\search_api_autocomplete\SearchInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Serialization\Json;



/**
 * Override controller for autocompletion.
 */

class CustomSearchController extends AutocompleteController{

  /**
   * Override the parent class's autocomplete function.
   *
   * @param \Drupal\search_api_autocomplete\SearchInterface $search_api_autocomplete_search
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   * @throws \JsonException
   * @throws \Exception
   */
  public function autocomplete (SearchInterface $search_api_autocomplete_search, Request $request)
    {
      // Call the parent class's autocomplete function.
      $parent_return = parent::autocomplete($search_api_autocomplete_search, $request);
      // Set a limit for all_results suggester to 1.
      $search_api_autocomplete_search->getSuggesterLimits()['all_results'] = (int) 1;
      // Decode the JSON response from the parent function.
      $decodedSuggestions = json_decode($parent_return->getContent(), TRUE, 512, JSON_THROW_ON_ERROR);

      // Add new element
      $userInput = $request->query->get('q');
      // Initialize new element
      $newElement = [
        'value' => $userInput,
        'label' => '', // Will be replaced by rendered output
        'url' => $GLOBALS['base_url'] . '/search?site_search=' . urlencode($userInput) . '&sort_by=relevance',
        '#user_input' => $userInput,
      ];

      $newBuild = [
        '#theme' => 'custom_search_autocomplete_suggestion',
        '#user_input' => $userInput,
        '#label' => 'View all results',
        '#url' => $GLOBALS['base_url'] . '/search?site_search=' . urlencode($userInput),
      ];

      // Render the new element using custom theme and assign to label
      try {
        $renderedOutput = $this->renderer->renderPlain($newBuild);
        $newElement['label'] = $renderedOutput;
      } catch (\Exception $e) {
        $logger = \Drupal::logger('update');
        Error::logException($logger,$e, 'search_api_autocomplete', (array) '%type while rendering an autocomplete suggestion: @message in %function (line %line of %file).', LogLevel::CRITICAL);
      }
      // Add new element to suggestions
      $decodedSuggestions[] = $newElement;

      return new JsonResponse($decodedSuggestions);
  }

}
