document.addEventListener("DOMContentLoaded", () => {
    const bars = document.querySelector(".bars");
    const sections = document.querySelector(".sections");

    // إظهار اقسام الصفحة وإخفائها في الجوالات

    bars.addEventListener("click", (el) => {
        el.stopPropagation();
        bars.classList.toggle("clicked");
        sections.classList.toggle("active");
        dropDown.classList.remove("active");
    });

    document.addEventListener("click", (e) => {
        if (!bars.contains(e.target) && !sections.contains(e.target)) {
            bars.classList.remove("clicked");
            sections.classList.remove("active");
        }
    });

    // تأثير كتابة النص

    const text = "عالم من أحدث الملابس والاكسسوارت";
    const el = document.getElementById("typewriter");
    let i = 0;

    function typeWriter() {
        el.textContent = text.slice(0, i);
        i++;

        if (i <= text.length) {
            setTimeout(typeWriter, 50);
        }
    }

    if (el) {
        typeWriter();
    }

    const start = document.querySelector(".start");

    if (start) {
        start.onclick = () => {
            window.scrollTo({
                top: 750,
                behavior: "smooth",
            });
        };
    }

    const dropDown = document.querySelector(".dropDown");
    const toggle = document.querySelector(".dropdown-toggle");

    if (toggle) {
        toggle.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropDown.classList.toggle("active");
        });
    }

    document.addEventListener("click", (e) => {
        if (dropDown && !dropDown.contains(e.target)) {
            dropDown.classList.remove("active");
        }
    });
});
