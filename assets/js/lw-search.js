(function () {
    'use strict';

    if (typeof lwSearchData === 'undefined') return;

    var apartments = lwSearchData.apartments || [];
    var areaMin = Number(lwSearchData.areaMin) || 0;
    var areaMax = Number(lwSearchData.areaMax) || 200;

    // DOM refs (null-safe — form and results may live in separate Elementor widgets)
    var investmentSelect = document.getElementById('lw-filter-investment');
    var areaMinInput = document.getElementById('lw-area-min');
    var areaMaxInput = document.getElementById('lw-area-max');
    var areaMinVal = document.getElementById('lw-area-min-val');
    var areaMaxVal = document.getElementById('lw-area-max-val');
    var roomsMinSelect = document.getElementById('lw-filter-rooms-min');
    var roomsMaxSelect = document.getElementById('lw-filter-rooms-max');
    var floorMinSelect = document.getElementById('lw-filter-floor-min');
    var floorMaxSelect = document.getElementById('lw-filter-floor-max');
    var searchBtn = document.getElementById('lw-search-btn');
    var tableBody = document.getElementById('lw-table-body');
    var grid = document.getElementById('lw-grid');
    var noResults = document.getElementById('lw-no-results');
    var table = document.getElementById('lw-table');
    var rangeTrack = document.getElementById('lw-range-track');
    // Dropdown state
    var currentStatus = (typeof window.lwDefaultStatus !== 'undefined') ? window.lwDefaultStatus : 'dostepne';
    var currentSort = '';

    var currentView = (typeof window.lwDefaultView !== 'undefined') ? window.lwDefaultView : 'grid';

    // View toggle
    var viewBtns = document.querySelectorAll('.lw-view-btn');
    for (var b = 0; b < viewBtns.length; b++) {
        viewBtns[b].addEventListener('click', function () {
            var view = this.getAttribute('data-view');
            if (view === currentView) return;
            currentView = view;
            for (var j = 0; j < viewBtns.length; j++) {
                viewBtns[j].classList.remove('lw-view-btn--active');
            }
            this.classList.add('lw-view-btn--active');
            doSearch();
        });
    }

    // Populate investment dropdown from settings
    var investments = lwSearchData.investmentNames || [];
    if (investmentSelect) {
        investments.forEach(function (inv) {
            addOption(investmentSelect, inv, inv);
        });
    }

    // Populate rooms dropdowns
    var roomsMin = Number(lwSearchData.roomsMin) || 1;
    var roomsMax = Number(lwSearchData.roomsMax) || 6;
    for (var r = roomsMin; r <= roomsMax; r++) {
        if (roomsMinSelect) addOption(roomsMinSelect, r, String(r));
        if (roomsMaxSelect) addOption(roomsMaxSelect, r, String(r));
    }

    // Populate floor dropdowns
    var floorMin = Number(lwSearchData.floorMin) || 0;
    var floorMax = Number(lwSearchData.floorMax) || 10;
    for (var f = floorMin; f <= floorMax; f++) {
        var label = f === 0 ? 'Parter' : String(f);
        if (floorMinSelect) addOption(floorMinSelect, f, label);
        if (floorMaxSelect) addOption(floorMaxSelect, f, label);
    }

    function addOption(select, value, text) {
        var opt = document.createElement('option');
        opt.value = value;
        opt.textContent = text;
        select.appendChild(opt);
    }

    // Setup range slider
    if (areaMinInput && areaMaxInput) {
        areaMinInput.min = areaMin;
        areaMinInput.max = areaMax;
        areaMinInput.value = areaMin;
        areaMaxInput.min = areaMin;
        areaMaxInput.max = areaMax;
        areaMaxInput.value = areaMax;
    }

    function updateRangeDisplay() {
        if (!areaMinInput || !areaMaxInput) return;
        var min = Number(areaMinInput.value);
        var max = Number(areaMaxInput.value);
        if (min > max) {
            areaMinInput.value = max;
            areaMaxInput.value = min;
            min = Number(areaMinInput.value);
            max = Number(areaMaxInput.value);
        }
        if (areaMinVal) areaMinVal.textContent = min + ' m2';
        if (areaMaxVal) areaMaxVal.textContent = max + ' m2';
        if (rangeTrack) {
            var range = areaMax - areaMin || 1;
            var leftPct = ((min - areaMin) / range) * 100;
            var rightPct = ((max - areaMin) / range) * 100;
            rangeTrack.style.background =
                'linear-gradient(to right, #ccc ' + leftPct + '%, #C8B89A ' + leftPct + '%, #C8B89A ' + rightPct + '%, #ccc ' + rightPct + '%)';
        }
    }

    if (areaMinInput) areaMinInput.addEventListener('input', updateRangeDisplay);
    if (areaMaxInput) areaMaxInput.addEventListener('input', updateRangeDisplay);
    updateRangeDisplay();

    // Status config
    var statusConfig = {
        dostepne:      { label: 'Dost\u0119pne',  cls: 'lw-status--dostepne' },
        zarezerwowane: { label: 'Rezerwacja',      cls: 'lw-status--zarezerwowane' },
        sprzedane:     { label: 'Sprzedane',       cls: 'lw-status--sprzedane' },
    };

    function createEl(tag, cls, text) {
        var el = document.createElement(tag);
        if (cls) el.className = cls;
        if (text) el.textContent = text;
        return el;
    }

    // ── Price history modal ──

    function showPriceHistoryModal(apartment) {
        // Remove existing modal
        var existing = document.getElementById('lw-price-modal');
        if (existing) existing.remove();

        var history = apartment.price_history || [];
        var backdrop = createEl('div', 'lw-modal-backdrop');
        backdrop.id = 'lw-price-modal';

        var modal = createEl('div', 'lw-modal');

        var header = createEl('div', 'lw-modal__header', 'Historia cen \u2014 Mieszkanie ' + apartment.title);
        modal.appendChild(header);

        var body = createEl('div', 'lw-modal__body');
        var tbl = document.createElement('table');
        tbl.className = 'lw-modal__table';

        var thead = document.createElement('thead');
        var headRow = document.createElement('tr');
        var th1 = createEl('th', null, 'Cena');
        var th2 = createEl('th', null, 'Obowi\u0105zuje od dnia');
        headRow.appendChild(th1);
        headRow.appendChild(th2);
        thead.appendChild(headRow);
        tbl.appendChild(thead);

        var tbody = document.createElement('tbody');
        if (history.length > 0) {
            history.forEach(function (row) {
                var tr = document.createElement('tr');
                tr.appendChild(createCell(row.price || '\u2014'));
                tr.appendChild(createCell(row.date || '\u2014'));
                tbody.appendChild(tr);
            });
        } else {
            var tr = document.createElement('tr');
            tr.appendChild(createCell(apartment.price_total_formatted || '\u2014'));
            tr.appendChild(createCell('\u2014'));
            tbody.appendChild(tr);
        }
        tbl.appendChild(tbody);
        body.appendChild(tbl);
        modal.appendChild(body);

        var footer = createEl('div', 'lw-modal__footer');
        var closeBtn = createEl('button', 'lw-modal__close', 'Zamknij');
        closeBtn.type = 'button';
        closeBtn.addEventListener('click', function () { backdrop.remove(); });
        footer.appendChild(closeBtn);
        modal.appendChild(footer);

        backdrop.appendChild(modal);
        backdrop.addEventListener('click', function (e) {
            if (e.target === backdrop) backdrop.remove();
        });

        document.body.appendChild(backdrop);
    }

    // ── Table rendering ──

    function createCell(text) {
        var td = document.createElement('td');
        td.textContent = text;
        return td;
    }

    function renderTable(data) {
        while (tableBody.firstChild) tableBody.removeChild(tableBody.firstChild);

        data.forEach(function (a) {
            var tr = document.createElement('tr');

            var tdTitle = document.createElement('td');
            var link = document.createElement('a');
            link.href = a.link;
            link.target = '_blank';
            link.rel = 'noopener';
            link.textContent = a.title;
            tdTitle.appendChild(link);
            tr.appendChild(tdTitle);

            tr.appendChild(createCell(a.floor));
            tr.appendChild(createCell(String(a.rooms)));

            var areaFmt = a.area !== null ? String(a.area).replace('.', ',') + ' m\u00B2' : '\u2014';
            tr.appendChild(createCell(areaFmt));

            var st = statusConfig[a.status] || { label: a.status_name || '\u2014', cls: '' };
            var tdStatus = document.createElement('td');
            var statusSpan = createEl('span', 'lw-status ' + st.cls, st.label);
            tdStatus.appendChild(statusSpan);
            tr.appendChild(tdStatus);

            // Price cell with history icon
            var tdPrice = document.createElement('td');
            tdPrice.textContent = a.price_total_formatted || '\u2014';
            if (a.price_history && a.price_history.length > 0) {
                var histBtn = createEl('button', 'lw-hist-trigger', '\uD83D\uDCC5');
                histBtn.type = 'button';
                histBtn.title = 'Historia cen';
                histBtn.addEventListener('click', (function (apt) {
                    return function (e) { e.stopPropagation(); showPriceHistoryModal(apt); };
                })(a));
                tdPrice.appendChild(document.createTextNode(' '));
                tdPrice.appendChild(histBtn);
            }
            tr.appendChild(tdPrice);

            tr.appendChild(createCell(a.investment || ''));

            // Actions cell
            var tdActions = document.createElement('td');
            tdActions.className = 'lw-table__actions';

            function makeTableAction(href, tooltip, iconName, extraClass) {
                var btn = document.createElement('a');
                btn.className = 'lw-table-action' + (extraClass ? ' ' + extraClass : '');
                btn.href = href;
                btn.target = '_blank';
                btn.rel = 'noopener';
                btn.title = tooltip;
                btn.setAttribute('data-tooltip', tooltip);
                var icon = document.createElement('easier-icon');
                icon.setAttribute('name', iconName);
                icon.setAttribute('size', '16');
                btn.appendChild(icon);
                return btn;
            }

            if (a.gallery_3d && a.gallery_3d.length > 0) {
                tdActions.appendChild(makeTableAction(a.gallery_3d[0], 'Wizualizacja 3D', '3d-scale'));
            }

            tdActions.appendChild(makeTableAction(a.link, 'Obejrzyj mieszkanie', 'eye'));

            if (a.pdf_url) {
                tdActions.appendChild(makeTableAction(a.pdf_url, 'Rzuty 3D', 'floor-plan'));
            }

            if (a.status === 'dostepne' && a.inquiry_url) {
                tdActions.appendChild(makeTableAction(a.inquiry_url, 'Wy\u015Blij zapytanie', 'mail-send-01', 'lw-table-action--inquiry'));
            }

            tr.appendChild(tdActions);

            tableBody.appendChild(tr);
        });
    }

    // ── Grid (card) rendering ──

    function renderGrid(data) {
        while (grid.firstChild) grid.removeChild(grid.firstChild);

        data.forEach(function (a) {
            var card = createEl('div', 'lw-card');
            var st = statusConfig[a.status] || { label: a.status_name || '', cls: '' };

            if (a.status) card.classList.add('lw-card--' + a.status);

            // ── Left: thumbnail ──
            var thumbCol = createEl('div', 'lw-card__thumb');
            if (a.thumbnail) {
                var imgRzut = document.createElement('img');
                imgRzut.className = 'lw-card__thumb-rzut';
                imgRzut.src = a.thumbnail;
                imgRzut.alt = 'Rzut - ' + a.title;
                imgRzut.loading = 'lazy';
                thumbCol.appendChild(imgRzut);
            }
            if (a.gallery_3d && a.gallery_3d.length > 0) {
                var imgViz = document.createElement('img');
                imgViz.className = 'lw-card__thumb-viz';
                imgViz.src = a.gallery_3d[0];
                imgViz.alt = 'Wizualizacja - ' + a.title;
                imgViz.loading = 'lazy';
                thumbCol.appendChild(imgViz);
            }
            if (!a.thumbnail && !(a.gallery_3d && a.gallery_3d.length > 0)) {
                var placeholder = createEl('div', 'lw-card__thumb-placeholder');
                placeholder.textContent = '\uD83C\uDFE0';
                thumbCol.appendChild(placeholder);
            }
            card.appendChild(thumbCol);

            // ── Center: info ──
            var info = createEl('div', 'lw-card__info');

            if (a.investment) {
                var invBadge = createEl('span', 'lw-card__investment', a.investment);
                info.appendChild(invBadge);
            }

            var titleEl = createEl('h3', 'lw-card__title', 'Mieszkanie ' + a.title);
            info.appendChild(titleEl);

            var details = createEl('ul', 'lw-card__details');

            addDetail(details, a.floor_num === 0 ? 'Parter' : a.floor + ' pi\u0119tro');
            addDetail(details, a.area !== null ? String(a.area).replace('.', ',') + 'm\u00B2' : null);
            addDetail(details, a.rooms + ' ' + roomsWord(a.rooms));

            // Show prices for non-sold apartments
            if (a.status !== 'sprzedane') {
                if (a.price_m2_formatted) {
                    addDetailEl(details, 'Cena za metr: ' + a.price_m2_formatted);
                }
                if (a.price_total_formatted) {
                    addDetailEl(details, 'Cena: ' + a.price_total_formatted);
                }
            }

            // Price history icon (shown regardless of status/price)
            if (a.price_history && a.price_history.length > 0) {
                var histLi = addDetailEl(details, 'Historia cen');
                var histIcon = createEl('button', 'lw-hist-trigger', '\uD83D\uDCC5');
                histIcon.type = 'button';
                histIcon.title = 'Historia cen';
                histIcon.addEventListener('click', (function (apt) {
                    return function (e) { e.stopPropagation(); showPriceHistoryModal(apt); };
                })(a));
                histLi.appendChild(document.createTextNode(' '));
                histLi.appendChild(histIcon);
            }

            info.appendChild(details);
            card.appendChild(info);

            // ── Right: actions ──
            var actions = createEl('div', 'lw-card__actions');

            // wizualizacja 3D (gallery)
            if (a.gallery_3d && a.gallery_3d.length > 0) {
                var viz = createEl('a', 'lw-card__btn lw-card__btn--viz');
                viz.href = a.gallery_3d[0];
                viz.target = '_blank';
                viz.rel = 'noopener';
                viz.textContent = 'wizualizacja 3D  \u203A';
                actions.appendChild(viz);
            }

            // obejrzyj mieszkanie
            var viewBtn = createEl('a', 'lw-card__btn lw-card__btn--view');
            viewBtn.href = a.link;
            viewBtn.target = '_blank';
            viewBtn.rel = 'noopener';
            viewBtn.textContent = 'obejrzyj mieszkanie  \u203A';
            actions.appendChild(viewBtn);

            // Rzuty 3D (PDF catalog card)
            if (a.pdf_url) {
                var rzuty = createEl('a', 'lw-card__btn lw-card__btn--rzuty');
                rzuty.href = a.pdf_url;
                rzuty.target = '_blank';
                rzuty.rel = 'noopener';
                rzuty.textContent = 'Rzuty 3D  \u203A';
                actions.appendChild(rzuty);
            }

            // Wyślij zapytanie (only for dostepne)
            if (a.status === 'dostepne' && a.inquiry_url) {
                var inquiry = createEl('a', 'lw-card__btn lw-card__btn--inquiry');
                inquiry.href = a.inquiry_url;
                inquiry.textContent = 'wy\u015Blij zapytanie  \u2709';
                actions.appendChild(inquiry);
            }

            card.appendChild(actions);

            // ── Status badge ──
            var badge = createEl('div', 'lw-card__status ' + st.cls, st.label);
            card.appendChild(badge);

            // ── Overlay ──
            var overlay = createEl('div', 'lw-card__overlay');
            var oTexts = window.lwOverlayTexts || {};
            var overlayLabel = (oTexts[a.status] || '') || st.label;
            var overlayText = createEl('span', null, overlayLabel);
            overlay.appendChild(overlayText);
            card.appendChild(overlay);

            grid.appendChild(card);
        });
    }

    function addDetail(ul, text) {
        if (!text) return;
        var li = createEl('li', null, text);
        ul.appendChild(li);
    }

    function addDetailEl(ul, text) {
        if (!text) return null;
        var li = createEl('li', null, text);
        ul.appendChild(li);
        return li;
    }

    function roomsWord(n) {
        if (n === 1) return 'pok\u00F3j';
        if (n >= 2 && n <= 4) return 'pokoje';
        return 'pokoi';
    }

    // ── Search / filter ──

    function doSearch() {
        var inv = investmentSelect ? investmentSelect.value : '';
        var aMin = areaMinInput ? Number(areaMinInput.value) : areaMin;
        var aMax = areaMaxInput ? Number(areaMaxInput.value) : areaMax;
        if (aMin > aMax) { var t = aMin; aMin = aMax; aMax = t; }

        var rMin = roomsMinSelect && roomsMinSelect.value ? Number(roomsMinSelect.value) : null;
        var rMax = roomsMaxSelect && roomsMaxSelect.value ? Number(roomsMaxSelect.value) : null;
        var fMin = floorMinSelect && floorMinSelect.value !== '' ? Number(floorMinSelect.value) : null;
        var fMax = floorMaxSelect && floorMaxSelect.value !== '' ? Number(floorMaxSelect.value) : null;

        var status = currentStatus;

        var filtered = apartments.filter(function (a) {
            if (inv && a.investment !== inv) return false;
            if (status && a.status !== status) return false;
            if (a.area !== null) {
                if (a.area < aMin || a.area > aMax) return false;
            }
            if (rMin !== null && a.rooms < rMin) return false;
            if (rMax !== null && a.rooms > rMax) return false;
            if (fMin !== null && a.floor_num < fMin) return false;
            if (fMax !== null && a.floor_num > fMax) return false;
            return true;
        });

        // Sort
        var sort = currentSort;
        if (sort) {
            filtered.sort(function (a, b) {
                if (sort === 'price-asc') return (a.price_total || Infinity) - (b.price_total || Infinity);
                if (sort === 'price-desc') return (b.price_total || 0) - (a.price_total || 0);
                if (sort === 'title-asc') return a.title.localeCompare(b.title, 'pl');
                if (sort === 'title-desc') return b.title.localeCompare(a.title, 'pl');
                return 0;
            });
        }

        // Update active filter count badge
        var activeCount = 0;
        if (inv) activeCount++;
        if (aMin > areaMin || aMax < areaMax) activeCount++;
        if (rMin !== null || rMax !== null) activeCount++;
        if (fMin !== null || fMax !== null) activeCount++;
        if (status) activeCount++;
        if (sort) activeCount++;
        var badge = document.querySelector('.lw-drawer-trigger__badge');
        if (badge) {
            if (activeCount > 0) {
                badge.textContent = activeCount;
                badge.style.display = '';
            } else {
                badge.style.display = 'none';
            }
        }

        if (filtered.length === 0) {
            if (table) table.style.display = 'none';
            if (grid) grid.style.display = 'none';
            if (noResults) noResults.style.display = 'block';
        } else if (currentView === 'table') {
            if (table) table.style.display = '';
            if (grid) grid.style.display = 'none';
            if (noResults) noResults.style.display = 'none';
            if (tableBody) renderTable(filtered);
        } else {
            if (table) table.style.display = 'none';
            if (grid) grid.style.display = '';
            if (noResults) noResults.style.display = 'none';
            if (grid) renderGrid(filtered);
        }
    }

    var hideBeforeSearch = window.lwHideBeforeSearch || false;
    var resultsRevealed = !hideBeforeSearch;

    function revealResults() {
        var resultsEl = document.getElementById('lw-results');
        var toolbarEl = document.getElementById('lw-results-toolbar');
        if (resultsEl) resultsEl.style.display = '';
        if (toolbarEl) toolbarEl.style.display = '';
        resultsRevealed = true;
    }

    if (searchBtn) searchBtn.addEventListener('click', function () {
        if (!resultsRevealed) revealResults();
        doSearch();
    });

    // ── Custom dropdowns ──
    function initDropdown(dropdownId, btnId, onSelect) {
        var dropdown = document.getElementById(dropdownId);
        var btn = document.getElementById(btnId);
        if (!dropdown || !btn) return;
        var menu = dropdown.querySelector('.lw-dropdown__menu');
        var items = menu.querySelectorAll('.lw-dropdown__item');
        var label = btn.querySelector('.lw-toolbar-btn__label');

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            // Close other dropdowns
            var allDropdowns = document.querySelectorAll('.lw-dropdown');
            for (var d = 0; d < allDropdowns.length; d++) {
                if (allDropdowns[d] !== dropdown) allDropdowns[d].classList.remove('lw-dropdown--open');
            }
            dropdown.classList.toggle('lw-dropdown--open');
        });

        for (var i = 0; i < items.length; i++) {
            items[i].addEventListener('click', function () {
                var val = this.getAttribute('data-value');
                label.textContent = this.textContent;
                for (var j = 0; j < items.length; j++) {
                    items[j].classList.remove('lw-dropdown__item--active');
                }
                this.classList.add('lw-dropdown__item--active');
                dropdown.classList.remove('lw-dropdown--open');
                if (val !== '') {
                    btn.classList.add('lw-toolbar-btn--active');
                } else {
                    btn.classList.remove('lw-toolbar-btn--active');
                }
                onSelect(val);
            });
        }
    }

    initDropdown('lw-dropdown-status', 'lw-btn-status', function (val) {
        currentStatus = val;
        doSearch();
    });

    initDropdown('lw-dropdown-sort', 'lw-btn-sort', function (val) {
        currentSort = val;
        doSearch();
    });

    // Close dropdowns on outside click
    document.addEventListener('click', function () {
        var allDropdowns = document.querySelectorAll('.lw-dropdown');
        for (var d = 0; d < allDropdowns.length; d++) {
            allDropdowns[d].classList.remove('lw-dropdown--open');
        }
    });

    // ── Mobile drawer ──
    (function initDrawer() {
        var MOBILE_BP = 768;
        var toolbar = document.querySelector('.lw-results-toolbar');
        if (!toolbar) return;

        var toolbarLeft = toolbar.querySelector('.lw-toolbar-left');
        var viewToggle = toolbar.querySelector('.lw-view-toggle');
        var searchForm = document.querySelector('.lw-search-form');

        // Create drawer trigger button
        var triggerBtn = document.createElement('button');
        triggerBtn.type = 'button';
        triggerBtn.className = 'lw-drawer-trigger';
        triggerBtn.setAttribute('data-tooltip', 'Szukaj i sortuj');
        var triggerIcon = document.createElement('easier-icon');
        triggerIcon.setAttribute('name', 'filter');
        triggerIcon.setAttribute('variant', 'stroke');
        triggerIcon.setAttribute('size', '18');
        triggerBtn.appendChild(triggerIcon);
        var triggerLabel = document.createElement('span');
        triggerLabel.className = 'lw-drawer-trigger__label';
        triggerLabel.textContent = 'Szukaj i sortuj';
        triggerBtn.appendChild(triggerLabel);

        var triggerBadge = document.createElement('span');
        triggerBadge.className = 'lw-drawer-trigger__badge';
        triggerBadge.style.display = 'none';
        triggerBtn.appendChild(triggerBadge);

        // Create drawer structure
        var overlay = document.createElement('div');
        overlay.className = 'lw-drawer-overlay';

        var drawer = document.createElement('div');
        drawer.className = 'lw-drawer';

        var drawerHeader = document.createElement('div');
        drawerHeader.className = 'lw-drawer__header';
        var drawerTitle = document.createElement('span');
        drawerTitle.className = 'lw-drawer__title';
        drawerTitle.textContent = 'Szukaj i sortuj';
        var closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'lw-drawer__close';
        closeBtn.textContent = '\u2715';
        drawerHeader.appendChild(drawerTitle);
        drawerHeader.appendChild(closeBtn);
        drawer.appendChild(drawerHeader);

        var drawerBody = document.createElement('div');
        drawerBody.className = 'lw-drawer__body';
        drawer.appendChild(drawerBody);

        document.body.appendChild(overlay);
        document.body.appendChild(drawer);

        // Placeholders to remember original positions
        var formPlaceholder = document.createElement('div');
        formPlaceholder.className = 'lw-drawer-placeholder lw-drawer-placeholder--form';
        formPlaceholder.style.display = 'none';
        var toolbarLeftPlaceholder = document.createElement('div');
        toolbarLeftPlaceholder.className = 'lw-drawer-placeholder lw-drawer-placeholder--toolbar';
        toolbarLeftPlaceholder.style.display = 'none';

        var isInDrawer = false;

        function moveToDrawer() {
            if (isInDrawer) return;
            isInDrawer = true;

            // Insert placeholders, then move elements
            if (searchForm && searchForm.parentNode) {
                searchForm.parentNode.insertBefore(formPlaceholder, searchForm);
                drawerBody.appendChild(searchForm);
            }
            if (toolbarLeft && toolbarLeft.parentNode) {
                toolbarLeft.parentNode.insertBefore(toolbarLeftPlaceholder, toolbarLeft);
                drawerBody.appendChild(toolbarLeft);
                toolbarLeft.className = 'lw-toolbar-left lw-toolbar-left--drawer';
            }
        }

        function moveBack() {
            if (!isInDrawer) return;
            isInDrawer = false;

            if (searchForm && formPlaceholder.parentNode) {
                formPlaceholder.parentNode.insertBefore(searchForm, formPlaceholder);
                formPlaceholder.parentNode.removeChild(formPlaceholder);
            }
            if (toolbarLeft && toolbarLeftPlaceholder.parentNode) {
                toolbarLeftPlaceholder.parentNode.insertBefore(toolbarLeft, toolbarLeftPlaceholder);
                toolbarLeftPlaceholder.parentNode.removeChild(toolbarLeftPlaceholder);
                toolbarLeft.className = 'lw-toolbar-left';
            }
        }

        function openDrawer() {
            moveToDrawer();
            overlay.classList.add('lw-drawer-overlay--open');
            drawer.classList.add('lw-drawer--open');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            overlay.classList.remove('lw-drawer-overlay--open');
            drawer.classList.remove('lw-drawer--open');
            document.body.style.overflow = '';
        }

        // Insert trigger before view toggle
        if (viewToggle && viewToggle.parentNode) {
            viewToggle.parentNode.insertBefore(triggerBtn, viewToggle);
        }

        triggerBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            openDrawer();
        });

        // Close drawer when search button is clicked
        if (searchBtn) {
            searchBtn.addEventListener('click', function () {
                if (drawer.classList.contains('lw-drawer--open')) {
                    closeDrawer();
                }
            });
        }

        // Handle resize: move elements back when going to desktop (only if drawer closed)
        var wasDesktop = window.innerWidth > MOBILE_BP;
        function onResize() {
            var isDesktop = window.innerWidth > MOBILE_BP;
            if (isDesktop && !wasDesktop && !drawer.classList.contains('lw-drawer--open')) {
                moveBack();
            }
            wasDesktop = isDesktop;
        }
        window.addEventListener('resize', onResize);

        // Always move elements back when drawer closes
        var origClose = closeDrawer;
        closeDrawer = function () {
            origClose();
            if (window.innerWidth > MOBILE_BP) {
                moveBack();
            }
        };
        closeBtn.addEventListener('click', closeDrawer);
        overlay.addEventListener('click', closeDrawer);

        // ── Sticky bottom bar ──
        var stickyBar = document.createElement('div');
        stickyBar.className = 'lw-sticky-bar';
        stickyBar.style.display = 'none';

        var stickyLeft = document.createElement('div');
        stickyLeft.className = 'lw-sticky-bar__left';
        stickyBar.appendChild(stickyLeft);

        var stickyRight = document.createElement('div');
        stickyRight.className = 'lw-sticky-bar__right';
        stickyBar.appendChild(stickyRight);

        var stickyFilterBtn = document.createElement('button');
        stickyFilterBtn.type = 'button';
        stickyFilterBtn.className = 'lw-sticky-bar__btn lw-sticky-bar__btn--filter';
        stickyFilterBtn.innerHTML = '<easier-icon name="filter" variant="stroke" size="18"></easier-icon> Filtry';
        stickyRight.appendChild(stickyFilterBtn);

        var stickySearchBtn = document.createElement('button');
        stickySearchBtn.type = 'button';
        stickySearchBtn.className = 'lw-sticky-bar__btn lw-sticky-bar__btn--search';
        stickySearchBtn.textContent = searchBtn ? (searchBtn.textContent || 'SZUKAJ') : 'SZUKAJ';
        stickyRight.appendChild(stickySearchBtn);

        document.body.appendChild(stickyBar);

        // Sort dropdown and view toggle references from toolbar
        var sortDropdown = document.getElementById('lw-dropdown-sort');
        var viewToggleEl = toolbar.querySelector('.lw-view-toggle');

        // Placeholders for sort/view when moved to sticky bar
        var sortPlaceholder = document.createElement('div');
        sortPlaceholder.style.display = 'none';
        var viewPlaceholder = document.createElement('div');
        viewPlaceholder.style.display = 'none';

        var stickyVisible = false;

        function moveToStickyBar() {
            if (sortDropdown && sortDropdown.parentNode) {
                sortDropdown.parentNode.insertBefore(sortPlaceholder, sortDropdown);
                stickyLeft.appendChild(sortDropdown);
            }
            if (viewToggleEl && viewToggleEl.parentNode) {
                viewToggleEl.parentNode.insertBefore(viewPlaceholder, viewToggleEl);
                stickyLeft.appendChild(viewToggleEl);
            }
        }

        function moveFromStickyBar() {
            if (sortDropdown && sortPlaceholder.parentNode) {
                sortPlaceholder.parentNode.insertBefore(sortDropdown, sortPlaceholder);
                sortPlaceholder.parentNode.removeChild(sortPlaceholder);
            }
            if (viewToggleEl && viewPlaceholder.parentNode) {
                viewPlaceholder.parentNode.insertBefore(viewToggleEl, viewPlaceholder);
                viewPlaceholder.parentNode.removeChild(viewPlaceholder);
            }
        }

        stickyFilterBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            openDrawer();
        });

        stickySearchBtn.addEventListener('click', function () {
            if (!resultsRevealed) revealResults();
            doSearch();
            var resultsEl = document.getElementById('lw-results');
            if (resultsEl) resultsEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        // Show/hide sticky bar based on form visibility
        if (searchForm && 'IntersectionObserver' in window) {
            var stickyObserver = new IntersectionObserver(function (entries) {
                var formVisible = entries[0].isIntersecting;
                if (!formVisible && !stickyVisible) {
                    stickyVisible = true;
                    moveToStickyBar();
                    stickyBar.style.display = '';
                } else if (formVisible && stickyVisible) {
                    stickyVisible = false;
                    stickyBar.style.display = 'none';
                    moveFromStickyBar();
                }
            }, { threshold: 0 });
            stickyObserver.observe(searchForm);
        }
    })();

    if (!hideBeforeSearch) doSearch();
})();
