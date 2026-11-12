(function () {
	'use strict';

	function loadMap(container) {
		if (!container || container.dataset.loaded === 'true') return;
		var src = container.dataset.mapSrc;
		if (!src) return;

		var iframe = document.createElement('iframe');
		iframe.src = src;
		iframe.title = container.dataset.mapTitle || 'Google Maps';
		iframe.loading = 'lazy';
		iframe.referrerPolicy = 'no-referrer-when-downgrade';
		iframe.allowFullscreen = true;
		container.dataset.loaded = 'true';
		container.replaceChildren(iframe);
	}

	function initMaps() {
		var maps = document.querySelectorAll('[data-tfd-map]');
		if (!maps.length) return;

		maps.forEach(function (map) {
			var button = map.querySelector('[data-tfd-map-button]');
			if (button) button.addEventListener('click', function () { loadMap(map); });
		});

		if ('IntersectionObserver' in window) {
			var observer = new IntersectionObserver(function (entries, mapObserver) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						loadMap(entry.target);
						mapObserver.unobserve(entry.target);
					}
				});
			}, { rootMargin: '250px 0px', threshold: 0.01 });
			maps.forEach(function (map) { if (map.dataset.mapMode === 'scroll') observer.observe(map); });
		}
	}

	function preselectLocation(root) {
		var booking = document.querySelector('[data-current-location]');
		if (!booking) return;
		var slug = booking.dataset.currentLocation;
		(root || document).querySelectorAll('select[name="location"]').forEach(function (select) {
			var option = Array.prototype.find.call(select.options, function (item) { return item.value === slug; });
			if (option) {
				select.value = slug;
				select.dispatchEvent(new Event('change', { bubbles: true }));
			}
		});
	}

	function initAnchorScroll() {
		document.querySelectorAll('a[href="#appointment-form"]').forEach(function (link) {
			link.addEventListener('click', function (event) {
				var target = document.getElementById('appointment-form');
				if (!target) return;
				event.preventDefault();
				target.scrollIntoView({ behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' });
			});
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		initMaps();
		initAnchorScroll();
		preselectLocation(document);
	});
	document.addEventListener('wpcf7init', function (event) { preselectLocation(event.target || document); });
}());
