# Custom Search Module

## Overview

The Custom Search module is a Drupal extension designed to enhance search functionalities, with a specific focus on customizing autocomplete behavior. It leverages Drupal's Event Subscriber, Dependency Injection, Plugin Development, and Routing systems.

## Features

- **Enhanced Autocomplete**: Overrides and extends the default search autocomplete functionality.
- **Customized Search Suggestions**: Implements logic for search suggestions like URL handling and limiting suggestions. 
- **Routing Customization**: Utilizes an Event Subscriber to modify existing routes.
- **Modern Frontend Technologies**:
    - **Employs Tailwind CSS and Custom Web Components**: The module utilizes Tailwind CSS for styling, providing a responsive and visually appealing user interface. Additionally, it incorporates custom web components, which offer several key advantages:
        - **Accessibility**: Custom web components are designed with accessibility in mind.
        - **Consistency**: Using web components helps maintain a consistent look and feel across the site, as these components can be reused in different parts of the application without duplicating code.
        - **Maintenance**: Web components are modular and encapsulated, making them easier to maintain and update. Changes made to a component are reflected wherever it's used, simplifying the development process.
        - **SEO**: These custom web components contribute to SEO-friendly markup, aiding in better search engine recognition and indexing.
        - **Lightweight and Reusable**: These components are lightweight and reusable.


## Components

### 1. CustomSearchController

- **Path**: `custom_search/src/Controller/CustomSearchController.php`
- **Description**: Overrides the default autocomplete controller of the Search API. Modifies the suggestions limit and formats the response to include a custom link to view all results.
- **Key Concepts**: Extending Core Controllers, Dependency Injection.

### 2. RouteSubscriber (Event Subscriber)

- **Path**: `custom_search/src/Routing/RouteSubscriber.php`
- **Description**: An integral part of the module's routing system. `RouteSubscriber` extends `RouteSubscriberBase` and is used as an Event Subscriber to respond to routing events. 
- **Functionality**:
    - Overrides the `alterRoutes` method to modify the routing table during rebuilds.
    - Changes the controller for the route `search_api_autocomplete.autocomplete` to the custom `CustomSearchController::autocomplete`.
- **Key Concepts**: Event Subscriber, Routing Customization.
- **Event Subscription**: Service tagged as an event subscriber in `custom_search.services.yml`, making sure it's subscribed to routing events.

### 3. Module Information

- **Path**: `custom_search/custom_search.info.yml`
- **Description**: Defines the module's metadata, dependencies, and Drupal core compatibility.

### 4. Module File

- **Path**: `custom_search/custom_search.module`
- **Description**: Contains Drupal hooks, including `hook_theme()` for defining a theme template for autocomplete suggestions.
- **Key Concepts**: Drupal Hooks, Theme System.

### 5. Services

- **Path**: `custom_search/custom_search.services.yml`
- **Description**: Declares the `RouteSubscriber` service, tagged as an event subscriber for handling routing events.

### 6. Autocomplete Suggestion Template

- **Path**: `custom_search/templates/custom-search-autocomplete-suggestion.html.twig`
- **Description**: Twig template for rendering autocomplete suggestions. Uses Tailwind CSS for styling and a custom web component for the suggestion button.

## Plugin Processor: Authors

- **Path**: `custom_search/src/Plugin/search_api/processor/Authors.php`
- **Description**: The `Authors` processor improve the search index by adding author names from the `field_authors` field in content entities.
- **Key Features**:
    - **Automated Indexing**: Automatically extracts and indexes author names.
    - **Flexible and Adaptable**: With `locked = true` and `hidden = true` settings, this processor is universally applied across all data sources without additional setup. This makes it flexible enough to handle various content types and scalable as new data sources are added.
    - **Data Processing**: Processes and formats author names using Drupal's `name.formatter` service, for consistency and accuracy in the indexed data.

