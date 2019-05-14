# Skin Deep feature plugin

This feature plugin is responsible for the bespoke _functionality_ implemented for the Skin Deep server.

The majority of the code here is the logic associated with the custom models defined in the site's [models](../../../models).
That includes custom Gutenberg blocks, widgets, Snipcart integration, facebook integration, etc.

## Installation

Backend dependencies are managed with composer and installed with:

```
composer install
```

Frontend dependencies are managed with yarn and installed and built with:
```bash
# Install
yarn install

# Build development
yarn build

# Run for production (watches files for changes)
yarn start
```
