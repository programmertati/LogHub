// Textarea mengikuti jumlah data yang ada //
$(document).ready(function() {
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        if (textarea.id.startsWith('keterangan')) {
            const lineCount = textarea.value.split('\n').length;
            textarea.rows = Math.max(lineCount, 4);
        }
    });
});
// /Textarea mengikuti jumlah data yang ada //