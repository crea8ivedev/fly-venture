export function initCountyMap() {
    const svg = document.getElementById('county-map-svg');
    if (!svg) return;

    const countyNameEl  = document.getElementById('selected-county-name');
    const countyPriceEl = document.getElementById('selected-county-price');
    const tooltipPill   = document.getElementById('county-tooltip-pill');
    const tooltipRect   = document.getElementById('tooltip-pill-rect');
    const tooltipText   = document.getElementById('tooltip-pill-text');

    const PAD_X = 18;
    const PAD_Y = 9;

    // ── Manual position overrides ─────────────────────────────────────────────
    // If a county key is present here, its value overrides the auto-calculated
    // bbox centroid. Omit a county to fall back to auto-calculation.
    //
    // tooltipOffsets  → shifts the entire pill (rect + text) by { dx, dy }
    //                   relative to the auto-centroid. Use this to nudge the
    //                   pill without disabling auto-sizing.
    //
    // tooltipAnchors  → fully overrides the centroid with an absolute SVG
    //                   coordinate { x, y }. The pill is still auto-sized
    //                   around the text at this fixed point.
    //
    // labelOffsets    → shifts the abbreviation <text> by { dx, dy } relative
    //                   to the auto-centroid.
    //
    // labelAnchors    → fully overrides the abbreviation label position with
    //                   an absolute SVG coordinate { x, y }.
    // ─────────────────────────────────────────────────────────────────────────

const tooltipOffsets = {
  pinellas:    { dx: -10, dy: -20 },
  hillsborough:{ dx:   0, dy:   0 },
  pasco:       { dx:   0, dy:   0 },
  hernando:    { dx:   0, dy:   0 },
  citrus:      { dx:   0, dy:   0 },
  sumter:      { dx:   0, dy:   0 },
  polk:        { dx:   0, dy:   0 },
  manatee:     { dx:   0, dy:   0 },
  hardee:      { dx:   0, dy:   0 },
  highlands:   { dx:   0, dy:   0 },
};

const tooltipAnchors = {
  pinellas:    { x: 80,  y: 490 },
  hillsborough:{ x: 238, y: 435 },
  pasco:       { x: 192, y: 340 },
  hernando:    { x: 192, y: 235 },
  citrus:      { x: 192, y: 120 },
  sumter:      { x: 315, y: 205 },
  polk:        { x: 435, y: 480 },
  manatee:     { x: 238, y: 655 },
  hardee:      { x: 420, y: 661 },
  highlands:   { x: 585, y: 718 },
};

const labelOffsets = {
  pinellas:    { dx: -10, dy: -20 },
  hillsborough:{ dx:   0, dy:   0 },
  pasco:       { dx:   0, dy:   0 },
  hernando:    { dx:   0, dy:   0 },
  citrus:      { dx:   0, dy:   0 },
  sumter:      { dx:   0, dy:   0 },
  polk:        { dx:   0, dy:   0 },
  manatee:     { dx:   0, dy:   0 },
  hardee:      { dx:   0, dy:   0 },
  highlands:   { dx:   0, dy:   0 },
};
const labelAnchors = {
  pinellas:    { x: 80,  y: 490 },
  hillsborough:{ x: 238, y: 435 },
  pasco:       { x: 192, y: 340 },
  hernando:    { x: 192, y: 235 },
  citrus:      { x: 192, y: 120 },
  sumter:      { x: 315, y: 205 },
  polk:        { x: 435, y: 480 },
  manatee:     { x: 238, y: 655 },
  hardee:      { x: 420, y: 661 },
  highlands:   { x: 580, y: 718 },
};

    // ─────────────────────────────────────────────────────────────────────────

    function resolveCenter(path, county, anchorMap, offsetMap) {
        if (anchorMap[county]) {
            return { cx: anchorMap[county].x, cy: anchorMap[county].y };
        }
        const bbox = path.getBBox();
        let cx = bbox.x + bbox.width  / 2;
        let cy = bbox.y + bbox.height / 2;
        if (offsetMap[county]) {
            cx += offsetMap[county].dx;
            cy += offsetMap[county].dy;
        }
        return { cx, cy };
    }

    function updateTooltip(name, county) {
        if (!tooltipRect || !tooltipText) return;

        const group = svg.querySelector(`.county-group[data-county="${county}"]`);
        const path  = group ? group.querySelector('.county-path') : null;
        if (!path) return;

        const { cx, cy } = resolveCenter(path, county, tooltipAnchors, tooltipOffsets);

        tooltipText.textContent = name;
        tooltipText.setAttribute('x', cx);
        tooltipText.setAttribute('y', cy);

        const tBbox  = tooltipText.getBBox();
        const rectW  = Math.ceil(tBbox.width)  + PAD_X * 2;
        const rectH  = Math.ceil(tBbox.height) + PAD_Y * 2;

        tooltipRect.setAttribute('x',      cx - rectW / 2);
        tooltipRect.setAttribute('y',      cy - rectH / 2);
        tooltipRect.setAttribute('width',  rectW);
        tooltipRect.setAttribute('height', rectH);
        tooltipRect.setAttribute('rx',     rectH / 2);
        tooltipText.setAttribute('y', cy);
    }

    function positionAbbrLabel(group) {
        const county = group.dataset.county;
        const label  = group.querySelector(`.county-abbr-label[data-for="${county}"]`);
        const path   = group.querySelector('.county-path');
        if (!label || !path) return;

        const { cx, cy } = resolveCenter(path, county, labelAnchors, labelOffsets);

        label.setAttribute('x', cx);
        label.setAttribute('y', cy);
        label.setAttribute('text-anchor',       'middle');
        label.setAttribute('dominant-baseline', 'central');
    }

    function setActiveCounty(group) {
        const county = group.dataset.county;
        const name   = group.dataset.name;
        const price  = group.dataset.price;

        svg.querySelectorAll('.county-group.county-active').forEach(g => g.classList.remove('county-active'));
        group.classList.add('county-active');

        svg.querySelectorAll('.county-abbr-label').forEach(label => {
            label.classList.toggle('county-abbr-hidden', label.dataset.for === county);
        });

        svg.appendChild(tooltipPill);
        updateTooltip(name, county);

        if (countyNameEl)  countyNameEl.textContent  = name;
        if (countyPriceEl) countyPriceEl.textContent = price;
    }

    function initAllLabels() {
        svg.querySelectorAll('.county-group').forEach(group => positionAbbrLabel(group));
    }

    svg.querySelectorAll('.county-group').forEach(group => {
        group.addEventListener('click', () => setActiveCounty(group));
    });

    requestAnimationFrame(() => {
        initAllLabels();
        const defaultGroup = svg.querySelector('.county-group.county-active');
        if (defaultGroup) setActiveCounty(defaultGroup);
    });
}