document.addEventListener("DOMContentLoaded", () => {
    // ====== عناصر المودال ======
    const logoutModal = document.getElementById("logoutModal");
    const confirmLogoutBtn = document.getElementById("confirmLogoutBtn");
    const cancelLogoutBtn = document.getElementById("cancelLogoutBtn");
    const logoutBtn = document.getElementById("logout-Btn");

    // ====== دالة فتح المودال ======
    function openLogoutModal() {
        if (logoutModal) {
            logoutModal.classList.add("active");
            logoutModal.style.display = "flex";
            document.body.style.overflow = "hidden";
        }
    }

    // ====== دالة إغلاق المودال ======
    function closeLogoutModal() {
        if (logoutModal) {
            logoutModal.classList.remove("active");
            logoutModal.style.display = "none";
            document.body.style.overflow = "auto"; // إعادة التمرير
        }
    }

    async function performLogout() {
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]',
        )?.content;

        if (!csrfToken) {
            alert("خطأ في المصادقة");
            confirmLogoutBtn.innerHTML =
                '<i class="fa-solid fa-check"></i> نعم، تسجيل خروج';
            confirmLogoutBtn.disabled = false;
            confirmLogoutBtn.style.opacity = "1";
            return;
        }

        try {
            const response = await fetch("/logout", {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                },
            });

            const data = await response.json();

            if (response.ok && data.success) {
                localStorage.removeItem("cart");
                window.location.href = "/";
            } else {
                alert(data.message || "حدث خطأ أثناء تسجيل الخروج");

                closeLogoutModal();
            }
        } catch (error) {
            console.error(" خطأ في الاتصال:", error);
            alert("حدث خطأ في الاتصال بالخادم");

            closeLogoutModal();
        }
    }

    if (logoutBtn) {
        logoutBtn.addEventListener("click", function (e) {
            e.preventDefault();
            openLogoutModal();
        });
    }

    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener("click", performLogout);
    }

    // عند الضغط على زر الإلغاء
    if (cancelLogoutBtn) {
        cancelLogoutBtn.addEventListener("click", closeLogoutModal);
    }

    // إغلاق المودال عند الضغط خارج المحتوى
    if (logoutModal) {
        logoutModal.addEventListener("click", function (e) {
            if (e.target === this) {
                closeLogoutModal();
            }
        });
    }
});
