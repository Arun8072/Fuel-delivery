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

// Function to set a cookie
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + encodeURIComponent(value) + ";" + expires + ";path=/";
}



    // Form submission
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        // Determine the fuel type based on slider values
        const petrolQuantity = parseFloat(petrolSlider.value);
        const dieselQuantity = parseFloat(dieselSlider.value);
        let fuelType = '';

        if (petrolQuantity > 0 && dieselQuantity > 0) {
            fuelType = 'both';
        } else if (petrolQuantity > 0) {
            fuelType = 'petrol';
        } else if (dieselQuantity > 0) {
            fuelType = 'diesel';
        }

        // Collect form data into a JSON object
        const formData = {
            username: form.username.value,
            mobile: form.mobile.value,
            location: form.location.value,
            payment: form.payment.value,
            petrolQuantity,
            dieselQuantity,
            fuelType, // Add fuelType to formData
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
                    form.classList.add('hidden');
                    form.reset();
                    petrolSlider.value = 0;
                    dieselSlider.value = 0;
                    updateSliderValue(petrolSlider, petrolValue);
                    updateSliderValue(dieselSlider, dieselValue);
                    orderStatus.classList.remove('hidden');
                    
                    console.log("Order submitted successfully:", response.message);
                    
                  // Set a cookie for the user's mobile number
setCookie("userMobile", formData.mobile, 7); // Cookie will expire in 7 days
console.log("Cookie set for user mobile:", formData.mobile);
                    
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
        console.log(jsonData);
    });
    
    
    
    


    // Function to get a cookie value by name
    function getCookie(name) {
        const decodedCookie = decodeURIComponent(document.cookie);
        const cookies = decodedCookie.split(';');
        for (let cookie of cookies) {
            while (cookie.charAt(0) === ' ') {
                cookie = cookie.substring(1);
            }
            if (cookie.indexOf(name + "=") === 0) {
                return cookie.substring(name.length + 1, cookie.length);
            }
        }
        return null;
    }

    // Function to delete a cookie
    function deleteCookie(name) {
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }

// Fetch order data from orders.json
async function fetchOrderData(mobile) {
    try {
        const response = await fetch('orders.json');
        if (!response.ok) {
            throw new Error("Error fetching orders.json");
        }
        const orders = await response.json();

        // Filter all orders with the given mobile number
        const matchingOrders = orders.filter(order => order.mobile === mobile);

        // Return the last matching order, or null if no matches
        return matchingOrders.length > 0 ? matchingOrders[matchingOrders.length - 1] : null;
    } catch (error) {
        console.error("Failed to fetch order data:", error);
    }
}

    // Apply active classes based on order status
    function updateOrderStatus(orderData) {
        if (orderData.hasAccepted) {
            document.getElementById('statusAccepted').querySelector('.status-dot').classList.add('active');
        }
        if (orderData.hasOutForDelivery) {
            document.getElementById('statusOutForDelivery').querySelector('.status-dot').classList.add('active');
        }
        if (orderData.hasDelivered==true) {
            document.getElementById('statusDelivered').querySelector('.status-dot').classList.add('active');
        }
    }

    // Main logic
    const mobileCookie = getCookie("userMobile");
     if (mobileCookie) {
        form.classList.add('hidden');
        orderStatus.classList.remove('hidden');

        // Retrieve and update the order status
        fetchOrderData(mobileCookie).then(orderData => {
            
            if (orderData) {
                updateOrderStatus(orderData);
            } else {
                console.warn("No order found for the provided mobile number.");
            }
            
            const deliveredCookie = orderData.hasDelivered;

if (deliveredCookie == true) {
        deleteCookie("userMobile");
        alert("Previous Order completed");
    } 
    
        });
   
    }
    

});
document.addEventListener('DOMContentLoaded', function() {
            const locationInput = document.getElementById('location');
            const getLocationButton = document.getElementById('getLocation');

            getLocationButton.addEventListener('click', function() {
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        locationInput.value = `${latitude.toFixed(7)}, ${longitude.toFixed(7)}`;
                    }, function(error) {
                        console.error("Error: " + error.message);
                        alert("Unable to retrieve your location. Please enter it manually.");
                    });
                } else {
                    alert("Geolocation is not supported by your browser. Please enter your location manually.");
                }
            });
        });