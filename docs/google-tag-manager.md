# How to configure Google Tag Manager

## Step 1: Create new variable
- Name: GDPR Consent level 
- Variable type: 1st Party Cookie
- Cookie Name: __cookie_consent
![Create new Cookie consent variable](img/step1.png?raw=true "Create new Cookie consent variable")

## Step 2: Create trigger `GDPR - Has not given consent`
- Name: GDPR - Has not given consent
- Trigger type: Custom Event
- Event name: .* (regex.* triggers all types of events eg pageview, clicks, user engagement… )
- Tick regex matching
- This trigger fires on: Some Custom Event
- Fire this trigger when GDPR consent level matches RegEX ^(undefined|null|0|false|NaN|)$
- (^(undefined|null|0|false|NaN|)$ = RegEx to indicate that the cookie must have a value that is equel to  0, null, undefinded, false…)
![Create GDPR - has not given consent variable](img/step2.png?raw=true "Create GDPR - has not given consent variable")

## Step 3: Add 'no consent' trigger as exception to every tag
Open tag > beneath the title ‘triggering’ click the plus icon next to exceptions and select the right trigger.
![Add no consent trigger](img/step3.png?raw=true "Add no consent trigger") 

## Step 4: Create `analytic cookies` trigger
- Name: GDPR - Consent for analytical cookies
- Trigger type: Custom Event
- Event name: .* 
- Tick regex matching
- This trigger fires on: Some Custom Event
- Fire this trigger when GDPR Consent Level does not match RegEx 2|true
- (2|true = RegEx to indicate that the cookie can't have the value 2 OR true/1, this does mean that in combination with the previous exception, the value of the cookie should be 3 to fire the tag.)
![Create analytic cookie trigger](img/step4.png?raw=true "Create analytic cookie trigger") 

## Step 5: Add `analytic cookies` trigger as exception to all analytic tags  
Examples of analytic cookies are: Google Analytics, Hotjar, Matomo Analytics,...
![Add analytic cookie trigger exception](img/step5.png?raw=true "Add analytic cookie trigger exception") 

## Step 6: Add `Marketing cookie` trigger
- Name: GDPR - Consent for marketing cookies
- Trigger type: Custom Event
- Event name: .* 
- Tick regex matching
- This trigger fires on: Some Custom Event
- Fire this trigger when GDPR Consent level does not match RegEx 3|true

(3|true = RegEx to indicate that the cookie can't have the value 3 OR true/1 , this does mean that in combination with the previous exception, the value of the cookie should be 2 to fire the tag.)
![Add marketing trigger](img/step6.png?raw=true "Add marketing trigger") 

## Step 7: Add `marketing cookies` trigger as exception to all marketing tags
Examples of marketing cookies are: Facebook Pixel, Google Ads conversion tags, Online pop ups/surveys, Doubleclick, ...
![Add marketing cookies tag as exception](img/step7.png?raw=true "Add marketing cookies tag as exception")

## Step 8: Add new trigger `Cookie refresh`
- Name: Cookie refresh
- Trigger type: Custom Event
- Event name: cookie_refresh
- This trigger fires on: All Custom events
![Add trigger cookie refresh](img/step8.png?raw=true "Add trigger cookie refresh")

## Step 9: Add `cookie refresh` trigger to the `Universal Analytics` tag
![Add cooie refresh to univeral analytics](img/step9.png?raw=true "Add cooie refresh to univeral analytics")

## Step 10: Preview changes and check if the right tags get fired
Check if the righ tag get's fired eg. when your site visitor only wants analytic cookies.
## Step 11: Publish
Don't forget to publish all your changes ;-)