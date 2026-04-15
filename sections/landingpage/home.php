<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);

?>
<section id="home" class="relative overflow-hidden hash-section">
  <!-- layered backgrounds -->
  <div class="absolute inset-x-0 top-0 h-[700px] overflow-hidden">
    <div class="absolute inset-0 hero-grad-a"></div>
    <div class="absolute inset-0 hero-grad-b"></div>
    <div class="absolute bottom-0 inset-x-0 h-[3px] hero-underline"></div>
  </div>

  <!-- soft brand blobs/outlines driven by vars -->
  <div class="absolute top-20 right-10 w-96 h-96 rounded-full opacity-10 bg-ph-blue"></div>
  <div class="absolute bottom-10 left-10 w-72 h-72 rounded-full opacity-10 bg-ph-red"></div>
  <div class="absolute top-1/3 right-1/3 w-48 h-48 border-8 rounded-2xl" style="border-color: color-mix(in srgb, var(--ph-blue-light) 60%, white); opacity:.2;"></div>
  <div class="absolute bottom-1/3 left-1/4 w-32 h-32 border-8 rounded-full" style="border-color: color-mix(in srgb, var(--ph-red) 50%, white); opacity:.2;"></div>

  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid gap-12 items-center">
      <div class="space-y-8">
        <br>
        <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/15 border border-white/30">
          <i data-lucide="shield-check" class="text-white w-4 h-4"></i>
          <span class="text-white font-medium text-sm">Official Urdaneta City Portal</span>
        </div>

        <h1 class="text-4xl lg:text-6xl font-black text-white leading-tight drop-shadow-lg">
          Urdaneta Legislative <span class="text-ph-yellow">Information System</span>
        </h1>

        <p class="text-lg text-white/90 leading-relaxed max-w-3xl">
          Official portal for accessing legislation from the Senate and House of Representatives.
          Track bills, connect with your representatives, and participate in Philippine democracy.
        </p>

        <!-- Global Search -->
        <div class="bg-white/90 border-2 rounded-2xl p-4 shadow-lg relative z-10"
             style="border-color: color-mix(in srgb, var(--ph-blue) 25%, #c7d2fe);">
          <form id="globalSearchForm" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
              <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5"
                 style="color: var(--ph-blue);"></i>
              <input id="globalSearchInput" type="search"
                placeholder="Search bills, members, committees, resources… (Press / to focus)"
                class="w-full pl-10 pr-4 py-3 rounded-xl border focus:outline-none focus:ring-2 text-gray-800"
                style="border-color: color-mix(in srgb, var(--ph-blue) 30%, #dbeafe); box-shadow: 0 0 0 0 rgba(0,0,0,0);"/>
            </div>
            <select id="globalSearchScope"
              class="sm:w-56 w-full px-3 py-3 rounded-xl border focus:outline-none focus:ring-2 text-gray-800"
              style="border-color: color-mix(in srgb, var(--ph-blue) 30%, #dbeafe);">
              <option value="all" selected>All</option>
              <option value="legislation">Legislation</option>
              <option value="representatives">Representatives</option>
              <option value="committees">Committees</option>
              <option value="calendar">Calendar</option>
              <option value="schedules">Schedules</option>
              <option value="resources">Resources</option>
            </select>
            <button class="btn-primary px-6 py-3 rounded-xl font-semibold flex items-center gap-2">
              <i data-lucide="arrow-right" class="w-4 h-4"></i> Search
            </button>
          </form>
          <div id="globalSearchResults" class="mt-3 hidden">
            <div class="bg-white border rounded-xl divide-y" id="globalSearchResultsList"></div>
          </div>
        </div>

        <!-- AI Card -->
        <div class="pt-6 border-t-2" style="border-color: color-mix(in srgb, var(--ph-blue) 25%, #e5e7eb);">
          <div class="p-6 rounded-2xl border-2"
               style="background: linear-gradient(135deg, color-mix(in srgb, var(--ph-blue) 8%, #f8fafc), color-mix(in srgb, var(--ph-yellow) 10%, #fff)); border-color: color-mix(in srgb, var(--ph-blue) 25%, #c7d2fe);">
            <div class="flex items-start gap-4">
              <div class="icon-wrapper p-3 rounded-xl flex-shrink-0">
                <i data-lucide="sparkles" class="text-white w-6 h-6"></i>
              </div>
              <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-900 mb-2">AI Legislative Assistant</h3>
                <p class="text-gray-700 text-sm mb-4">
                  Generate draft resolutions and bills using artificial intelligence
                </p>
                <button onclick="openAIModal()" class="btn-primary px-6 py-3 rounded-xl font-semibold shadow-lg transition-all hover:shadow-xl flex items-center gap-2">
                  <i data-lucide="wand-2" class="w-5 h-5"></i>
                  Generate  Now
                </button>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /content -->
    </div>
  </div>
</section>
