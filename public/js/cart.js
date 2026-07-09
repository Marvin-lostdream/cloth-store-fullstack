let cart = JSON.parse(localStorage.getItem("cart")) || [];

// إضافة منتج
function addToCart(product) {
    const existing = cart.find((item) => item.id === product.id);

    if (existing) {
        existing.quantity++;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: product.has_discount ? product.price * 0.9 : product.price,
            image: product.image,
            quantity: 1,
        });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartUI();
    showMessage("✅ تم إضافة المنتج للسلة");
}

// حذف منتج
function removeFromCart(productId) {
    cart = cart.filter((item) => item.id !== productId);
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartUI();
    displayCart();
}

// تحديث الواجهة
function updateCartUI() {
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    const badge = document.querySelector(".cart-count");
    if (badge) badge.textContent = count;
}

// عرض رسالة
function showMessage(text) {
    const div = document.querySelector(".added");
    if (div) {
        div.innerHTML = text;
        div.style.display = "block";
        setTimeout(() => (div.style.display = "none"), 3000);
    }
}

// عرض السلة في الصفحة
function displayCart() {
    const container = document.querySelector(".products");
    const totalContainer = document.querySelector(".total");

    if (!container) return;

    container.innerHTML = "";

    if (cart.length === 0) {
        container.innerHTML = '<p class="empty-cart">السلة فارغة</p>';
        if (totalContainer) totalContainer.innerHTML = "";
        return;
    }

    let total = 0;

    cart.forEach((item) => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;

        const div = document.createElement("div");
        div.classList.add("cart");
        div.innerHTML = `
            <img src="${item.image ? "/storage/" + item.image : "/img/default.png"}" alt="${item.name}" />
            <h3>${item.name}</h3>
            <div class="info">
                <p>السعر: <span>${item.price} ل.س</span></p>
                <p>الكمية: <span>${item.quantity}</span></p>
            </div>
            <button class="deleteCart" data-id="${item.id}">إزالة</button>
        `;
        container.appendChild(div);
    });

    // المجموع الكلي
    if (totalContainer) {
        totalContainer.innerHTML = `
            <p style="font-weight:bold;font-size:1.2rem;">
                السعر الإجمالي:
                <span style="color:#4ab323;font-weight:bold;">${total} ل.س</span>
            </p>
        `;
    }

    // أزرار الحذف
    document.querySelectorAll(".deleteCart").forEach((btn) => {
        btn.addEventListener("click", function () {
            removeFromCart(parseInt(this.dataset.id));
        });
    });
}

// ===== إتمام الطلب =====
document.querySelector(".payBtn")?.addEventListener("click", function () {
    if (cart.length === 0) {
        alert("السلة فارغة!");
        return;
    }

    // التحقق من تسجيل الدخول
    if (!document.querySelector(".dropDown")) {
        alert("يرجى تسجيل الدخول أولاً");
        window.location.href = "/login";
        return;
    }

    fetch("/api/checkout", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
        body: JSON.stringify({ items: cart }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                cart = [];
                localStorage.removeItem("cart");
                alert(" تم إتمام الطلب بنجاح!");
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            alert("حدث خطأ في الاتصال");
            console.error(error);
        });
});

// ===== عند تحميل الصفحة =====
document.addEventListener("DOMContentLoaded", function () {
    displayCart();
    updateCartUI();
});
