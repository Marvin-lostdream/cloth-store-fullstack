document.addEventListener("DOMContentLoaded", () => {
    const registerform = document.querySelector(".register-form");
    const loginForm = document.querySelector(".login-form");

    const emailRegex = /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/;

    // functions

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content;
    }

    function validateRequired(...fields) {
        return fields.every((field) => field && field.trim().length > 0);
    }

    if (registerform) {
        registerform.addEventListener("submit", async (e) => {
            e.preventDefault();

            const registerBtn = document.getElementById("register-Btn");

            const nameRegisterInput = document
                .getElementById("register-name")
                ?.value?.trim();
            const emailRegisterInput = document
                .getElementById("register-email")
                ?.value?.trim();
            const passwordRegisterInput =
                document.getElementById("register-password")?.value;
            const passwordConfirmationInput = document.getElementById(
                "password_confirmation",
            )?.value;

            if (
                !validateRequired(
                    nameRegisterInput,
                    emailRegisterInput,
                    passwordRegisterInput,
                    passwordConfirmationInput,
                )
            ) {
                alert("جميع الحقول مطلوبة");
                return;
            }

            if (!emailRegex.test(emailRegisterInput)) {
                alert("يرجى كتابة الإيميل بشكل صحيح");
                return;
            }

            if (passwordRegisterInput !== passwordConfirmationInput) {
                alert("كلمات المرور غير متطابقة");
                return;
            }

            if (passwordRegisterInput.length < 8) {
                alert("كلمة المرور يجب أن تكون 8 أحرف على الأقل");
                return;
            }

            const csrfToken = getCsrfToken();
            if (!csrfToken) {
                alert("خطأ بالمصادقة");
                return;
            }

            registerBtn.disabled = true;
            registerBtn.textContent = "جاري التسجيل...";

            const formData = new FormData();

            formData.append("name", nameRegisterInput);
            formData.append("email", emailRegisterInput);
            formData.append("password", passwordRegisterInput);
            formData.append("password_confirmation", passwordConfirmationInput);
            try {
                const response = await fetch("/register", {
                    method: "POST",
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: formData,
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = "/";
                } else {
                    let errorMessage = "حدث خطأ أثناء التسجيل";
                    if (data.errors) {
                        errorMessage = Object.values(data.errors)
                            .flat()
                            .join("\n");
                    } else if (data.message) {
                        errorMessage = data.message;
                    }
                    alert(errorMessage);
                }
            } catch (error) {
                console.error("خطأ في الاتصال:", error);
                alert("حدث خطأ في الاتصال بالخادم");
            } finally {
                registerBtn.disabled = false;
                registerBtn.textContent = "تسجيل مستخدم جديد";
            }
        });
    }

    if (loginForm) {
        const loginBtn = document.getElementById("login-Btn");

        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const emailLoginInput = document
                .getElementById("login-email")
                ?.value?.trim();
            const passwordLoginInput =
                document.getElementById("login-password")?.value;

            if (!validateRequired(emailLoginInput, passwordLoginInput)) {
                alert("جميع الحقول مطلوبة");
                return;
            }

            if (!emailRegex.test(emailLoginInput)) {
                alert("يرجى كتابة الإيميل بشكل صحيح");
                return;
            }

            const csrfToken = getCsrfToken();
            if (!csrfToken) {
                alert("خطأ بالمصادقة");
                return;
            }

            loginBtn.disabled = true;
            loginBtn.textContent = "جاري تسجيل الدخول...";

            const formData = new FormData();
            formData.append("email", emailLoginInput);
            formData.append("password", passwordLoginInput);

            try {
                const response = await fetch("/login", {
                    method: "POST",
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: formData,
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = "/";
                } else if (response.status === 422 && data.errors) {
                    alert(Object.values(data.errors).flat().join("\n"));
                } else if (response.status === 419) {
                    alert("انتهت صلاحية الجلسة، يرجى تحديث الصفحة");
                    window.location.reload();
                } else {
                    alert(data.message || "حدث خطأ أثناء تسجيل الدخول");
                }
            } catch (error) {
                console.error("خطأ في الاتصال:", error);
                alert("حدث خطأ في الاتصال بالخادم");
            } finally {
                loginBtn.disabled = false;
                loginBtn.textContent = "تسجيل دخول";
            }
        });
    }
});
