# Changelog

All notable changes to `Cookie Consent package` will be documented in this file.

## 1.2.12
### Updated
- Fixed english translation parameter for policyUrl
## 1.2.11
### Updated
- Updated documentation in Step 9 to be more clear.
## 1.2.10
### Updated
- Updated wicg-insert library to resolve conflict when loaded in twice
## 1.2.9
### Fixed
Last update had a breaking change, this is now fixed in this minor version.
### Added
Composer requirements
## 1.2.8
### Updated
- Added the ingored paths to readme file
### Removed
- Removed check if cookie policy page matches current url. Js handles this
## Version 1.2.7
### Updated
- Added data attribute for gtm event

## Version 1.2.6
### Updated
- Set default GTM event from pageview to cookie_refresh
## Version 1.2.5
### Removed
- Removed obsolete parenthesis from index.tpl 
## Version 1.2.4
### Updated
- Step 3 in google Tag Manager docs
- Changed some of the images to be more up to date
## Version 1.2.3
### Added
- Docs to configure Google Tag Manager
### Updated
- Added docs for Tag Manager to readme page
- Added TOC
## Version 1.2.2
### Updated
- Fixed readme file to contain correct snippets
## Version 1.2.1
### Updated
- Javascript changes for opening/altering modal
## Version 1.2.0
### Updated
- Rework of the frontend. Modal is now more generic.
- config has been reworked to alter cookie settings
## Version 1.1.0
### Updated
- Placed the javascript and css inside public/vendor/ instead of the public/ folder. 
- Changed publishing

## Version 1.0.3
### Updated
- Fixed bug in middleware return when response has no body tag
## Version 1.0.2
### Updated
- Fixed translation in js file
## Version 1.0.1
### Updated
- Changed policyUrl variable, set it on the top of snippet with @php()

## Version 1.0.0
### Added
- All the things
    - Middleware that triggers the package
    - CSS and Javascript files
    - The modal view + the cookie settings view
    - Service provider
    - translations for the major languages in Belgium (Dutch, French and English)
