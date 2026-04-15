<div id="aiModal" class="modal">
    <div class="modal-content">
        <div class="p-6 border-b border-gray-200" style="background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-blue-light) 100%);">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="wand-2" class="text-white w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white">AI Legislative Assistant</h2>
                        <p class="text-blue-100 text-sm">Generate LGU Ordinances & Resolutions</p>
                    </div>
                </div>
                <button onclick="closeAIModal()" class="text-white hover:text-blue-100 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <div>
                <label for="billType" class="block text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i data-lucide="folder" class="w-4 h-4" style="color: var(--ph-blue);"></i>
                    Legislative Document Type
                </label>
                <select id="billType" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-600 transition-colors">
                    <option value="ordinance">LGU Ordinance</option>
                    <option value="resolution">LGU Resolution</option>
                </select>
            </div>

            <div>
                <label for="topic" class="block text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i data-lucide="file-edit" class="w-4 h-4" style="color: var(--ph-blue);"></i>
                    Subject Matter / Topic
                </label>
                <textarea id="topic" rows="4"
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-600 transition-colors"
                    placeholder="Example: An Act promoting renewable energy development in rural areas and providing incentives for solar panel installation"></textarea>
                <p class="text-xs text-gray-500 mt-2">Be specific about the legislative purpose and scope</p>
            </div>

            <div id="generationControls">
                <button id="generateBtn" onclick="generateBill()"
                    class="w-full text-white px-6 py-4 rounded-xl font-bold shadow-lg transition-all hover:shadow-xl flex items-center justify-center gap-3"
                    style="background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-red) 100%);">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                    Generate Legislative Draft
                </button>
                <div id="stopContainer" style="display: none;" class="space-y-3">
                    <div class="flex items-center justify-center gap-3 font-bold animate-pulse"
                        style="color: var(--ph-blue);">
                        <div class="spinner-custom"></div>
                        <span>Generating Your Draft...</span>
                    </div>
                    <button id="stopBtn" onclick="stopGenerating()"
                        class="w-full bg-red-100 text-red-600 border-2 border-red-200 px-6 py-3 rounded-xl font-bold hover:bg-red-200 transition-all flex items-center justify-center gap-3">
                        <i data-lucide="square" class="w-4 h-4 fill-current"></i>
                        Stop Generating
                    </button>
                </div>
            </div>

            <div id="generatedBillContainer" style="display: none;" class="space-y-4">
                <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $isLoggedIn = isset($_SESSION['user_id']);
                ?>

                <div id="billGeneratedActionsContainer"
                    class="flex items-center justify-between p-4 rounded-xl"
                    style="display: none; background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-blue-light) 100%);">

                    <div class="flex items-center gap-2 text-white">
                        <i data-lucide="file-check" class="w-5 h-5"></i>
                        <span class="font-bold">Generated Document</span>
                    </div>

                    <div class="flex gap-2">
                        <?php if ($isLoggedIn): ?>
                            <button id="uploadBtn" onclick="importAIGeneratedToRecord(event)" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                <span class="btn-text">Upload to Dashboard</span>
                            </button>
                        <?php else: ?>
                            <a href="/pages/loginportal" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                <i data-lucide="lock" class="w-4 h-4"></i> Login to Upload
                            </a>
                        <?php endif; ?>

                        <button onclick="downloadBill()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i> Download
                        </button>
                    </div>
                </div>
                <div class="bg-gray-50 border-2 rounded-xl p-6" style="border-color: var(--ph-blue);">
                    <pre id="generatedBill" class="whitespace-pre-wrap font-mono text-sm text-gray-900"></pre>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-yellow-900 mb-1">Important Legal Notice</p>
                            <p class="text-sm text-yellow-800">
                                This is an AI-generated draft for reference only. All official legislation must be reviewed by legal counsel
                                and submitted through proper congressional procedures as outlined in the Rules of the House/Senate.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="assistantModal" class="modal">
    <div class="modal-content max-w-3xl w-full overflow-hidden">
        <!-- Header -->
        <div class="px-5 py-4 flex items-center justify-between"
            style="background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-blue-light) 100%);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i data-lucide="bot" class="text-white w-5 h-5"></i>
                </div>
                <div>
                    <div class="text-white font-bold leading-tight">AI Legislative Assistant</div>
                    <div class="text-white/80 text-xs">Free demo — not official legal advice</div>
                </div>
            </div>
            <button class="text-white hover:text-blue-100" onclick="closeAssistantModal()" aria-label="Close">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-4 bg-white">
            <!-- Suggested prompts -->
            <div class="flex flex-wrap gap-2 mb-3">
                <button class="px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50" data-ai-suggest>
                    How does a bill become a law in PH?
                </button>
                <button class="px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50" data-ai-suggest>
                    What does “in committee” mean?
                </button>
                <button class="px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50" data-ai-suggest>
                    Where to see schedules this week?
                </button>
                <button class="px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50" data-ai-suggest>
                    Explain SBN vs HBN
                </button>
                <button class="px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50" data-ai-explain-site>
                    Explain this website
                </button>

            </div>

            <!-- Chat area -->
            <div id="assistantChat"
                class="h-[52vh] sm:h-[56vh] overflow-y-auto bg-gray-50 border rounded-xl p-4 space-y-3">
                <!-- Welcome bubble -->
                <div class="max-w-[85%] rounded-2xl px-4 py-3 text-sm bg-blue-50 border border-blue-200 text-blue-900">
                    Hi! I can help explain the legislative process, statuses, and where to find resources.
                    Ask me anything about the Philippine Congress, bills, committees, or schedules.
                </div>
            </div>

            <!-- Input -->
            <form id="assistantForm" class="mt-3 flex items-end gap-2">
                <div class="relative flex-1">
                    <textarea id="assistantInput" rows="2"
                        class="w-full rounded-xl border-2 border-gray-300 focus:outline-none focus:border-blue-600 p-3 pr-10 text-sm"
                        placeholder="Type your question (e.g., What happens after third reading?)"></textarea>
                    <i data-lucide="message-square" class="absolute right-3 top-3.5 w-5 h-5 text-gray-400"></i>
                </div>
                <button class="btn-primary text-white px-4 py-2 rounded-lg inline-flex items-center gap-2" type="submit">
                    <i data-lucide="send" class="w-4 h-4"></i> Send
                </button>
            </form>
        </div>
    </div>
</div>