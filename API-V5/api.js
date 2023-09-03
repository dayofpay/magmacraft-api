class Settings {
    constructor(delay) {
        this.delay = delay;
    }
}

async function fetchData(data, service, customDelay = {
    delay: 2500
}) {
    const url = `https://v-devs.eu/softwares/magmacraft/services/${service}/${data.data}`;

    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();

        if (customDelay) {
            await new Promise(resolve => setTimeout(resolve, customDelay));
        } else {
            await new Promise(resolve => setTimeout(resolve, 2500));
        }

        return result;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

async function main() {

    // Valid Services

    // LHRV - Get user last join(data=username)
    // battlepass - Get user battle pass data (quests, challenges, and others)(data=username)
    // CoreService - Get User core data (such as uuid, join timestamp, and other)(data=username)
    // parkourGames - Returns User parkour games(data=username)
    // clanPunishments - Returns clan punishments(data=clanName)
    // prefix - Returns user prefix(data=username)
    // tokens - Rerturns user tokens(data=username)
    try {
        const result = await fetchData({
            "data": "dayofpay"
        }, 'battlepass', undefined); // If you want to use custom delay change the 'undefined' to time in MS (1 Second = 1000)
        console.log(result);
    } catch (error) {
        console.error(error);
    }
}

// Call the main function to start the process
main();







// Example usage of coreservice



// Get Core User Data

let getUserUUID = fetch(`https://v-devs.eu/softwares/magmacraft/services/coreservice/{{$authData->auth_user}}?data=uuid`);
let apiResponse = null;
getUserUUID
    .then(response => {
        if (response.ok) {
            apiResponse = response.json();
            document.getElementById('userUUID').textContent = apiResponse;
            return apiResponse;
        } else {
            throw new Error('Failed to fetch UUID');
        }
    })
    .then(data => {
        console.log(data);
        //document.getElementById('userUUID').textContent = data["data"];
    })
    .catch(error => {
        console.error('Error:', error);
    });