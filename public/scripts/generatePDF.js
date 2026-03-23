function generatePDF(number) {
    const element = document.body; 

    const options = {
        margin:       0.5,
        filename:     'funders_invoice_' + number + '.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true }, 
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    html2pdf().set(options).from(element).save();
}