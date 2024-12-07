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
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = {
            username: form.username.value,
            mobile: form.mobile.value,
            location: form.location.value,
            payment: form.payment.value,
            petrolQuantity: petrolSlider.value,
            dieselQuantity: dieselSlider.value,
            timestamp: new Date().toISOString()
        };

        try {
            const response = await fetch('submit_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            });

            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    form.reset();
                    petrolSlider.value = 0;
                    dieselSlider.value = 0;
                    updateSliderValue(petrolSlider, petrolValue);
                    updateSliderValue(dieselSlider, dieselValue);
                    orderStatus.classList.remove('hidden');
                    console.log('Order submitted successfully:', result.message);
                } else {
                    alert('Error submitting order: ' + result.message);
                }
            } else {
                throw new Error('Failed to submit order. HTTP status: ' + response.status);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while submitting the order. Please try again.');
        }
    });
});
