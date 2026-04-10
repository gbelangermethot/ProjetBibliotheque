function selectionerDocument(tr) {
    const id = tr.dataset.id;
    document.getElementById('selected_id').value = id;
    document.getElementById('selectForm').submit()
}

(function () {
  const menu = document.getElementById("mainMenu");
  const btn = menu?.querySelector(".menu-toggle");
  const list = document.getElementById("menuList");
  if (!menu || !btn || !list) return;

  btn.addEventListener("click", () => {
    const isOpen = menu.classList.toggle("open");
    btn.setAttribute("aria-expanded", isOpen ? "true" : "false");
  });

  // Optionnel: fermer après clic sur un lien
  list.addEventListener("click", (e) => {
    if (e.target.tagName === "A" && menu.classList.contains("open")) {
      menu.classList.remove("open");
      btn.setAttribute("aria-expanded", "false");
    }
  });
})();