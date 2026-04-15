let assignedItems = [];
const recordState = {};
async function fetchMyAssignedOrdinance() {
    try {
        const response = await fetch('/api/fetchMyAssignedOrdinancesList.php', {
            method: 'GET',
            headers: { 'Cache-Control': 'no-cache' }
        });

        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const result = await response.json();
        if (result.success && Array.isArray(result.data)) {
            assignedItems = result.data;
            result.data.forEach(item => {
                recordState[item.trackingNo] = {
                    ...item,
                    attachments: recordState[item.trackingNo]?.attachments || item.attachments || [],
                    hyperlink: recordState[item.trackingNo]?.hyperlink || item.hyperlink || "",
                    additional_fields: item.additional_fields || []
                };
            });
            applyRecordOverridesFromStorage();
            renderAssignedTable(result.data);
            applyAssignedFilters();

        } else {
            renderAssignedTable([]);
        }
    } catch (error) {
        console.error("Pooling Error:", error);
    } finally {
        setTimeout(fetchMyAssignedOrdinance, 5000);
    }
}

const RECORD_STORAGE_KEY = "lis_recordStateOverrides_v1";

function persistRecordStateToStorage() {
    try {
        const payload = {};

        Object.values(recordState).forEach((rec) => {
            payload[rec.trackingNo] = {
                hyperlink: rec.hyperlink || "",
                attachments: Array.isArray(rec.attachments) ? rec.attachments : [],
            };
        });

        localStorage.setItem(RECORD_STORAGE_KEY, JSON.stringify(payload));
    } catch (e) {
        console.warn("Failed to persist record state:", e);
    }
}

function applyRecordOverridesFromStorage() {
    try {
        const raw = localStorage.getItem(RECORD_STORAGE_KEY);
        if (!raw) return;

        const overrides = JSON.parse(raw);

        Object.keys(overrides).forEach((trackingNo) => {
            const rec = recordState[trackingNo];
            const o = overrides[trackingNo];
            if (!rec || !o) return;

            if (typeof o.hyperlink === "string") {
                rec.hyperlink = o.hyperlink;
            }

            if (Array.isArray(o.attachments)) {
                rec.attachments = o.attachments;
            }
        });
    } catch (e) {
        console.warn("Failed to apply record overrides:", e);
    }
}

assignedItems.forEach((item) => {
    recordState[item.trackingNo] = {
        ...item,
        hyperlink: item.hyperlink || "",
        locked: !!item.locked,
        attachments: Array.isArray(item.attachments) ? [...item.attachments] : [],
        notes: "",
        original: null,
    };
    recordState[item.trackingNo].original = { ...recordState[item.trackingNo] };
});
applyRecordOverridesFromStorage();

const optionsState = {
    printLayout: "full",
    legisLayout: "detailed",
    legisShowSubtitle: true,
};

const gridConfig = {
    visibleColumns: {
        trackingNo: true,
        title: true,
        type: true,
        author: true,
        status: true,
        deadline: true,
        actions: true,
    },
};

let currentSearchTerm = "";

const ROLE = {
    STAFF: "staff",
    COUNCILOR: "councilor",
    MAYOR: "mayor",
};

const recordFeatureButtons = [
    {
        id: "setupGrid",
        label: "Setup Grid",
        icon: "layout-template",
        handler: "openGridSetup",
        roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    },
    {
        id: "setHyperlink",
        label: "Set HyperLINK",
        icon: "link-2",
        handler: "openSetHyperlink",
        roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    },
    {
        id: "readPrint",
        label: "Read / Print",
        icon: "printer",
        handler: "readPrintRecord",
        roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    },
    {
        id: "loadFile",
        label: "Load File",
        icon: "file-up",
        handler: "loadFileForRecord",
        roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    },
    {
        id: "lockRecord",
        label: "Lock",
        icon: "lock",
        handler: "lockRecord",
        roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    },
    {
        id: "options",
        label: "Options",
        icon: "settings-2",
        handler: "openRecordOptions",
        roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    },

    // Middle row / record editing
    {
        id: "mainData",
        label: "Main Data",
        icon: "file-pen-line",
        handler: "openMainDataEditor",
        roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    },
    {
        id: "addRecord",
        label: "Add",
        icon: "file-plus-2",
        handler: "addRecord",
        roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    },
    {
        id: "editRecord",
        label: "Edit",
        icon: "file-edit",
        handler: "editRecord",
        roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    },
    {
        id: "deleteRecord",
        label: "Delete",
        icon: "trash-2",
        handler: "deleteRecord",
        roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    },
    {
        id: "searchMode",
        label: "Search",
        icon: "search",
        handler: "openSearchMode",
        roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    },
    //{
    //    id: "transferRecord",
    //    label: "Transfer",
    //    icon: "move-right",
    //    handler: "transferRecord",
    //    roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    //},
    //{
    //    id: "resetChanges",
    //    label: "Reset",
    //    icon: "rotate-ccw",
    //    handler: "resetRecordChanges",
    //    roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    //},
    {
        id: "endModule",
        label: "End",
        icon: "door-open",
        handler: "endRecordModule",
        roles: [ROLE.STAFF, ROLE.COUNCILOR, ROLE.MAYOR],
    },
];
const recordDialogConfig = {
    sections: {
        basic: true,
        legislation: true,
        support: true,
    },
    basicFields: {
        trackingNo: true,
        type: true,
        author: true,
        status: true,
        deadline: true,
    },
    legislationFields: {
        category: true,
        sessionType: true,
        yearTaken: true,
        agendaNo: true,
        committee: true,
    },
    supportFields: {
        hyperlink: true,
        attachments: true,
    },
};

const calendarState = {
    currentDate: new Date(),
};

let currentRecordId = null;
let filteredAssignedItems = [];
let currentUsedAssignedIds = [];
const localAttachmentStore = {};
let pendingDeleteRecordId = null;
let highlightTimer = null;

const recordGridSetupById = {};
let activeGridSetupRecordId = null;
function createDefaultGridConfig() {
    return JSON.parse(JSON.stringify(recordDialogConfig));
}
function getGridConfigForRecord(trackingNo) {
    return recordGridSetupById[trackingNo] || recordDialogConfig;
}
