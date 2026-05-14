document.addEventListener('DOMContentLoaded', () => {
    const rings = document.querySelectorAll('[data-progress]');
    rings.forEach((ring) => {
        const value = parseInt(ring.getAttribute('data-progress') || '0', 10);
        const circle = ring.querySelector('.progress');
        if (!circle) return;
        const circumference = 2 * Math.PI * 52;
        const offset = circumference - (value / 100) * circumference;
        circle.style.strokeDasharray = `${circumference}`;
        circle.style.strokeDashoffset = `${offset}`;
    });

    const downloadBtn = document.getElementById('download-report');
    if (downloadBtn && window.jspdf) {
        downloadBtn.addEventListener('click', () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const data = window.reportData || {};

            doc.setFontSize(16);
            doc.text('MindScan Mental Health Report', 14, 18);
            doc.setFontSize(11);
            doc.text(`Name: ${data.name || ''}`, 14, 30);
            doc.text(`Age: ${data.age || ''}`, 14, 36);
            doc.text(`Gender: ${data.gender || ''}`, 14, 42);
            doc.text(`Occupation: ${data.occupation || ''}`, 14, 48);
            doc.text(`Risk Level: ${data.risk_level || ''}`, 14, 56);
            doc.text(`Mental Health Score: ${data.mental_health_score || 0}`, 14, 64);
            doc.text(`Stress Score: ${data.stress_score || 0}`, 14, 70);
            doc.text(`Sleep Score: ${data.sleep_score || 0}`, 14, 76);
            doc.text(`Productivity Score: ${data.productivity_score || 0}`, 14, 82);
            doc.text(`Emotional Stability: ${data.emotional_score || 0}`, 14, 88);
            doc.text(`Sentiment: ${data.sentiment_label || ''}`, 14, 94);

            doc.text('Recommendations:', 14, 104);
            (data.recommendations || []).forEach((item, index) => {
                doc.text(`- ${item}`, 18, 112 + index * 6);
            });

            doc.save('MindScan-Report.pdf');
        });
    }

    const printBtn = document.getElementById('print-report');
    if (printBtn) {
        printBtn.addEventListener('click', () => {
            window.print();
        });
    }

    const shareBtn = document.getElementById('share-report');
    if (shareBtn) {
        shareBtn.addEventListener('click', async () => {
            const data = window.reportData || {};
            const shareText = `MindScan Report - Risk Level: ${data.risk_level || ''}`;
            if (navigator.share) {
                await navigator.share({
                    title: 'MindScan Report',
                    text: shareText,
                });
            } else {
                await navigator.clipboard.writeText(shareText);
                Swal.fire('Share link copied to clipboard.');
            }
        });
    }

    const voiceBtn = document.getElementById('voice-report');
    if (voiceBtn && 'speechSynthesis' in window) {
        voiceBtn.addEventListener('click', () => {
            const data = window.reportData || {};
            const summary = `Your risk level is ${data.risk_level}. Mental health score ${data.mental_health_score}. Stress score ${data.stress_score}. Sleep score ${data.sleep_score}. Productivity score ${data.productivity_score}.`;
            const utterance = new SpeechSynthesisUtterance(summary);
            utterance.rate = 1;
            utterance.pitch = 1;
            window.speechSynthesis.speak(utterance);
        });
    }
});
