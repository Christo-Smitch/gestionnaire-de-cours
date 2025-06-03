window.addEventListener("DOMContentLoaded", () => {
  const role = localStorage.getItem("role");
  if (role === "admin") {
    document.getElementById("btn-utilisateurs").style.display = "inline-block";
  }

  switchTab("planning");
  loadPlanning();
  loadChat();
  loadDocuments();
  loadUsersIfAdmin();
  setupChatToggle();
});

document.querySelectorAll(".bottom-nav button").forEach(btn => {
  btn.addEventListener("click", () => {
    const tab = btn.getAttribute("data-tab");
    switchTab(tab);
  });
});

function switchTab(tabName) {
  document.querySelectorAll(".tab-content").forEach(tab => {
    tab.classList.remove("active");
  });
  document.getElementById(`tab-${tabName}`).classList.add("active");
}

let semaineOffset = 0;

function changerSemaine(delta) {
  semaineOffset += delta;
  loadPlanning();
}

function loadPlanning() {
  fetch(`php/get_emploi.php?offset=${semaineOffset}`)
    .then(res => res.text())
    .then(html => {
      document.getElementById("emploi-tableau").innerHTML = html;

      document.querySelectorAll(".modifier-cellule").forEach(btn => {
        btn.addEventListener("click", (e) => {
          e.stopPropagation();
          const cell = btn.closest(".planning-cell");
          const date = cell.dataset.date;
          const heure = cell.dataset.heure;
          const ancienneMatiere = cell.querySelector(".matiere")?.textContent || "";
          const ancienEnseignant = cell.querySelector(".enseignant")?.textContent || "";

          const matiere = prompt("Nom de la matière :", ancienneMatiere);
          if (matiere === null) return;

          const enseignant = prompt("Nom de l’enseignant :", ancienEnseignant);
          if (enseignant === null) return;

          fetch("php/set_emploi.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ date, heure, matiere, enseignant })
          })
            .then(res => res.json())
            .then(data => {
              if (data.success) loadPlanning();
              else alert("Erreur lors de la mise à jour.");
            })
            .catch(() => alert("Erreur serveur."));
        });
      });
    });
}

function loadChat() {
  const role = localStorage.getItem("role") || "";

  fetch("php/get_chat.php", { credentials: "include" })
    .then(res => res.text())
    .then(html => {
      document.getElementById("messages").innerHTML = html;

      const ajouterBtn = document.getElementById("ajouterMessageBtn");
      if (role === "admin" && ajouterBtn) {
        ajouterBtn.style.display = "inline-block";
      } else if (ajouterBtn) {
        ajouterBtn.style.display = "none";
      }

      document.querySelectorAll(".delete-message").forEach(btn => {
        btn.addEventListener("click", () => {
          const id = btn.dataset.id;

          afficherConfirmation("Supprimer ce message ?", () => {
            fetch("php/delete_chat.php", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ id })
            })
              .then(res => res.json())
              .then(() => loadChat());
          });
        });
      });
    });
}

function loadDocuments() {
  fetch("php/get_docs.php", { credentials: "include" })
    .then(res => res.text())
    .then(html => {
      document.getElementById("liste-docs").innerHTML = html;
    });
}

function loadUsersIfAdmin() {
  fetch("php/set_users.php", { credentials: "include" })
    .then(res => {
      if (!res.ok) throw new Error("Non admin");
      return res.json();
    })
    .then(utilisateurs => {
      document.getElementById("btn-utilisateurs").style.display = "inline-block";
      const container = document.getElementById("utilisateurs-container");

      let html = "<h3>Utilisateurs enregistrés</h3><ul>";
      utilisateurs.forEach(u => {
        html += `
          <li>
            <strong>${u.nom}</strong><br>
            Rôle : <em>${u.role}</em>
            <button onclick=\"supprimerUtilisateur('${u.nom}')\">🗑</button>
          </li><hr>
        `;
      });
      html += "</ul>";

      html += `
        <h3>Ajouter un utilisateur</h3>
        <form id="ajout-user-form">
          <input type="text" id="nouveauNom" placeholder="Nom complet" required />
          <input type="password" id="nouveauMDP" placeholder="Mot de passe" required />
          <select id="nouveauRole" required>
            <option value="">Rôle</option>
            <option value="admin">Admin</option>
            <option value="prof">Prof</option>
            <option value="eleve">Élève</option>
          </select>
          <button type="submit">Ajouter</button>
        </form>
      `;

      container.innerHTML = html;

      document.getElementById("ajout-user-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const nom = document.getElementById("nouveauNom").value.trim();
        const mot_de_passe = document.getElementById("nouveauMDP").value.trim();
        const role = document.getElementById("nouveauRole").value;

        if (!nom || !mot_de_passe || !role) return alert("Tous les champs sont requis.");

        fetch("php/add_user.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          credentials: "include",
          body: JSON.stringify({ nom, mot_de_passe, role, session_role: localStorage.getItem("role") })
        })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              loadUsersIfAdmin();
            } else {
              alert("Erreur : " + data.error);
            }
          });
      });
    })
    .catch(() => {
      // utilisateur non admin → ne rien faire
    });
}

function supprimerUtilisateur(nom) {
  afficherConfirmation(`Supprimer l’utilisateur ${nom} ?`, () => {
    fetch("php/delete_user.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ nom })
    })
      .then(res => res.json())
      .then(() => loadUsersIfAdmin());
  });
}

function setupChatToggle() {
  const toggleBtn = document.getElementById("ajouterMessageBtn");
  if (toggleBtn) {
    toggleBtn.addEventListener("click", () => {
      const form = document.getElementById("chat-form");
      form.style.display = form.style.display === "none" ? "block" : "none";
    });
  }

  document.getElementById("envoyerMessage")?.addEventListener("click", () => {
    const message = document.getElementById("chatInput").value;
    if (!message.trim()) return;

    fetch("php/send_chat.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        auteur: localStorage.getItem("nom"),
        message
      })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById("chatInput").value = "";
          document.getElementById("chat-form").style.display = "none";
          loadChat();
        } else {
          alert("Erreur : " + data.error);
        }
      });
  });
}

function afficherConfirmation(message, onConfirm) {
  const modal = document.getElementById("confirmation-modal");
  const text = document.getElementById("confirmation-message");
  const ouiBtn = document.getElementById("confirm-yes");
  const nonBtn = document.getElementById("confirm-no");

  text.textContent = message;
  modal.style.display = "flex";

  function cleanup() {
    modal.style.display = "none";
    ouiBtn.removeEventListener("click", handleYes);
    nonBtn.removeEventListener("click", handleNo);
  }

  function handleYes() {
    cleanup();
    onConfirm();
  }

  function handleNo() {
    cleanup();
  }

  ouiBtn.addEventListener("click", handleYes);
  nonBtn.addEventListener("click", handleNo);
}

function logout() {
  localStorage.clear();
  window.location.href = "php/logout.php";
}
