# Changelog

All notable changes to `Cookie Consent package` will be documented in this file.
## 1.10.0
- Added option to add secure flag to a cookie
## 1.9.1
- Added .gitattributes
## 1.9.0
-  Updated to be compatible with Laravel 12
## 1.8.6
- Updated readme
## 1.8.5
- Styling updates
## 1.8.4
- Styling updates
## 1.8.3
- Styling updates
## 1.8.2
- Styling updates
## 1.8.1
- Fixed typo in js
## 1.8.0
- Added option to set the domain property on a cookie with Session domain config from Laravel
## 1.7.0 
-  Updated to be compatible with Laravel 11
- changed group names for publishing config/views/etc
## 1.6.0
- Added Callback for Google Consent mode
## 1.5.0
- Updated to be compatible with Laravel 10
- Added return types
- Removed compatibility with Laravel 8
- Removed compatibility with PHP 7
## 1.4.2
- Added banner on top readme
- Made preview images same width
## 1.4.1
- Added packagist downloads
- Added screenshots of modals
## 1.4.0
- Styling changes to separate between functional and essential cookies
- Added upgrading.md
## 1.3.4 and 1.3.5 and 1.3.6
- Security fixes (thanks to dependabot)
## 1.3.3
### Fixed
- Lang publishing for Laravel 9
## 1.3.2
### Updated
- Ignored paths now prevents loading cookie module view instead of preventing javascript
- Updated usage in readme
## 1.3.1
### Updated
- Made it possible to accept all from modal
- Added accept only essential cookies
## 1.3.0
### Updated
- Updated to be compatible with Laravel 9
- Removed compatibility with Laravel 6 & 7
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
- Changed some images to be more up to date
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
