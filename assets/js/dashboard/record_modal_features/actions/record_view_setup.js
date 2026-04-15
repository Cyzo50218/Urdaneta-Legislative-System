function openGridSetup(recordId) {
    const targetRecordId = recordId || currentRecordId;
    if (!targetRecordId) {
        console.warn("openGridSetup: no record id provided and currentRecordId is empty.");
        return;
    }

    const modal = document.getElementById("gridSetupModal");
    if (!modal) return;

    activeGridSetupRecordId = targetRecordId;

    let cfg = recordGridSetupById[targetRecordId];
    if (!cfg) {
        cfg = createDefaultGridConfig();
        recordGridSetupById[targetRecordId] = cfg;
    }

    const sections = cfg.sections;
    const basic = cfg.basicFields;
    const legis = cfg.legislationFields;
    const support = cfg.supportFields;

    // Sections
    modal.querySelector('input[name="sections.basic"]').checked = !!sections.basic;
    modal.querySelector('input[name="sections.legislation"]').checked = !!sections.legislation;
    modal.querySelector('input[name="sections.support"]').checked = !!sections.support;

    // Basic fields
    modal.querySelector('input[name="basic.trackingNo"]').checked = !!basic.trackingNo;
    modal.querySelector('input[name="basic.type"]').checked = !!basic.type;
    modal.querySelector('input[name="basic.author"]').checked = !!basic.author;
    modal.querySelector('input[name="basic.status"]').checked = !!basic.status;
    modal.querySelector('input[name="basic.deadline"]').checked = !!basic.deadline;

    // Legislation fields
    modal.querySelector('input[name="legis.category"]').checked = !!legis.category;
    modal.querySelector('input[name="legis.sessionType"]').checked = !!legis.sessionType;
    modal.querySelector('input[name="legis.yearTaken"]').checked = !!legis.yearTaken;
    modal.querySelector('input[name="legis.agendaNo"]').checked = !!legis.agendaNo;
    modal.querySelector('input[name="legis.committee"]').checked = !!legis.committee;

    // Support fields
    modal.querySelector('input[name="support.hyperlink"]').checked = !!support.hyperlink;
    modal.querySelector('input[name="support.attachments"]').checked = !!support.attachments;

    closeRecordDialogAndOpenOtherDialog();

    modal.classList.add("show");
}

function closeGridSetupModal() {
    const modal = document.getElementById("gridSetupModal");
    if (!modal) return;
    modal.classList.remove("show");
}
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("gridSetupModal");
    if (!modal) return;

    const btnSave = document.getElementById("gridSetupSaveBtn");
    const btnCancel = document.getElementById("gridSetupCancelBtn");
    const btnClose = document.getElementById("gridSetupCloseBtn");

    btnSave.addEventListener("click", () => {
        const modal = document.getElementById("gridSetupModal");

        if (!activeGridSetupRecordId) {
            console.warn("Grid setup save: no active record id.");
            closeGridSetupModal();
            return;
        }
        let cfg = recordGridSetupById[activeGridSetupRecordId];
        if (!cfg) {
            cfg = createDefaultGridConfig();
            recordGridSetupById[activeGridSetupRecordId] = cfg;
        }

        const sections = cfg.sections;
        const basic = cfg.basicFields;
        const legis = cfg.legislationFields;
        const support = cfg.supportFields;

        // Sections
        sections.basic = modal.querySelector('input[name="sections.basic"]').checked;
        sections.legislation = modal.querySelector('input[name="sections.legislation"]').checked;
        sections.support = modal.querySelector('input[name="sections.support"]').checked;

        // Basic fields
        basic.trackingNo = modal.querySelector('input[name="basic.trackingNo"]').checked;
        basic.type = modal.querySelector('input[name="basic.type"]').checked;
        basic.author = modal.querySelector('input[name="basic.author"]').checked;
        basic.status = modal.querySelector('input[name="basic.status"]').checked;
        basic.deadline = modal.querySelector('input[name="basic.deadline"]').checked;

        // Legislation fields
        legis.category = modal.querySelector('input[name="legis.category"]').checked;
        legis.sessionType = modal.querySelector('input[name="legis.sessionType"]').checked;
        legis.yearTaken = modal.querySelector('input[name="legis.yearTaken"]').checked;
        legis.agendaNo = modal.querySelector('input[name="legis.agendaNo"]').checked;
        legis.committee = modal.querySelector('input[name="legis.committee"]').checked;

        // Support fields
        support.hyperlink = modal.querySelector('input[name="support.hyperlink"]').checked;
        support.attachments = modal.querySelector('input[name="support.attachments"]').checked;

        if (typeof openRecordDialog === "function") {
            openRecordDialog(activeGridSetupRecordId);
        }

        closeGridSetupModal();
    });
    const closeHandler = () => {
        const id = currentRecordId;
        closeGridSetupModal();
        if (id) {
            setTimeout(() => openRecordDialog(id), 10);
        }
    };

    btnCancel.addEventListener("click", closeHandler);
    btnClose.addEventListener("click", closeHandler);

    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            const id = currentRecordId;
            closeGridSetupModal();

            if (id) {
                setTimeout(() => openRecordDialog(id), 10);
            }
        }
    });

});

function openRecordOptions() {
    const modal = document.getElementById("recordOptionsModal");
    if (!modal) return;
    closeRecordDialogAndOpenOtherDialog();

    const layoutSelect = document.getElementById("recordPrintLayoutSelect");
    const legisLayoutSelect = document.getElementById("recordLegisLayoutSelect");
    const subtitleToggle = document.getElementById("recordLegisSubtitleToggle");

    layoutSelect.value = optionsState.printLayout || "full";
    legisLayoutSelect.value = optionsState.legisLayout || "detailed";
    subtitleToggle.checked = optionsState.legisShowSubtitle !== false;

    modal.classList.add("show");
}

document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("recordOptionsModal");
    if (!modal) return;

    const btnSave = document.getElementById("recordOptionsSaveBtn");
    const btnCancel = document.getElementById("recordOptionsCancelBtn");
    const btnClose = document.getElementById("recordOptionsCloseBtn");
    const layoutSelect = document.getElementById("recordPrintLayoutSelect");
    const legisLayoutSelect = document.getElementById("recordLegisLayoutSelect");
    const subtitleToggle = document.getElementById("recordLegisSubtitleToggle");

    const allowedLayouts = ["full", "summary", "card"];
    const allowedLegisLayouts = ["detailed", "compact"];

    const closeOptionsModal = () => {
        openRecordDialog(currentRecordId);

        modal.classList.remove("show");

    };

    btnCancel.addEventListener("click", closeOptionsModal);
    btnClose.addEventListener("click", closeOptionsModal);
    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            closeOptionsModal();
        }
    });

    btnSave.addEventListener("click", () => {
        let layout = layoutSelect.value;
        if (!allowedLayouts.includes(layout)) {
            layout = "full";
        }

        let legisLayout = legisLayoutSelect.value;
        if (!allowedLegisLayouts.includes(legisLayout)) {
            legisLayout = "detailed";
        }

        optionsState.printLayout = layout;
        optionsState.legisLayout = legisLayout;
        optionsState.legisShowSubtitle = !!subtitleToggle.checked;
        if (currentRecordId) {
            console.log("empty");
            openRecordDialog(currentRecordId);
        }

        closeOptionsModal();
    });
});

