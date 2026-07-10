document.addEventListener('alpine:init', () => {
    Alpine.store('cart', {
        items: JSON.parse(localStorage.getItem('cart') || '[]'),

        save() {
            localStorage.setItem('cart', JSON.stringify(this.items));
        },

        addToCart(product) {
            const existing = this.items.find((item) => item.id === product.id);
            if (existing) {
                existing.quantity += 1;
            } else {
                this.items.push({ ...product, quantity: 1 });
            }
            this.save();
        },

        removeFromCart(id) {
            this.items = this.items.filter((item) => item.id !== id);
            this.save();
        },

        updateQuantity(id, quantity) {
            if (quantity <= 0) {
                this.removeFromCart(id);
                return;
            }
            const item = this.items.find((item) => item.id === id);
            if (item) {
                item.quantity = quantity;
                this.save();
            }
        },

        clearCart() {
            this.items = [];
            this.save();
        },

        get totalItems() {
            return this.items.reduce((sum, item) => sum + item.quantity, 0);
        },

        get subtotal() {
            return this.items.reduce((sum, item) => sum + item.price * item.quantity, 0);
        },
    });
});
