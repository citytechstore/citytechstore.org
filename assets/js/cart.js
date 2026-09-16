document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-to-cart-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            var productId = button.getAttribute('data-product-id');
            var originalText = button.textContent;

            button.disabled = true;

            fetch('cart/add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + encodeURIComponent(productId) + '&quantity=1'
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (data.success) {
                        button.textContent = 'Added!';
                        setTimeout(function () {
                            button.textContent = originalText;
                            button.disabled = false;
                        }, 1500);
                    } else {
                        alert(data.message || 'Could not add item to cart.');
                        button.disabled = false;
                    }
                })
                .catch(function (error) {
                    console.error('Add to cart failed:', error);
                    alert('Something went wrong adding this item to your cart.');
                    button.disabled = false;
                });
        });
    });
});
