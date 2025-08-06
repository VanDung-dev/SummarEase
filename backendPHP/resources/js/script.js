// Hàm chuyển đổi markdown thành HTML
function markdownToHtml(markdown) {
    let html = markdown.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    html = html.replace(/\*(.*?)\*/g, '<em>$1</em>');
    html = html.replace(/(?:\r\n|\r|\n){2,}/g, '</p><p>');
    html = html.replace(/(?:\r\n|\r|\n)/g, '<br>');
    html = '<p>' + html + '</p>';
    return html;
}

// Hàm hiển thị kết quả tóm tắt với định dạng markdown
function displaySummaryWithMarkdown(summaryText, outputElementId) {
    const outputArea = document.getElementById(outputElementId);
    if (outputArea) {
        outputArea.innerHTML = markdownToHtml(summaryText);
    }
}

// Hàm xử lý kết quả tóm tắt từ API
function handleSummaryResult(result) {
    if (result && result.summary) {
        displaySummaryWithMarkdown(result.summary, 'summary-output');
    } else {
        console.error('Invalid summary result format');
    }
}

// Khi tài liệu đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script loaded and ready to display markdown summaries');
});
