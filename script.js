document.addEventListener("DOMContentLoaded", function () {

    const hamburger = document.getElementById("hamburger");
    const sideMenu = document.getElementById("sideMenu");
    const overlay = document.getElementById("menuOverlay");

    if (!hamburger || !sideMenu || !overlay) {
        console.log("Menu elements missing!");
        return;
    }

    // OPEN MENU
    hamburger.addEventListener("click", function () {
        sideMenu.classList.add("show");
        overlay.classList.add("show");
    });

    // CLOSE MENU (click outside)
    overlay.addEventListener("click", function () {
        sideMenu.classList.remove("show");
        overlay.classList.remove("show");
    });

});

    /* ===== DARK MODE ===== */
    if (localStorage.getItem("darkMode") === "on") document.body.classList.add("dark");
    if (darkToggle) {
        darkToggle.addEventListener("click", () => {
            document.body.classList.toggle("dark");
            localStorage.setItem(
                "darkMode",
                document.body.classList.contains("dark") ? "on" : "off"
            );
        });
    }

    /* ===== LOGOUT ===== */
    if (logoutBtn) {
        logoutBtn.addEventListener("click", () => {
            localStorage.removeItem("quickBiteUser");
            alert("Logged out!");
            location.href = "index.html";
        });
    }

    /* ===== ADD TO CART ===== */
    const addButtons = document.querySelectorAll(".add-btn");
    addButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const item = {
                name: btn.dataset.name,
                price: parseFloat(btn.dataset.price),
                img: btn.dataset.img,
                qty: 1
            };
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            const existing = cart.find(i => i.name === item.name);
            if (existing) existing.qty++; else cart.push(item);
            localStorage.setItem("cart", JSON.stringify(cart));
            alert("✅ Added to cart!");
        });
    });

    /* ===== LOAD CART PAGE ===== */
    const cartGrid = document.getElementById("cartGrid");
    const cartTotal = document.getElementById("cartTotal");
    if (cartGrid && cartTotal) {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        let total = 0;
        cartGrid.innerHTML = "";
        cart.forEach((item, index) => {
            total += item.price * item.qty;
            cartGrid.innerHTML += `
                <div class="menu-card">
                    <img src="${item.img}">
                    <h3>${item.name}</h3>
                    <p>$${item.price} × ${item.qty}</p>
                    <button class="remove-btn" onclick="removeItem(${index})">×</button>
                </div>
            `;
        });
        cartTotal.textContent = "Total: $" + total.toFixed(2);
    }

    /* ===== LOAD PAYMENT PAGE ===== */
    const paymentSummary = document.getElementById("paymentSummary");
    const paymentTotal = document.getElementById("paymentTotal");
    if (paymentSummary && paymentTotal) {
        const cart = JSON.parse(localStorage.getItem("cart")) || [];
        let total = 0;
        paymentSummary.innerHTML = "";
        cart.forEach(item => {
            const itemTotal = item.price * item.qty;
            total += itemTotal;
            paymentSummary.innerHTML += `<p>${item.qty} × ${item.name} <span>$${itemTotal.toFixed(2)}</span></p>`;
        });
        paymentTotal.textContent = "$" + total.toFixed(2);
    }

});

/* ===== REMOVE ITEM FROM CART ===== */
function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    location.reload();
}

/* ===== GO TO PAYMENT ===== */
function goToPayment() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    if (cart.length === 0) {
        alert("Your cart is empty!");
        return;
    }
    location.href = "payment.html";
}

/* ===== PAY NOW ===== */
function payNow() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    if (cart.length === 0) {
        alert("Your cart is empty!");
        return;
    }

    // Save order as Delivered in localStorage
    const orders = JSON.parse(localStorage.getItem("orders")) || [];
    const total = cart.reduce((sum, i) => sum + i.price * i.qty, 0);
    const newOrder = {
        id: Date.now(),
        items: cart,
        total: total.toFixed(2),
        status: "Delivered",
        date: new Date().toLocaleString()
    };
    orders.push(newOrder);
    localStorage.setItem("orders", JSON.stringify(orders));
    localStorage.removeItem("cart");

    alert("✅ Payment successful! Order delivered.");
    location.href = "menu.html"; // Redirect to menu or home page
	
}
/* ===== LOGIN / REGISTER ===== */
const loginForm = document.getElementById("loginForm");
if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("password").value.trim();
        const firstName = document.getElementById("firstName").value.trim();
        const lastName = document.getElementById("lastName").value.trim();
        const age = document.getElementById("age").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const gender = document.getElementById("gender").value;

        if (!username || !password) {
            alert("Username and password are required!");
            return;
        }

        // Save user info in localStorage
        const user = { username, password, firstName, lastName, age, phone, gender };
        localStorage.setItem("quickBiteUser", JSON.stringify(user));

        alert("✅ Logged in successfully!");
        location.href = "profile.html";
    });
}


