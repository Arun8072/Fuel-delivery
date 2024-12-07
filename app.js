document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('fuelOrderForm');
    const petrolSlider = document.getElementById('petrolQuantity');
    const dieselSlider = document.getElementById('dieselQuantity');
    const petrolValue = document.getElementById('petrolValue');
    const dieselValue = document.getElementById('dieselValue');
    const orderStatus = document.getElementById('orderStatus');

    function updateSliderStyle(slider, value) {
        const percent = (value - slider.min) / (slider.max - slider.min) * 100;
        slider.style.setProperty('--slider-pos', `${percent}%`);
    }

    function updateSliderValue(slider, valueElement) {
        const value = parseFloat(slider.value);
        valueElement.textContent = `${value} L`;
        updateSliderStyle(slider, value);
    }

    // Initialize sliders
    updateSliderValue(petrolSlider, petrolValue);
    updateSliderValue(dieselSlider, dieselValue);

    // Add event listeners
    petrolSlider.addEventListener('input', () => updateSliderValue(petrolSlider, petrolValue));
    dieselSlider.addEventListener('input', () => updateSliderValue(dieselSlider, dieselValue));

    // Form submission
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        // Collect form data into a JSON object
        const formData = {
            username: form.username.value,
            mobile: form.mobile.value,
            location: form.location.value,
            payment: form.payment.value,
            petrolQuantity: petrolSlider.value,
            dieselQuantity: dieselSlider.value,
            timestamp: new Date().toISOString()
        };

        // Convert data to JSON format
        const jsonData = JSON.stringify(formData);

        // Create a new XMLHttpRequest object
        const xhr = new XMLHttpRequest();

        // Specify the request type and URL of the PHP file
        xhr.open("POST", "submit_order.php", true);

        // Set the request header to indicate JSON content
        xhr.setRequestHeader("Content-Type", "application/json");

        // Define a callback function to handle the response
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    form.reset();
                    petrolSlider.value = 0;
                    dieselSlider.value = 0;
                    updateSliderValue(petrolSlider, petrolValue);
                    updateSliderValue(dieselSlider, dieselValue);
                    orderStatus.classList.remove('hidden');
                    console.log("Order submitted successfully:", response.message);
                } else {
                    alert("Error submitting order: " + response.message);
                }
            } else {
                console.error("Error:", xhr.statusText);
                alert("An error occurred while submitting the order. Please try again.");
            }
        };

        // Handle errors during the request
        xhr.onerror = function () {
            console.error("Request error.");
            alert("Failed to send the request. Please check your connection.");
        };

        // Send the JSON data
        xhr.send(jsonData);
    });
});
