// -------------------------------
// MEMBERS PARTYLIST
// -------------------------------
const members = [
  {
    img: "/assets/img/member-1.jpg",
    name: "Hon. Juan Dela Cruz",
    position: "Partylist Chairperson",
    description:
      "Championing barangay development, livelihood, and youth opportunities.",
    socials: {
      facebook: "https://facebook.com",
      twitter: "https://x.com",
      email: "mailto:juan.delacruz@example.com",
    },
  },
  {
    img: "/assets/img/member-2.jpg",
    name: "Hon. Maria Santos",
    position: "Kagawad",
    description:
      "Focused on health, social services, and senior citizen welfare.",
    socials: {
      facebook: "https://facebook.com",
      instagram: "#",
      email: "mailto:maria.santos@example.com",
    },
  },
  {
    img: "/assets/img/member-3.jpg",
    name: "Hon. Kevin Ramirez",
    position: "SK Chairperson",
    description:
      "Leads youth projects on sports, education, and digital skills.",
    socials: {
      facebook: "https://facebook.com",
      twitter: "#",
      email: "mailto:kevin.ramirez@example.com",
    },
  },
  {
    img: "/assets/img/member-4.jpg",
    name: "Hon. Liza Mendoza",
    position: "Party Secretary",
    description:
      "Ensures documentation, records, and public communications are transparent.",
    socials: {
      facebook: "https://facebook.com",
      instagram: "#",
      email: "mailto:liza.mendoza@example.com",
    },
  },
];

const partylistData = [
  {
    img: "/assets/img/partylist-1.jpg",
    title: "Kabataan sa Barangay",
    description:
      "Focused on youth empowerment, education, and skills training.",
  },
  {
    img: "/assets/img/partylist-2.jpg",
    title: "Barangay Senior Care",
    description:
      "Advocating health, pensions, and welfare for senior citizens.",
  },
  {
    img: "/assets/img/partylist-3.jpg",
    title: "Pamilyang Urdaneta",
    description: "Supporting women, solo parents, and vulnerable families.",
  },
  {
    img: "/assets/img/partylist-4.jpg",
    title: "Trabaho at Kabuhayan",
    description:
      "Focused on livelihood, local jobs, and small business support.",
  },
];
const aboutInfo = [
  {
    title: "Our Mission",
    type: "text",
    content:
      "We strive to uplift every barangay by promoting inclusive growth, accessible services, and community-driven programs that empower youth, families, workers, and seniors alike.",
  },
  {
    title: "Our Vision",
    type: "text",
    content:
      "A united and progressive Urdaneta where every sector feels heard, supported, and given equal opportunities to thrive — from grassroots communities to emerging youth leaders.",
  },
  {
    title: "Core Values",
    type: "list",
    items: [
      "Genuine Public Service",
      "Transparency & Accountability",
      "Community Participation",
      "Respect for Barangay Autonomy",
      "Integrity in Governance",
    ],
  },
];
function renderMembers() {
  const container = document.getElementById("membersGrid");
  container.innerHTML = "";

  members.forEach((m, idx) => {
    const card = `
      <div
        class="member-card bg-white rounded-xl shadow-md p-6 border border-gray-100 text-center cursor-pointer
               hover:shadow-xl hover:border-blue-200 relative"
        data-index="${idx}">
        
        <div class="absolute inset-0 bg-blue-50 opacity-0 pointer-events-none transition duration-200 card-bg"></div>

        <div class="relative flex flex-col items-center">
          <img src="${m.img}"
               class="w-24 h-24 mx-auto rounded-full object-cover mb-3 shadow-sm"
               alt="${m.position}">

          <h4 class="text-sm font-semibold text-gray-900">${m.name}</h4>
          <p class="text-xs text-gray-600">${m.position}</p>

          <!-- DETAILS: height + fade/slide controlled by CSS -->
          <div class="member-details mt-3">
            <p class="text-[11px] text-gray-700 leading-snug mb-2">
              ${m.description}
            </p>

            <div class="mt-2 flex items-center justify-center gap-3">
              ${
                m.socials.facebook
                  ? `
                  <a href="${m.socials.facebook}" target="_blank"
                     class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/80 border border-gray-200
                            shadow-sm hover:bg-blue-600 hover:border-blue-600 hover:text-white text-xs transition">
                    <i data-lucide="facebook" class="w-3 h-3"></i>
                  </a>`
                  : ""
              }

              ${
                m.socials.instagram
                  ? `
                  <a href="${m.socials.instagram}" target="_blank"
                     class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/80 border border-gray-200
                            shadow-sm hover:bg-pink-600 hover:border-pink-600 hover:text-white text-xs transition">
                    <i data-lucide="instagram" class="w-3 h-3"></i>
                  </a>`
                  : ""
              }

              ${
                m.socials.twitter
                  ? `
                  <a href="${m.socials.twitter}" target="_blank"
                     class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/80 border border-gray-200
                            shadow-sm hover:bg-gray-900 hover:border-gray-900 hover:text-white text-xs transition">
                    <i data-lucide="twitter" class="w-3 h-3"></i>
                  </a>`
                  : ""
              }

              <a href="${m.socials.email}"
                 class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/80 border border-gray-200
                        shadow-sm hover:bg-emerald-600 hover:border-emerald-600 hover:text-white text-xs transition">
                <i data-lucide="mail" class="w-3 h-3"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    `;

    container.insertAdjacentHTML("beforeend", card);
  });

  const cards = container.querySelectorAll(".member-card");

  cards.forEach((card) => {
    card.addEventListener("mouseenter", () => {
      card.classList.add("expanded");
      const bg = card.querySelector(".card-bg");
      if (bg) bg.classList.add("opacity-70");
    });

    card.addEventListener("mouseleave", () => {
      card.classList.remove("expanded");
      const bg = card.querySelector(".card-bg");
      if (bg) bg.classList.remove("opacity-70");
    });

    card.addEventListener("click", () => {
      card.classList.toggle("expanded");
    });
  });
}

function renderPartylist() {
  const container = document.getElementById("partylistGrid");
  container.innerHTML = "";

  partylistData.forEach((p) => {
    const card = `
                <div class="relative overflow-hidden rounded-xl shadow-md bg-gray-900 group
                            hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <img src="${p.img}"
                        alt="${p.title}"
                        class="w-full h-48 object-cover opacity-90 group-hover:opacity-100 transition duration-300" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                    <div class="absolute bottom-3 left-3 right-3">
                        <h3 class="text-sm font-bold text-white tracking-wide uppercase">
                            ${p.title}
                        </h3>
                        <p class="text-xs text-gray-200 leading-tight">
                            ${p.description}
                        </p>
                    </div>

                    <div class="absolute inset-0 opacity-0 group-hover:opacity-20 
                                bg-blue-400 transition duration-300 mix-blend-screen"></div>
                </div>
            `;

    container.insertAdjacentHTML("beforeend", card);
  });
}

function renderAboutCards() {
  const container = document.getElementById("aboutCards");
  container.innerHTML = "";

  aboutInfo.forEach((card) => {
    let html = `
                <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 hover:shadow-lg transition">
                    <h3 class="text-base font-semibold text-gray-900 mb-2">${card.title}</h3>
            `;

    if (card.type === "text") {
      html += `
                    <p class="text-xs text-gray-700 leading-relaxed">
                        ${card.content}
                    </p>
                `;
    }

    if (card.type === "list") {
      html += `<ul class="text-xs text-gray-700 leading-relaxed space-y-1">`;
      card.items.forEach((item) => {
        html += `<li>• ${item}</li>`;
      });
      html += `</ul>`;
    }

    html += `</div>`;
    container.insertAdjacentHTML("beforeend", html);
  });
}

renderAboutCards();

renderPartylist();
renderMembers();
