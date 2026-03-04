=== LW Search Bar ===
Contributors: liskadev
Tags: apartments, search, real estate
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0

Wyszukiwarka mieszkań dla Rezydencji Liwskiej — łączy dane z dwóch inwestycji przez REST API.

== Description ==

Plugin dodaje shortcode `[lw_search_bar]` wyświetlający formularz wyszukiwania mieszkań z filtrami: inwestycja, powierzchnia (dual-range slider), pokoje (od/do), piętro (od/do).

Dane pobierane są z lokalnego CPT `lokal` oraz z drugiej instalacji WordPress przez REST API `/lw/v1/apartments`.

== Installation ==

1. Zainstaluj plugin w `wp-content/plugins/lw-search-bar/`
2. Aktywuj w panelu WordPress
3. Przejdź do Ustawienia → LW Search Bar i skonfiguruj URL zdalnej strony oraz nazwę inwestycji
4. Dodaj shortcode `[lw_search_bar]` na wybranej stronie

== Changelog ==

= 1.0.0 =
* Pierwsza wersja
