window.addEventListener("load", async function () {
    await Clerk.load();

    if (!Clerk.user) {
        window.location.href = "/cafinanceiro/admin/login.html";
    } else {
        const userButtonDiv = document.getElementById("user-button");
        Clerk.mountUserButton(userButtonDiv, {
            afterSignOutUrl: '/cafinanceiro'
        });

        const observer = new MutationObserver(() => {
            if (userButtonDiv) {
                const header = document.querySelector("header");
                header.style.display = "flex";
                header.style.justifyContent = "space-between";
                header.style.alignItems = "center";
            }
        });
        observer.observe(userButtonDiv, { childList: true, subtree: true });
    }
});