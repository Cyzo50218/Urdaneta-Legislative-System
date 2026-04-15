// --- STATE ---
let pageSections = [];
let rawDataStore = [];

let sectionKeyToDelete = null;
let isMobile = window.innerWidth < 900;
let pageEmptySectionData = [{
    title: "Local Government Code of the Philippines",
    text: `
This section currently has no content available. 
The Local Government Code of 1991 establishes the framework for local governance in the Philippines, 
empowering local government units to manage their own affairs, provide basic services, 
and promote the welfare of their constituents.

Further provisions and chapters will be loaded here once available.
  `
},
{
    title: "",
    text: ""
},
];

// --- POLLING STATE ---
let snapshotPollInterval = null;
const POLL_DURATION = 30000;

function startSnapshotPolling() {
    if (snapshotPollInterval) clearInterval(snapshotPollInterval);

    snapshotPollInterval = setInterval(async () => {
        const editModal = document.getElementById('editSectionModal');
        const isEditing = editModal && editModal.style.display === 'flex';
        if (!isEditing) {
            await loadPageSections();
        } else {
            console.log("Sync paused: User is editing.");
        }
    }, POLL_DURATION);
}
function stopSnapshotPolling() {
    if (snapshotPollInterval) {
        clearInterval(snapshotPollInterval);
        snapshotPollInterval = null;
    }
}
async function loadPageSections() {
    try {
        const magazine = $("#magazine");
        const currentPage = magazine.turn("page");

        const response = await fetch('/../../api/govcodes/fetchPageSections.php');
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            const secureData = result.data.map(section => ({
                title: section.title,
                content: section.content
            }));
            pageSections = secureData;
            rawDataStore = result.data;
            generateMagazinePages();
            renderSectionsTable(result.data);
            const totalPages = magazine.turn("pages");
            const targetPage = Math.min(currentPage, totalPages);
            magazine.turn("page", targetPage);

        } else {
            rawDataStore = [];
            pageSections = pageEmptySectionData;
            generateMagazinePages();
            renderSectionsTable([]);
        }
    } catch (error) {
        console.error("Snapshot synchronization failure:", error);
    }
}

function splitIntoPages(htmlContent, limit = 1000) {
    if (!htmlContent) return [];
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = htmlContent;

    const pages = [];
    let currentPageHtml = "";
    let currentLength = 0;
    tempDiv.childNodes.forEach((node) => {
        const nodeHtml = node.nodeType === Node.ELEMENT_NODE ? node.outerHTML : node.textContent;
        const nodeLength = node.textContent.length;
        if (currentLength + nodeLength > limit && currentPageHtml !== "") {
            pages.push(currentPageHtml);
            currentPageHtml = nodeHtml;
            currentLength = nodeLength;
        } else {
            currentPageHtml += nodeHtml;
            currentLength += nodeLength;
        }
    });
    if (currentPageHtml.trim() !== "") {
        pages.push(currentPageHtml);
    }

    return pages;
}

function generateMagazinePages() {
    const magazine = $("#magazine");
    const wasAtEnd = magazine.turn("page") === magazine.turn("pages");
    magazine.turn("stop");
    magazine.turn("page", 1).turn("stop");
    const endOfDocumentHtml = `
    <div class="back-cover" style="background:#2e3f5a; color:white; display:flex; align-items:center; justify-content:center; font-size:30px; font-weight:bold; width:400px; height:550px;">
        <div style="padding: 40px;">END OF DOCUMENT</div>
    </div>`;
    const blankPageHtml = `<div class="dynamic-page" style="background: white; width: 400px; height: 550px;"></div>`;
    const totalPagesBefore = magazine.turn("pages");
    for (let i = totalPagesBefore; i > 1; i--) {
        if (magazine.turn("hasPage", i)) {
            magazine.turn("removePage", i);
        }
    }
    const sectionsToRender = (!pageSections || pageSections.length === 0)
        ? pageEmptySectionData
        : pageSections;

    sectionsToRender.forEach((section) => {
        const htmlContent = section.content || section.text || "";
        const pages = htmlContent ? splitIntoPages(htmlContent, 1000) : [];

        const createAndAdd = (content) => {
            const pageElement = $(`
    <div class="dynamic-page" style="background: white; width: 400px; height: 550px; font-family: 'Times New Roman', Times, serif;">
      <div style="border: 1px solid #ddd; margin: 15px; height: calc(100% - 30px); position: relative;">
        <div style="text-align: center; border-bottom: 1px double #999; margin: 10px 15px; padding-bottom: 5px;">
          <span style="text-transform: uppercase; font-size: 10px; letter-spacing: 1px; color: #555;">
            Local Government Code of the Philippines
          </span>
        </div>
        <div class="legal-content" style="padding: 10px 20px; font-size: 13px; line-height: 1.5; color: #222;">
          ${content}
        </div>
        <div style="position: absolute; bottom: 10px; width: 100%; text-align: center; font-size: 10px; color: #777;">
          <span class="page-number-display"></span>
        </div>
      </div>
    </div>`);
            magazine.turn("addPage", pageElement);
        };

        if (pages.length === 0) {
            createAndAdd(section.title ? `<h2 style="margin-top:0; font-size: 18px;">${section.title}</h2>` : "");
        } else {
            pages.forEach((chunk, index) => {
                const displayTitle = (index === 0 && section.title)
                    ? `<h2 style="margin-top:0; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 10px;">${section.title}</h2>`
                    : "";
                createAndAdd(`${displayTitle}<div>${chunk}</div>`);
            });
        }
    });
    if (magazine.turn("pages") % 2 === 0) {
        magazine.turn("addPage", $(blankPageHtml));
    }
    magazine.turn("addPage", $(endOfDocumentHtml));
    const finalTotal = magazine.turn("pages");

    if (wasAtEnd) {
        setTimeout(() => {
            magazine.turn("page", finalTotal);
        }, 300);
    }
}

window.initMagazineEngine = function () {
    const magazine = $("#magazine");
    if (magazine.length === 0) {
        return;
    }
    if (magazine.data('done')) return;

    function getSettings() {
        return {
            display: isMobile ? "single" : "double",
            width: isMobile ? window.innerWidth * 0.9 : 800,
            height: isMobile ? (window.innerWidth * 0.9) * 1.3 : 550,
            autoCenter: true
        };
    }

    const settings = getSettings();
    magazine.turn({
        display: settings.display,
        width: settings.width,
        height: settings.height,
        autoCenter: true,
        elevation: 50,
        gradients: true,
        acceleration: true
    });

    magazine.data('done', true);
    loadPageSections();
    startSnapshotPolling();
    $(window).off("resize.turnjs").on("resize.turnjs", function () {
        const s = getSettings();
        if (magazine.turn("is")) {
            magazine.turn("display", s.display);
            magazine.turn("size", s.width, s.height);
            magazine.turn("center");
        }
    });
};

function renderSectionsTable(data) {
    const tableBody = document.getElementById('sectionsTableBody');
    if (!tableBody) return;
    tableBody.innerHTML = '';
    data.forEach((section) => {
        const isTampered = section.tampered === true;

        const row = document.createElement('tr');
        row.className = "hover:bg-slate-50 transition-colors border-b border-slate-100";

        row.innerHTML = `
            <td class="p-3 font-medium text-slate-700">
                ${section.title}
                <div class="text-[10px] text-slate-400 font-mono">${section.section_key}</div>
            </td>
            <td class="p-3 text-center">
                <span class="px-2 py-1 rounded-full text-[11px] font-bold ${isTampered ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'}">
                    ${isTampered ? 'TAMPERED' : 'VERIFIED'}
                </span>
            </td>
            <td class="p-3 text-right">
                <div class="flex justify-end gap-2">
                    <button onclick="editGovSection('${section.section_key}')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Edit">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </button>
                    <button onclick="deleteGovSection('${section.section_key}')" class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </td>
        `;
        tableBody.appendChild(row);
    });
    if (window.lucide) lucide.createIcons();
}