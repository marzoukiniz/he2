// Function to handle color button click
function handleColorButtonClick(button) {
    let productId = button.getAttribute("data-product");
    let selectedColor = button.getAttribute("data-color");
    let container = document.getElementById(`length-stock-${productId}`);

    // Remove "active" class from all color buttons
    let colorButtons = document.querySelectorAll(`.color-btn[data-product="${productId}"]`);
    colorButtons.forEach(btn => btn.classList.remove("active"));

    // Add "active" class to the clicked color button
    button.classList.add("active");

    // Fetch product variations based on the selected color
    fetch(`/get-product-variations?product_id=${productId}&color=${selectedColor}`)
        .then(response => response.json())
        .then(data => {
            let html = "<ul>";
            data.variations.forEach(variation => {
                console.log(variation);
                html += `<li>
                            <a class="length" data-additional-cost="${variation.additional_cost}" 
                                onclick="updatePrice(this, ${productId})" data-length-id="${variation.length_id}">
                                ${variation.length}
                            </a> 
                             <input type="hidden" name="color_id" value="${variation.color_id}">
                            <span class="stock">Stock: ${variation.stock}</span>
                         </li>`;
            });
            html += "</ul>";
            container.innerHTML = html;
        })
        .catch(error => console.error("Error fetching variations:", error));
}

// Function to update price and set active class for length buttons
function updatePrice(button, productId) {
    let additionalCost = parseFloat(button.getAttribute("data-additional-cost")) || 0;
    let basePriceElement = document.getElementById(`price-${productId}`);
    let basePrice = parseFloat(basePriceElement.getAttribute("data-base-price"));

    // Calculate new price
    let newPrice = basePrice + additionalCost;

    // Update price display
    basePriceElement.textContent = `QAR ${newPrice.toFixed(1)}`;

    // Remove "active" class from all length buttons
    let buttons = document.querySelectorAll(`#length-stock-${productId} .length`);
    buttons.forEach(btn => btn.classList.remove("active"));

    // Add "active" class to the clicked button
    button.classList.add("active");
// Remove all existing hidden input fields with name="l-id"
document.querySelectorAll(`#length-stock-${productId} input[name="l_id"]`).forEach(input => input.remove());
 // Get length ID from button attribute
 let lengthId = button.getAttribute("data-length-id");
     // Create a new hidden input field
     let hiddenInput = document.createElement("input");
     hiddenInput.type = "hidden";
     hiddenInput.name = "l_id";
     hiddenInput.value = lengthId;
 
     // Insert the hidden input field right after the clicked button
     button.insertAdjacentElement("afterend", hiddenInput);
}

// Function to filter products by category, color, length, and brand
function CheckColorFilter() {
    document.querySelectorAll('.filter-option').forEach(input => {
        input.addEventListener('change', function () {
            let selectedColors = [];
            let selectedLengths = [];
            let selectedCategories = [];
            let selectedBrands = [];

            // Get selected colors
            document.querySelectorAll('.color-filter:checked').forEach(checked => {
                selectedColors.push(checked.value);
            });

            // Get selected lengths
            document.querySelectorAll('.length-filter:checked').forEach(checked => {
                selectedLengths.push(checked.value);
            });

            // Get selected categories
            document.querySelectorAll('.category-filter:checked').forEach(checked => {
                selectedCategories.push(checked.value);
            });

            // Get selected brands
            document.querySelectorAll('.brand-filter:checked').forEach(checked => {
                selectedBrands.push(checked.value);
            });

            // Send AJAX request with all filters (categories, colors, lengths, and brands)
            fetch(colorFilterRoute, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    color: selectedColors, 
                    length: selectedLengths, 
                    category: selectedCategories, 
                    brand: selectedBrands // Add brand filter here
                })
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
