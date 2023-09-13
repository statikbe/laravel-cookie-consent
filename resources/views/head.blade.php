<link rel="stylesheet"
      type="text/css"
      href="{{ asset('vendor/cookie-consent/css/cookie-consent.css') }}">

@php
    $fbPixlCode = config('cookie-consent.facebook_pixel_code');
    $cookieKey = config('cookie-consent.cookie_key');
@endphp
@if (array_key_exists($cookieKey, $_COOKIE) && $_COOKIE[$cookieKey] == 'true')
    @if (!empty($fbPixlCode))
        <!-- Facebook Pixel Code -->
        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', {{ $fbPixlCode }});
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1"
                 width="1"
                 style="display:none"
                 src="https://www.facebook.com/tr?id={{ $fbPixlCode }}&ev=PageView&noscript=1" /></noscript>
        <!-- End Facebook Pixel Code -->
    @endif
@else
    <!-- Load Facebook Pixel Code on Consent -->
    <script>
        window.addEventListener('load', initFbScript, false)

        function initFbScript() {
            var cookieObserverElement = document.createElement('div');
            cookieObserverElement.style.display = 'none';
            cookieObserverElement.id = 'cookieObserverElement';
            document.body.appendChild(cookieObserverElement);

            var observer = new MutationObserver(loadFacebookPixel);
            observer.observe(document.getElementById('cookieObserverElement'), {
                childList: true
            });

            var intervalId = setInterval(updateCookieObserverElement, 1000); // Vérifiez chaque seconde

            function updateCookieObserverElement() {
                var cookieValue = decodeURIComponent(
                    document.cookie.replace(
                        new RegExp(
                            '(?:(?:^|.*;)\\s*' +
                            encodeURIComponent("{{ $cookieKey }}").replace(/[\-\.\+\*]/g, '\\$&') +
                            '\\s*\\=\\s*([^;]*).*$)|^.*$'
                        ),
                        '$1'
                    )
                ) || null
                document.getElementById('cookieObserverElement').innerText = cookieValue;
            }

            function loadFacebookPixel() {
                const cookieValue = document.getElementById('cookieObserverElement').innerText;
                if (cookieValue === 'true') {
                    let script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.text = `
                        ! function(f, b, e, v, n, t, s) {
                            if (f.fbq) return;
                            n = f.fbq = function() {
                                n.callMethod ?
                                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                            };
                            if (!f._fbq) f._fbq = n;
                            n.push = n;
                            n.loaded = !0;
                            n.version = '2.0';
                            n.queue = [];
                            t = b.createElement(e);
                            t.async = !0;
                            t.src = v;
                            s = b.getElementsByTagName(e)[0];
                            s.parentNode.insertBefore(t, s)
                        }(window, document, 'script',
                            'https://connect.facebook.net/en_US/fbevents.js');
                        fbq('init', {{ $fbPixlCode }});
                        fbq('track', 'PageView');
                    `;
                    let noScript = document.createElement('noscript');
                    let noScriptImg = document.createElement('img')
                    noScriptImg.style.display = 'none';
                    noScriptImg.height = '1';
                    noScriptImg.width = '1';
                    noScriptImg.src = "https://www.facebook.com/tr?id={{ $fbPixlCode }}&ev=PageView&noscript=1";

                    noScript.appendChild(noScriptImg)

                    document.head.appendChild(script);
                    document.head.appendChild(noScript);
                    clearInterval(intervalId)
                }
            }
        }
    </script>
    <!-- End Facebook Pixel Code on Consent -->
@endif
