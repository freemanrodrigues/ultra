@extends('/layouts/master-layout')

@section('content')

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    /* Map container styling */
    .map-container {
        height: 65vh;
        width: 100%;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
    }
    
    #siteMap {
        height: 100%;
        width: 100%;
        border-radius: 0.5rem;
    }
    

    
    /* Custom marker popup styling */
    .custom-popup {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 12px;
        line-height: 1.4;
    }
    
    .custom-popup h6 {
        color: #1d4ed8;
        margin: 0 0 8px 0;
        font-size: 14px;
        font-weight: 600;
    }
    
    .custom-popup .info-row {
        margin: 4px 0;
        display: flex;
        align-items: center;
    }
    
    .custom-popup .info-label {
        font-weight: 600;
        color: #555;
        min-width: 80px;
        margin-right: 8px;
    }
    
    .custom-popup .info-value {
        color: #333;
    }
    
    .custom-popup .customer-list {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #eee;
    }
    
    .custom-popup .customer-item {
        background: #f8f9fa;
        padding: 4px 8px;
        margin: 2px 0;
        border-radius: 0.25rem;
        font-size: 11px;
    }
    

    
    /* Ensure single page view */
    .container-fluid {
        max-height: 100vh;
        overflow: hidden;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .map-container {
            height: 60vh;
        }
        

    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h4 mb-0">
            <i class="fas fa-map-marked-alt text-primary"></i> Site Master Map
            <small class="text-muted">({{ $siteMasters->total() }} total sites)</small>
    </h1>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
            @if(session('success')['text']) {{ session('success')['text'] }}<br> @endif
        @if(session('success')['link'])
        <a href="{{ session('success')['link'] }}" class="alert-link">
            {{ session('success')['link_text'] }}
        </a>.
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

    <!-- Map Controls -->
    <div class="card shadow-sm mb-2">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('site-masters.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by Site Name...">
            </div>
            <div class="col-md-2">
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('site-masters.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
            </div>
            <div class="col-md-2">
                    <a href="{{ route('site-masters.create') }}" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-plus"></i> Add New Site
                    </a>
            </div>
            <div class="col-md-2">
                    <a href="{{ route('site-masters.index') }}?view=list" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="fas fa-list"></i> List View
                    </a>
            </div>
        </form>
        </div>
    </div>

    <!-- Map Container -->
    <div class="card shadow">
        <div class="card-body p-0">
            <div class="map-container">
                <div id="siteMap"></div>
                

            </div>
        </div>
    </div>

    <!-- Site Statistics -->
    <div class="row mt-2">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center py-2">
                    <h6 class="card-title mb-1">{{ $mapSites->count() }}</h6>
                    <small class="card-text">Sites on Map</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center py-2">
                    <h6 class="card-title mb-1">{{ $sitesWithCustomers }}</h6>
                    <small class="card-text">Sites with Customers</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center py-2">
                    <h6 class="card-title mb-1">{{ $totalCustomerSites }}</h6>
                    <small class="card-text">Total Customer Sites</small>
        </div>
    </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center py-2">
                    <h6 class="card-title mb-1">{{ $sitesMissingCoordinates }}</h6>
                    <small class="card-text">Sites Missing Coordinates</small>
                </div>
            </div>
</div>
    </div>
</div>  

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map centered on India
    const map = L.map('siteMap').setView([20.5937, 78.9629], 6); // India center with better zoom
    
    // Add Google Maps tiles
    L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        attribution: '© Google Maps',
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);
    
    // Site data from controller
    const sites = @json($mapSites);
    
    // Create markers for each site
    const markers = [];
    const bounds = L.latLngBounds();
    let nearbyCustomerMarkers = []; // Store nearby customer site markers
    
    // Function to calculate distance between two points
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of the Earth in kilometers
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c; // Distance in kilometers
    }
    
    // Function to show nearby customer sites
    function showNearbyCustomerSites(selectedSite, map) {
        // Clear existing nearby customer markers
        nearbyCustomerMarkers.forEach(marker => map.removeLayer(marker));
        nearbyCustomerMarkers = [];
        
        // Find nearby customer sites within 5km radius
        const nearbySites = sites.filter(site => {
            if (site.id === selectedSite.id) return false; // Exclude the selected site itself
            
            const distance = calculateDistance(
                selectedSite.lat, selectedSite.long,
                site.lat, site.long
            );
            
            return distance <= 5; // Within 5km radius
        });
        
        // Create distinct icons for nearby customer sites
        nearbySites.forEach(site => {
            const hasCustomers = site.customer_site_masters && site.customer_site_masters.length > 0;
            
            // Create distinct blue icon for nearby customer sites
            const nearbyIcon = L.icon({
                iconUrl: 'data:image/svg+xml;base64,' + btoa(`
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#3b82f6" width="24" height="24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                `),
                iconSize: hasCustomers ? [35, 35] : [30, 30],
                iconAnchor: [hasCustomers ? 17.5 : 15, hasCustomers ? 35 : 30],
                popupAnchor: [0, hasCustomers ? -17.5 : -15],
                shadowUrl: 'data:image/svg+xml;base64,' + btoa(`
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000000" opacity="0.3" width="24" height="24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                `),
                shadowSize: hasCustomers ? [35, 35] : [30, 30],
                shadowAnchor: [hasCustomers ? 17.5 : 15, hasCustomers ? 35 : 30]
            });
            
            // Create marker for nearby site
            const nearbyMarker = L.marker([site.lat, site.long], {
                icon: nearbyIcon
            }).addTo(map);
            
            // Add popup with distance information
            const distance = calculateDistance(selectedSite.lat, selectedSite.long, site.lat, site.long);
            const popupContent = `
                <h6><i class="fas fa-building text-primary"></i> ${site.site_name}</h6>
                <div class="info-row">
                    <span class="info-label">Distance:</span>
                    <span class="info-value">${distance.toFixed(1)} km</span>
                </div>
                <div class="info-row">
                    <span class="info-label">City:</span>
                    <span class="info-value">${site.city || 'N/A'}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">State:</span>
                    <span class="info-value">${site.state_table ? site.state_table.statename : 'N/A'}</span>
                </div>
                ${site.customer_site_masters && site.customer_site_masters.length > 0 ? `
                    <div class="customer-list">
                        <strong>Customers (${site.customer_site_masters.length}):</strong>
                        ${site.customer_site_masters.map(cs => `
                            <div class="customer-item">${cs.customer.cus_company_name}</div>
                        `).join('')}
                    </div>
                ` : ''}
                <div class="mt-2">
                    <a href="/masters/site-masters/${site.id}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                </div>
            `;
            
            nearbyMarker.bindPopup(popupContent, {
                maxWidth: 300,
                className: 'custom-popup'
            });
            
            // Add tooltip
            nearbyMarker.bindTooltip(`${site.site_name} (${distance.toFixed(1)} km away)`, {
                permanent: false,
                direction: 'top',
                offset: [0, -10]
            });
            
            nearbyCustomerMarkers.push(nearbyMarker);
        });
        
        // Show info about nearby sites
        if (nearbySites.length > 0) {
            const infoDiv = document.createElement('div');
            infoDiv.className = 'alert alert-info alert-dismissible fade show';
            infoDiv.style.position = 'absolute';
            infoDiv.style.top = '10px';
            infoDiv.style.left = '10px';
            infoDiv.style.zIndex = '1000';
            infoDiv.style.maxWidth = '300px';
            infoDiv.innerHTML = `
                <strong><i class="fas fa-info-circle"></i> Nearby Sites Found</strong><br>
                ${nearbySites.length} site(s) within 5km radius<br>
                <small>Blue markers show nearby customer sites</small>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.getElementById('siteMap').parentNode.appendChild(infoDiv);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                if (infoDiv.parentNode) {
                    infoDiv.parentNode.removeChild(infoDiv);
                }
            }, 5000);
        }
    }
    
    // Show message if no sites with coordinates
    if (sites.length === 0) {
        const noSitesDiv = document.createElement('div');
        noSitesDiv.innerHTML = `
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                        background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                        text-align: center; z-index: 1000;">
                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Sites with Coordinates</h5>
                <p class="text-muted mb-3">Add coordinates to your sites to see them on the map.</p>
                <a href="{{ route('site-masters.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Site
                </a>
            </div>
        `;
        document.getElementById('siteMap').appendChild(noSitesDiv);
    }
    
    sites.forEach(site => {
        // Create custom popup content
        const popupContent = createPopupContent(site);
        
        // Determine marker size based on whether site has customers
        const hasCustomers = site.customer_site_masters && site.customer_site_masters.length > 0;
        const iconSize = hasCustomers ? [40, 40] : [35, 35];
        
        // Create custom red location icon
        const redLocationIcon = L.icon({
            iconUrl: 'data:image/svg+xml;base64,' + btoa(`
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ef4444" width="24" height="24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
            `),
            iconSize: iconSize,
            iconAnchor: [iconSize[0]/2, iconSize[1]],
            popupAnchor: [0, -iconSize[1]/2],
            shadowUrl: 'data:image/svg+xml;base64,' + btoa(`
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000000" opacity="0.3" width="24" height="24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
            `),
            shadowSize: iconSize,
            shadowAnchor: [iconSize[0]/2, iconSize[1]]
        });
        
        // Create marker with red location icon
        const marker = L.marker([site.lat, site.long], {
            icon: redLocationIcon
        }).addTo(map);
        
        // Add popup
        marker.bindPopup(popupContent, {
            maxWidth: 300,
            className: 'custom-popup'
        });
        
        // Add tooltip on hover
        marker.bindTooltip(site.site_name, {
            permanent: false,
            direction: 'top',
            offset: [0, -10]
        });
        
                // Add click event to zoom to site and show nearby customer sites
        marker.on('click', function() {
            // Zoom into the clicked site location
            map.setView([site.lat, site.long], 16);
            
            // Highlight selected marker by changing icon color to darker red
            const selectedIcon = L.icon({
                iconUrl: 'data:image/svg+xml;base64,' + btoa(`
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#dc2626" width="24" height="24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                `),
                iconSize: [50, 50], // Larger for selected site
                iconAnchor: [25, 50],
                popupAnchor: [0, -25],
                shadowUrl: 'data:image/svg+xml;base64,' + btoa(`
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000000" opacity="0.3" width="24" height="24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                `),
                shadowSize: [50, 50],
                shadowAnchor: [25, 50]
            });
            marker.setIcon(selectedIcon);
            
            // Add territory highlight circle
            const territoryCircle = L.circle([site.lat, site.long], {
                color: '#dc2626',
                fillColor: '#dc2626',
                fillOpacity: 0.1,
                radius: 2000 // 2km radius
            }).addTo(map);
            
            // Store reference to remove later
            marker.territoryCircle = territoryCircle;
            
            // Find and show nearby customer sites
            showNearbyCustomerSites(site, map);
            
            // Reset other markers to original red color
            markers.forEach((m, index) => {
                if (m !== marker) {
                    // Remove any existing territory circles
                    if (m.territoryCircle) {
                        map.removeLayer(m.territoryCircle);
                        m.territoryCircle = null;
                    }
                    
                    const originalIcon = L.icon({
                        iconUrl: 'data:image/svg+xml;base64,' + btoa(`
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ef4444" width="24" height="24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                        `),
                        iconSize: sites[index].customer_site_masters && sites[index].customer_site_masters.length > 0 ? [40, 40] : [35, 35],
                        iconAnchor: [sites[index].customer_site_masters && sites[index].customer_site_masters.length > 0 ? 20 : 17.5, sites[index].customer_site_masters && sites[index].customer_site_masters.length > 0 ? 40 : 35],
                        popupAnchor: [0, sites[index].customer_site_masters && sites[index].customer_site_masters.length > 0 ? -20 : -17.5],
                        shadowUrl: 'data:image/svg+xml;base64,' + btoa(`
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000000" opacity="0.3" width="24" height="24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                        `),
                        shadowSize: sites[index].customer_site_masters && sites[index].customer_site_masters.length > 0 ? [40, 40] : [35, 35],
                        shadowAnchor: [sites[index].customer_site_masters && sites[index].customer_site_masters.length > 0 ? 20 : 17.5, sites[index].customer_site_masters && sites[index].customer_site_masters.length > 0 ? 40 : 35]
                    });
                    m.setIcon(originalIcon);
                }
            });
        });
        
        // Add hover event to show site info
        marker.on('mouseover', function() {
            // Make icon slightly larger on hover
            const hoverIcon = L.icon({
                iconUrl: 'data:image/svg+xml;base64,' + btoa(`
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ef4444" width="24" height="24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                `),
                                 iconSize: hasCustomers ? [45, 45] : [40, 40],
                 iconAnchor: [hasCustomers ? 22.5 : 20, hasCustomers ? 45 : 40],
                 popupAnchor: [0, hasCustomers ? -22.5 : -20],
                shadowUrl: 'data:image/svg+xml;base64,' + btoa(`
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000000" opacity="0.3" width="24" height="24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                `),
                                 shadowSize: hasCustomers ? [45, 45] : [40, 40],
                 shadowAnchor: [hasCustomers ? 22.5 : 20, hasCustomers ? 45 : 40]
            });
            marker.setIcon(hoverIcon);
        });
        
        marker.on('mouseout', function() {
            // Reset to original size
            marker.setIcon(redLocationIcon);
        });
        
        markers.push(marker);
        bounds.extend([site.lat, site.long]);
    });
    
    // Fit map to show all markers if there are any
    if (bounds.isValid() && sites.length > 0) {
        map.fitBounds(bounds, { padding: [20, 20] });
    } else if (sites.length === 0) {
        // If no sites, keep the default India view
        map.setView([20.5937, 78.9629], 6);
    }
    
    // Function to create popup content
    function createPopupContent(site) {
        let content = `
            <div class="custom-popup">
                <h6><i class="fas fa-building"></i> ${site.site_name}</h6>
                <div class="info-row">
                    <span class="info-label">Address:</span>
                    <span class="info-value">${site.address || 'N/A'}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">City:</span>
                    <span class="info-value">${site.city || 'N/A'}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">State:</span>
                    <span class="info-value">${site.state_table ? site.state_table.statename : 'N/A'}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Country:</span>
                    <span class="info-value">${site.country || 'N/A'}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Coordinates:</span>
                    <span class="info-value">${site.lat.toFixed(6)}, ${site.long.toFixed(6)}</span>
                </div>
        `;
        
        // Add customer information if available
        if (site.customer_site_masters && site.customer_site_masters.length > 0) {
            content += `
                <div class="customer-list">
                    <div class="info-label">Customers:</div>
                    ${site.customer_site_masters.map(customerSite => `
                        <div class="customer-item">
                            <i class="fas fa-user"></i> ${customerSite.customer ? customerSite.customer.customer_name : 'N/A'}
                            ${customerSite.site_customer_name ? ` - ${customerSite.site_customer_name}` : ''}
                        </div>
                    `).join('')}
                </div>
            `;
        }
        
                 content += `
                 <div class="mt-2">
                     <a href="/masters/site-masters/${site.id}" class="btn btn-sm btn-outline-primary">
                         <i class="fas fa-eye"></i> View Details
                     </a>
                 </div>
             </div>
         `;
        
        return content;
    }
    

    
    // Search functionality
    document.getElementById('search').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        markers.forEach((marker, index) => {
            const site = sites[index];
            const isVisible = site.site_name.toLowerCase().includes(searchTerm) ||
                            (site.city && site.city.toLowerCase().includes(searchTerm)) ||
                            (site.state_table && site.state_table.statename.toLowerCase().includes(searchTerm));
            
            if (isVisible) {
                marker.addTo(map);
            } else {
                map.removeLayer(marker);
            }
        });
    });
    
    // Status filter functionality
    document.getElementById('status').addEventListener('change', function(e) {
        const status = e.target.value;
        
        markers.forEach((marker, index) => {
            const site = sites[index];
            const isVisible = !status || site.status.toString() === status;
            
            if (isVisible) {
                marker.addTo(map);
            } else {
                map.removeLayer(marker);
            }
        });
    });
});
</script>

@endsection
