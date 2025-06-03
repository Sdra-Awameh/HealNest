function showNotification(message, type = 'info') {
    const notificationStyles = {
        success: {
            gradient: 'linear-gradient(135deg, #6AE3A1 0%, #4CB963 100%)',
            borderColor: '#2E8B57',
            textColor: '#ffffff'
        },
        error: {
            gradient: 'linear-gradient(135deg, #FF6B6B 0%, #FF4757 100%)',
            borderColor: '#D63031',
            textColor: '#ffffff'
        },
        warning: {
            gradient: 'linear-gradient(135deg, #FFD700 0%, #FFA500 100%)',
            borderColor: '#FF8C00',
            textColor: '#333333'
        },
        info: {
            gradient: 'linear-gradient(135deg, #5F9EA0 0%, #4682B4 100%)',
            borderColor: '#1E90FF',
            textColor: '#ffffff'
        }
    };

    Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: "right",
        stopOnFocus: true,
        style: {
            background: notificationStyles[type].gradient,
            color: notificationStyles[type].textColor,
            borderRadius: '8px',
            boxShadow: '0 4px 6px rgba(0,0,0,0.1)',
            border: `2px solid ${notificationStyles[type].borderColor}`,
            fontWeight: '600',
            padding: '12px 20px',
            minWidth: '250px',
            textAlign: 'center'
        }
    }).showToast();
}