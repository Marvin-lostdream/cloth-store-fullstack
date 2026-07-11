// التحكم في Modal

function openModal(modalId) {
    document.getElementById(modalId).classList.add("show");
    document.body.style.overflow = "hidden";
}

function openEditModal(productId) {
    // فتح النافذة المنبثقة
    openModal("editModal");

    // إظهار مؤشر تحميل

    // جلب بيانات المنتج
    fetch(`/admin/product/${productId}/edit`)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((product) => {
            // ملء حقول النموذج بالبيانات
            document.getElementById("edit_id").value = product.id;
            document.getElementById("edit_name").value = product.name;
            document.getElementById("edit_price").value = product.price;
            document.getElementById("edit_is_available").value =
                product.is_available ? 1 : 0;
            document.getElementById("edit_category").value = product.category;
            document.getElementById("edit_has_discount").value =
                product.has_discount ? 1 : 0;

            // تعيين نوع المنتج
            const typeSelect = document.querySelector(
                '#editForm select[name="type"]',
            );
            if (typeSelect) {
                typeSelect.value = product.type;
            }

            // عرض الصورة الحالية
            const currentImg = document.getElementById("edit_current_img");
            if (product.image) {
                currentImg.src = product.image;
                currentImg.style.display = "block";
            } else {
                currentImg.style.display = "none";
            }

            // إخفاء معاينة الصورة الجديدة
            document.getElementById("editImagePreview").style.display = "none";

            // تحديث action النموذج
            document.getElementById("editForm").action =
                `/admin/product/${product.id}/edit`;

            // إخفاء مؤشر التحميل
            document.querySelector("#editModal .modal-content").style.opacity =
                "1";
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("حدث خطأ في تحميل بيانات المنتج");
            closeModal("editModal");
        });
}

function openDeleteModal(productId, productName) {
    document.getElementById("delete_product_name").textContent = productName;

    document.getElementById("deleteForm").action =
        `/admin/product/${productId}/delete`;

    openModal("deleteModal");
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove("show");
    document.body.style.overflow = "auto";

    if (modalId === "addModal") {
        const preview = document.getElementById("addImagePreview");

        preview.src = "#";
        preview.style.display = "none";

        document.querySelector("#addModal input[type='file']").value = "";
    }
    if (modalId === "editModal") {
        const preview = document.getElementById("editImagePreview");

        preview.src = "#";
        preview.style.display = "none";

        document.querySelector("#editModal input[type='file']").value = "";
    }
}

window.onclick = (e) => {
    if (e.target.classList.contains("modal")) {
        e.target.classList.remove("show");
        document.body.style.overflow = "auto";

        const addPreview = document.getElementById("addImagePreview");
        if (addPreview) {
            addPreview.src = "#";
            addPreview.style.display = "none";
            document.querySelector("#addModal input[type='file']").value = "";
        }
        const editPreview = document.getElementById("editImagePreview");
        if (editPreview) {
            editPreview.src = "#";
            editPreview.style.display = "none";
            document.getElementById("editPreviewLabel").style.display = "none";
            document.querySelector("#editModal input[type='file']").value = "";
        }
    }
};

// إظهار الصورة عند الإضافة والتعديل

function previewImage(previewId) {
    const preview = document.getElementById(previewId);
    const url = input.value.trim();

    if (url && (url.startsWith("http://") || url.startsWith("https://"))) {
        preview.src = url;
        preview.style.display = "block";
        preview.style.maxWidth = "150px";
        preview.style.maxHeight = "150px";
        preview.style.objectFit = "cover";
        preview.style.borderRadius = "5px";
    } else {
        preview.src = "#";
        preview.style.display = "none";
    }
}
