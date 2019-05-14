# Server content

This directory contains the content of the Skin Deep server, and is based on the [Bedrock](https://roots.io/bedrock/) server framework.

Bedrock is a modern WordPress stack that helps you get started with the best development tools and project structure.

## Features

* Better folder structure
* Dependency management with [Composer](https://getcomposer.org)
* Easy WordPress configuration with environment specific files
* Environment variables with [Dotenv](https://github.com/vlucas/phpdotenv)
* Autoloader for mu-plugins (use regular plugins as mu-plugins)
* Enhanced security (separated web root and secure passwords with [wp-password-bcrypt](https://github.com/roots/wp-password-bcrypt))

## Requirements

* PHP >= 7.1
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

## Installation

The [trellis](../trellis) directory handles installation of all dependencies involved in deployment.

However, as stated in the [Trellis docs](https://roots.io/trellis/docs/local-development-setup/), some installation is required for the local vagrant development server:

> Composer and WP-CLI commands need to be run on the virtual machine for any post-provision modifications. Front-end build tools should be run from your host machine and not the Vagrant VM.

### Server root

Directory: `.`

The server root manages server dependencies, such as:
- Wordpress
- 3rd-party plugins

These are managed using composer and are installed with:
```
composer install
```

### Models

Directory: [./web/app/models](./web/app/models)

Custom models, i.e. custom post types & taxonomies, are managed using [`soberwp/models`](https://github.com/soberwp/models).

This directory also stores configuration of custom database fields managed by ACF.
However modification of these fields is usally done through the ACF app in the admin interface, rather than modifying these files directly.

### Skin Deep theme

Directory: [`./web/app/themes/sd-theme`](./web/app/themes/sd-theme)

The Skin Deep theme implements custom _presentation_ of Wordpress content.

### Skin Deep feature plugin

Directory: [`./web/app/mu-plugins/skin-deep`](./web/app/mu-plugins/skin-deep)

The feature plugin contains all bespoke Skin Deep _functionality_ rather than _presentation_.

The distinction is blurred between the two, but on the whole you need to ask yourself:

> If one day I find a nice snazzy theme and switch away to it, what bespoke behaviour should _still_ be installed?

That which should be retained lives in the feature plugin, rather than the theme.

## Documentation

Bedrock documentation is available at [https://roots.io/bedrock/docs/](https://roots.io/bedrock/docs/).

## Testing

This project employs some light testing.

Check for basic formatting errors with:
```bash
# Check for basic formatting errors
composer check
```

### End-to-end testing

The project employs some end-to-end testing using Cypress.

To run the tests, we start up a fresh server with controlled database contents. This is managed by docker.

Run the tests with:
```bash
# Navigate to testing directory
cd tests

# Start the mock server
docker-compose up

# Install cypress
yarn install

# Run the testing, or...
yarn run
# Open the Cypress test runner
yarn open
```


