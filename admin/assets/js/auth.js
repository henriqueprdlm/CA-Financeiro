window.addEventListener("load", async function () {
    try {
        const response = await fetch('assets/locales/clerk-ptBR.json');
        if (!response.ok) throw new Error('Falha no fetch: ' + response.status);
        const ptBR = await response.json();

        await Clerk.load({ localization: ptBR });

        if (!Clerk.user) {
            window.location.href = "/cafinanceiro/admin/login.html";
        } else {
            const userButtonDiv = document.getElementById("user-button");
            Clerk.mountUserButton(userButtonDiv, {
                afterSignOutUrl: '/cafinanceiro',
                localization: ptBR
            });

            const observer = new MutationObserver(() => {
                if (userButtonDiv) {
                    const header = document.querySelector("header");
                    if (header) {
                        header.style.display = "flex";
                        header.style.justifyContent = "space-between";
                        header.style.alignItems = "center";
                    }
                }
            });
            observer.observe(userButtonDiv, { childList: true, subtree: true });
        }
    } catch (error) {
        console.error("Erro ao carregar Clerk ou traduções:", error);
    }
});
