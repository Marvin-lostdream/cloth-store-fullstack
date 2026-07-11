function addToCart(product) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

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

    const added = document.querySelector(".added");
    if (added) {
        added.style.display = "block";
        added.style.left = "0";
        added.innerHTML = "✅ تم إضافة المنتج للسلة";

        setTimeout(() => {
            added.style.left = "-100%";
        }, 2000);
    }

    updateCartCount();
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    const badge = document.querySelector(".cart-count");
    if (badge) {
        badge.textContent = count;
    }
}

function classActive(pElement, cElement) {
    const parent = document.querySelector(pElement);

    parent.addEventListener("click", (e) => {
        const target = e.target.closest(cElement);
        if (!target) return;

        parent.querySelectorAll(cElement).forEach((el) => {
            el.classList.remove("active");
        });
        target.classList.add("active");
    });
}

classActive(".all-sections ul", "li");
classActive(".clothes ul", "li");

const products = document.querySelector(".products");
const clothesSection = document.querySelector(".clothes ul");

async function fetchProducts(category, section = null) {
    try {
        let url = `api/products/${category}`;
        if (section) {
            url += `/${section}`;
        }

        const response = await fetch(url);
        const data = await response.json();

        return data.products || [];
    } catch (error) {
        console.log("Error fetching products ", error);
        return [];
    }
}

async function displayProducts(category, section) {
    products.innerHTML = "";

    const productList = await fetchProducts(category, section);

    const oldMsg = document.getElementById("msgNotFound");
    if (oldMsg) oldMsg.remove();

    if (productList.length === 0) {
        products.insertAdjacentHTML(
            "afterend",
            `<p id="msgNotFound" style="text-align:center; color:red; margin-bottom:30px;">لا توجد منتجات مطابقة</p>`,
        );
        return;
    }
    productList.forEach((product) => {
        productCard(product);
    });
}

function initializePage() {
    let path = window.location.pathname;
    let targetCategory;
    let defaultSection;

    if (path === "/men") {
        targetCategory = "men";
        defaultSection = "shirts";
    } else if (path === "/women") {
        targetCategory = "women";
        defaultSection = "shirts";
    } else if (path === "/kids") {
        targetCategory = "kids";
        defaultSection = "shirts";
    } else if (path === "/accessories") {
        targetCategory = "accessories";
        defaultSection = "accessories";
    }
    displayProducts(targetCategory, defaultSection);

    clothesSection.addEventListener("click", (e) => {
        const li = e.target.closest("li");
        if (!li) return;

        const section = e.target.dataset.section;
        displayProducts(targetCategory, section);
    });
}

initializePage();

function productCard(product) {
    const productDiv = document.createElement("div");
    productDiv.classList.add("product");
    const title = document.createElement("h3");
    title.textContent = product.name;

    const img = document.createElement("img");

    let imagePath = product.image;
    if (!imagePath.startsWith("http")) {
        imagePath = product.image;
    }

    img.src = imagePath;
    img.alt = product.name;
    const infoDiv = document.createElement("div");
    infoDiv.classList.add("info");
    const price = document.createElement("p");
    const finalPrice = product.has_discount
        ? product.price * 0.9
        : product.price;
    price.innerHTML = `السعر : <span>${finalPrice} ل.س</span>`;
    const status = document.createElement("p");
    status.innerHTML = `الحالة : <span style="color: ${product.is_available ? "blue" : "red"}">${product.is_available ? "متوفر" : "غير متوفر"}</span>`;
    infoDiv.appendChild(price);
    infoDiv.appendChild(status);

    const cartBtn = document.createElement("button");
    cartBtn.classList.add("cartBtn");
    cartBtn.textContent = product.is_available
        ? "إضافة إلى السلة"
        : "غير متاح حاليا";

    if (!product.is_available) {
        cartBtn.style.cssText =
            "background-color: #6e6e6e; color:black; pointer-events: none; user-select: none";
    } else {
        cartBtn.style.cssText = "background-color: #ff5722; color:black";
    }
    const detailsBtn = document.createElement("button");
    detailsBtn.classList.add("detailsBtn");
    detailsBtn.textContent = "تفاصيل المنتج";

    if (product.has_discount) {
        const specialDiv = document.createElement("div");
        specialDiv.classList.add("special");
        specialDiv.textContent = "حسم %10";
        productDiv.appendChild(specialDiv);
    }
    productDiv.appendChild(title);
    productDiv.appendChild(img);
    productDiv.appendChild(infoDiv);
    productDiv.appendChild(cartBtn);
    productDiv.appendChild(detailsBtn);

    products.appendChild(productDiv);

    cartBtn.addEventListener("click", function () {
        addToCart({
            id: product.id,
            name: product.name,
            price: product.price,
            has_discount: product.has_discount,
            image: product.image,
        });
    });

    detailsBtn.addEventListener("click", () => {
        const existingOverlay = document.querySelector(".overlay");
        const existingDetails = document.querySelector(".product-details");
        if (existingOverlay) existingOverlay.remove();
        if (existingDetails) existingDetails.remove();
        document.body.style.overflow = "hidden";
        document.body.insertAdjacentHTML(
            "beforeend",
            `
            <div class="overlay"></div>
            <div class="product-details">
                <button class="closeBtn"><i class="fa-solid fa-xmark"></i></button>
                <hr/>
                <div class="details">
                    <img src="${imagePath}" alt="${product.name}" />
                    <div class="infoDiv">
                        <div class="info">
                            <p>اسم المنتج : <span style="color:#795548">${product.name}</span></p>
                            <p>القياسات : <span style="color:#795548">${product.is_available ? "متوفر بجميع القياسات" : "لا يوجد"}</span></p>
                            <p>الحالة : <span style="color: ${product.is_available ? "#795548" : "red"}">${product.is_available ? "متوفر" : "غير متوفر"}</span></p>
                            <p>السعر : ${product.has_discount ? `<span style="text-decoration: line-through; color: #acacac;">${product.price}</span> <span style="color: #6d2e17;">${product.price * 0.9} ل.س</span>` : `<span style="color:#795548;"> ${product.price} ل.س</span>`}</p>
                        </div>
                        <button class="cartBtn detailsCartBtn" style="${product.is_available ? "background-color: #ff5722; color:black;" : "background-color: #6e6e6e; color:black; pointer-events: none; user-select: none"}">${product.is_available ? "إضافة إلى السلة" : "غير متاح حاليا"}</button>
                    </div>
                </div>
            </div>
        `,
        );

        const closeBtn = document.querySelector(".closeBtn");
        const overlay = document.querySelector(".overlay");
        const detailsCartBtn = document.querySelector(".detailsCartBtn");

        const closeModal = () => {
            document.querySelector(".product-details")?.remove();
            document.querySelector(".overlay")?.remove();
            document.body.style.overflow = "auto";
        };

        closeBtn.onclick = closeModal;
        overlay.onclick = closeModal;

        if (detailsCartBtn && product.is_available) {
            detailsCartBtn.onclick = function () {
                addToCart({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    has_discount: product.has_discount,
                    image: product.image,
                });
                closeModal();
            };
        }
    });
}

let search = document.getElementById("search");

search.addEventListener("input", () => {
    const value = search.value.toLowerCase().trim();
    const productsContainer = document.querySelector(".products");

    let msg = document.getElementById("msgNotFound");
    if (msg) msg.remove();

    if (value === "") {
        let path = window.location.pathname;
        let targetCategory;
        let defaultSection;

        if (path === "/men") {
            targetCategory = "men";
            defaultSection = "shirts";
        } else if (path === "/women") {
            targetCategory = "women";
            defaultSection = "shirts";
        } else if (path === "/kids") {
            targetCategory = "kids";
            defaultSection = "shirts";
        } else if (path === "/accessories") {
            targetCategory = "accessories";
            defaultSection = "accessories";
        }
        displayProducts(targetCategory, defaultSection);
        return;
    }

    let path = window.location.pathname;
    let category = "men";

    if (path === "/men") category = "men";
    else if (path === "/women") category = "women";
    else if (path === "/kids") category = "kids";
    else if (path === "/accessories") category = "accessories";

    fetchProducts(category)
        .then((allProducts) => {
            productsContainer.innerHTML = "";

            let found = false;

            allProducts.forEach((product) => {
                if (product.name.toLowerCase().includes(value)) {
                    productCard(product);
                    found = true;
                }
            });

            if (!found) {
                let existingMsg = document.getElementById("msgNotFound");
                if (existingMsg) existingMsg.remove();

                productsContainer.innerHTML = `<p id="msgNotFound" style="text-align:center; color:red; margin-bottom:30px;">🔍 لا توجد منتجات تطابق "${value}"</p>`;
            }
        })
        .catch((error) => {
            console.log("Error searching:", error);
        });
});

let btnAdded = false;

window.onscroll = () => {
    if (window.scrollY >= 400 && !btnAdded) {
        let upBtn = document.createElement("button");
        upBtn.classList.add("upBtn");
        upBtn.textContent = "↑";
        upBtn.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 20px;
            background: #ff0000;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 26px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.9);
            z-index: 80;
            opacity:0;
            transition:all 0.3s;
        `;
        document.body.appendChild(upBtn);
        btnAdded = true;
        upBtn.onclick = () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });
        };
    } else if (btnAdded) {
        let upBtn = document.querySelector(".upBtn");
        if (upBtn) {
            if (window.scrollY < 400) {
                upBtn.style.opacity = "0";
                upBtn.style.pointerEvents = "none";
            } else {
                upBtn.style.opacity = "1";
                upBtn.style.pointerEvents = "all";
            }
        }
    }
};
