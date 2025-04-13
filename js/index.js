const BASE_URL = 'http://localhost/laboratorio/progettoMuseo/api/';
const APIKEY = 'b4st0I5868HdCLAdeotkrSPGdeT1Df9ixpeQpWgD';

document.addEventListener('DOMContentLoaded', () => {
    const callBtn = document.getElementById('callApi');
    const apiSelect = document.getElementById('apiSelect');
    const resultBox = document.getElementById('result');

    callBtn.addEventListener('click', async () => {
        const endpoint = apiSelect.value;
        resultBox.textContent = '// caricamento...';

        try {
            const response = await fetch(`${BASE_URL}api.php?uri=${endpoint}`, {
                headers: {
                    'APIKEY': APIKEY,
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            resultBox.textContent = JSON.stringify(data, null, 4);
        } catch (err) {
            resultBox.textContent = '// Errore nella richiesta: ' + err.message;
        }
    });
});
