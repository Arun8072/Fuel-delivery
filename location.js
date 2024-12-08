document.addEventListener('DOMContentLoaded', () => {
    const getLocationButton = document.getElementById('getLocation');
    const locationInput = document.getElementById('location');

    // Function to fetch user's current location
    function fetchLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    // Fill the input field with latitude and longitude
                    locationInput.value = `Lat: ${latitude}, Lon: ${longitude}`;
                    console.log(`Location fetched: ${latitude}, ${longitude}`);
                },
                (error) => {
                    console.error("Error fetching location:", error.message);
                    alert("Unable to fetch location. Please check your location settings.");
                }
            );
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    }

    // Add event listener to the button
    getLocationButton.addEventListener('click', fetchLocation);
});
