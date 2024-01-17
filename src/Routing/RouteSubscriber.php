<?php

namespace Drupal\custom_search\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class RouteSubscriber
 *
 * @package Drupal\custom_search\Routing
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('search_api_autocomplete.autocomplete')) {
      $route->setDefault('_controller', '\Drupal\custom_search\Controller\CustomSearchController::autocomplete');
    }

  }

}
