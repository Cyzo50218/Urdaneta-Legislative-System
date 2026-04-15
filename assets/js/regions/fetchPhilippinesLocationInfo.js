const BASE_URL = 'https://psgc.gitlab.io/api';

async function apiFetch(endpoint) {
    const response = await fetch(`${BASE_URL}${endpoint}`);
    if (!response.ok) throw new Error(`API Error: ${response.statusText}`);
    const data = await response.json();
    return data.sort((a, b) => a.name.localeCompare(b.name));
}

export const GeoService = {
    getRegions: () => apiFetch('/regions/'),

    // Provinces belong to a Region
    getProvincesByRegion: (regionCode) => apiFetch(`/regions/${regionCode}/provinces/`),

    // Cities belong to a Province OR a Region 
    getCitiesByProvince: (provinceCode) => apiFetch(`/provinces/${provinceCode}/cities-municipalities/`),
    getCitiesByRegion: (regionCode) => apiFetch(`/regions/${regionCode}/cities-municipalities/`),

    // Barangays belong to a City/Municipality
    getBarangaysByCity: (cityCode) => apiFetch(`/cities-municipalities/${cityCode}/barangays/`),

    getRegionName: (code) => fetch(`${BASE_URL}/regions/${code}`).then(res => res.json()),
    getProvinceName: (code) => fetch(`${BASE_URL}/provinces/${code}`).then(res => res.json()),
    getCityName: (code) => fetch(`${BASE_URL}/cities-municipalities/${code}`).then(res => res.json()),
    getBarangayName: (code) => fetch(`${BASE_URL}/barangays/${code}`).then(res => res.json())
};