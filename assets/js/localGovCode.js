// --- STATE ---
let pageSections = [];

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

async function loadPageSections() {
  try {
    const response = await fetch('/api/govcodes/fetchPageSections.php');
    const result = await response.json();

    if (result.success && result.data.length > 0) {
      const secureData = result.data.map(section => ({
        title: section.title,
        text: section.content
      }));

      pageSections = secureData;

      console.log("Urdaneta City 2026 Codes Verified.");
      generateMagazinePages();
    } else {
      pageSections = pageEmptySectionData;
      generateMagazinePages();
    }
  } catch (error) {
    console.error("Critical failure:", error);
  }
}

// Start secure fetch
loadPageSections();

function splitIntoPages(text, limit = 120) {
  const words = text.trim().split(/\s+/);
  const pages = [];

  for (let i = 0; i < words.length; i += limit) {
    pages.push(words.slice(i, i + limit).join(" "));
  }
  return pages;
}

function generateMagazinePages() {
  const magazine = $("#magazine");

  // 1. Capture state
  const wasAtEnd = magazine.turn("page") === magazine.turn("pages");

  // 2. STOP ALL ANIMATIONS IMMEDIATELY
  // This kills any active "folds" or "flips" that would cause the 'opts' error
  magazine.turn("stop");

  // Jump to page 1 INSTANTLY (without animation)
  // This keeps the engine from trying to animate while we delete
  magazine.turn("page", 1).turn("stop");

  // Templates
  const endOfDocumentHtml = `
    <div class="back-cover" style="background:#2e3f5a; color:white; display:flex; align-items:center; justify-content:center; font-size:30px; font-weight:bold; width:400px; height:550px;">
        <div style="padding: 40px;">END OF DOCUMENT</div>
    </div>`;
  const blankPageHtml = `<div class="dynamic-page" style="background: white; width: 400px; height: 550px;"></div>`;

  // 3. Clear old pages
  const totalPagesBefore = magazine.turn("pages");
  // We loop and remove. Since we are on page 1 and stopped animations, it's safe.
  for (let i = totalPagesBefore; i > 1; i--) {
    if (magazine.turn("hasPage", i)) {
      magazine.turn("removePage", i);
    }
  }

  // 4. Add dynamic content
  const sectionsToRender = (!pageSections || pageSections.length === 0)
    ? pageEmptySectionData
    : pageSections;

  sectionsToRender.forEach((section) => {
    const text = section.text || "";
    const pages = text ? splitIntoPages(text, 120) : [];

    const createAndAdd = (content) => {
      const pageElement = $(`
        <div class="dynamic-page" style="background: white; width: 400px; height: 550px; font-family: 'Times New Roman', Times, serif;">
          <div style="border: 1px solid #ddd; margin: 15px; height: calc(100% - 30px); position: relative;">
            <div style="text-align: center; border-bottom: 1px double #999; margin: 10px 15px; padding-bottom: 5px;">
              <span style="text-transform: uppercase; font-size: 10px; letter-spacing: 1px; color: #555;">
                Local Government Code of the Philippines
              </span>
            </div>
            <div class="legal-content" style="padding: 10px 20px; font-size: 13px; line-height: 1.6; color: #222;">
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
      createAndAdd(section.title ? `<h2 style="margin-top:0;">${section.title}</h2>` : "");
    } else {
      pages.forEach((chunk) => {
        createAndAdd(`<h2 style="margin-top:0;">${section.title}</h2><p style="text-align: justify;">${chunk.replace(/\n/g, "<br>")}</p>`);
      });
    }
  });

  // 5. Add buffer and back cover
  if (magazine.turn("pages") % 2 === 0) {
    magazine.turn("addPage", $(blankPageHtml));
  }
  magazine.turn("addPage", $(endOfDocumentHtml));

  // 6. Sync and Navigate back if needed
  const finalTotal = magazine.turn("pages");

  if (wasAtEnd) {
    // Wait for the DOM to settle, then flip back to the end
    setTimeout(() => {
      magazine.turn("page", finalTotal);
    }, 300);
  }
}
$(function () {
  const magazine = $("#magazine");

  function getSettings() {
    let isMobile = window.innerWidth < 900;
    return {
      display: isMobile ? "single" : "double",
      width: isMobile ? window.innerWidth * 0.9 : 800,
      height: isMobile ? (window.innerWidth * 0.9) * 1.3 : 550,
      autoCenter: true
    };
  }

  // Initial Setup
  const settings = getSettings();
  magazine.turn({
    display: settings.display,
    width: settings.width,
    height: settings.height,
    autoCenter: true,
    elevation: 50,
    gradients: true,
    acceleration: true,
    when: {
      turning: function (event, page, view) {
      }
    }
  });

  $(window).on("resize", function () {
    const s = getSettings();
    magazine.turn("display", s.display);
    magazine.turn("size", s.width, s.height);
    magazine.turn("center");
  });

  // Keyboard navigation
  $(window).bind("keydown", function (e) {
    if (e.keyCode == 37) magazine.turn("previous");
    else if (e.keyCode == 39) magazine.turn("next");
  });
  magazine.on("touchstart", function (e) {
    const touch = e.originalEvent.touches[0];
    this.startX = touch.pageX;
  }).on("touchend", function (e) {
    const touch = e.originalEvent.changedTouches[0];
    const distX = touch.pageX - this.startX;
    if (Math.abs(distX) > 50) {
      if (distX > 0) magazine.turn("previous");
      else magazine.turn("next");
    }
  });
});