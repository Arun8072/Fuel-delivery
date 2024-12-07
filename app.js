document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('fuelOrderForm');
    const petrolSlider = document.getElementById('petrolQuantity');
    const dieselSlider = document.getElementById('dieselQuantity');
    const petrolValue = document.getElementById('petrolValue');
    const dieselValue = document.getElementById('dieselValue');
    const orderStatus = document.getElementById('orderStatus');
    const statusList = document.getElementById('statusList');

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
    // Update the form submission part in app.js
form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    formData.append('timestamp', new Date().toISOString());

    try {
        const response = await fetch('submit_order.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        if (result.success) {
            form.reset();
            orderStatus.classList.remove('hidden');
            updateOrderStatus('statusSent');
            pollOrderStatus();
        } else {
            throw new Error(result.message || 'Unknown error occurred');
        }
    } catch (error) {
        console.error('Error:', error);
        alert(`An error occurred while submitting the order: ${error.message}`);
    }
});

    function updateOrderStatus(status) {
        const statusElement = document.getElementById(status);
        if (statusElement) {
            statusElement.querySelector('span:first-child').classList.remove('bg-gray-300');
            statusElement.querySelector('span:first-child').classList.add('bg-green-500');
        }
    }

    async function pollOrderStatus() {
        try {
            const response = await fetch('get_order_status.php');
            if (response.ok) {
                const status = await response.json();
                if (status.accepted) updateOrderStatus('statusAccepted');
                if (status.outForDelivery) updateOrderStatus('statusOutForDelivery');
                if (status.delivered) {
                    updateOrderStatus('statusDelivered');
                    return; // Stop polling if delivered
                }
                setTimeout(pollOrderStatus, 5000); // Poll every 5 seconds
            } else {
                throw new Error('Network response was not ok.');
            }
        } catch (error) {
            console.error('Error polling order status:', error);
        }
    }
});
