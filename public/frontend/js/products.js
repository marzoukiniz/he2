//   when click on each color
function handleColorButtonClick(button) {
    let productId = button.getAttribute("data-product");
    let selectedColor = button.getAttribute("data-color");
    let container = document.getElementById(`length-stock-${productId}`);

    fetch(`/get-product-variations?product_id=${productId}&color=${selectedColor}`)
        .then(response => response.json())
        .then(data => {
            let html = "<ul>";
            data.variations.forEach(variation => {
                html += `<li class="length">${variation.length}</li> <li class="stock"> ${variation.stock}</li>`;
            });
            html += "</ul>";
            container.innerHTML = html;
        })
        .catch(error => console.error("Error fetching variations:", error));
}






// Function to filter products by color and length
function CheckColorFilter() {
    document.querySelectorAll('.filter-option').forEach(input => {
        input.addEventListener('change', function () {
            let selectedColors = [];
            let selectedLengths = [];

            // Get selected colors
            document.querySelectorAll('.color-filter:checked').forEach(checked => {
                selectedColors.push(checked.value);
            });

            // Get selected lengths
            document.querySelectorAll('.length-filter:checked').forEach(checked => {
                selectedLengths.push(checked.value);
            });

            // Send AJAX request with both filters
            fetch(colorFilterRoute, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ color: selectedColors, length: selectedLengths })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Response from server:", data); // Debugging
                if (data.success) {
                    document.getElementById('product-container').innerHTML = data.html;
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => console.error('AJAX Error:', error));
        });
    });
}

// Run when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', CheckColorFilter);
