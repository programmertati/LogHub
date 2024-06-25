let previousDownlink = null;
let previousRtt = null;

function updateNetworkInfo() {
    const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;

    if (connection) {
        document.getElementById('downlink').textContent = connection.downlink;
        document.getElementById('rtt').textContent = connection.rtt;

        if (previousDownlink !== null && previousDownlink !== connection.downlink) {
            blinkIcon('download-icon');
        }
        previousDownlink = connection.downlink;

        updateDownloadIcon(connection.downlink);

        updateWifiIcon(connection.rtt);

        connection.addEventListener('change', updateNetworkInfo);
    } else {
        document.getElementById('network-info').textContent = 'API Informasi Jaringan tidak didukung oleh browser Anda.';
    }
}

function blinkIcon(iconId) {
    const icon = document.getElementById(iconId);
    icon.style.animation = 'blink 1s infinite';
    
    setTimeout(() => {
        icon.style.animation = '';
    }, 3000);
}

function updateWifiIcon(rtt) {
    const wifiIcon = document.getElementById('wifi-icon');
    
    if (rtt == 0) {
        wifiIcon.style.color = 'rgba(0, 0, 0, 0.5)';
    } else if (rtt <= 100) {
        wifiIcon.style.color = '#3bc346';
    } else if (rtt > 100 && rtt <= 200) {
        wifiIcon.style.color = '#e9de48';
    } else if (rtt > 200) {
        wifiIcon.style.color = '#dc3545';
    } 
}

function updateDownloadIcon(downlink) {
    const downloadIcon = document.getElementById('download-icon');
    
    if (downlink == 0) {
        downloadIcon.style.color = 'rgba(0, 0, 0, 0.5)';
    } else {
        downloadIcon.style.color = '';
    }
}

updateNetworkInfo();

const style = document.createElement('style');
style.innerHTML = `
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }
`;
document.head.appendChild(style);