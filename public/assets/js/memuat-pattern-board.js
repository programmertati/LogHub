function selectPattern(pattern) {
    var selectedPattern = document.querySelector('#pattern-field');
    selectedPattern.value = pattern;

    var allPatterns = document.querySelectorAll('.h-full');
    allPatterns.forEach(function(item) {
        item.classList.remove('border-black');
    });

    var selectedPatternElement = document.getElementById('pattern-' + pattern);
    selectedPatternElement.classList.add('border-black');

    var allChecks = document.querySelectorAll('.fa-circle-check');
    allChecks.forEach(function(item) {
        item.parentElement.style.opacity = '0';
    });

    var selectedCheck = document.getElementById('check-' + pattern);
    selectedCheck.style.opacity = '100';
}

function selectPattern2(cover, cardId) {
    var selectedPattern = document.querySelector('#cover-field-' + cardId);
    selectedPattern.value = cover;

    var allPatterns = document.querySelectorAll('.h-full');
    allPatterns.forEach(function(item) {
        item.classList.remove('border-black');
    });

    var selectedPatternElement = document.getElementById('cover-' + cover + '-' + cardId);
    selectedPatternElement.classList.add('border-black');

    var allChecks = document.querySelectorAll('.fa-circle-check');
    allChecks.forEach(function(item) {
        item.parentElement.style.opacity = '0';
    });

    var selectedCheck = document.getElementById('check-' + cover + '-' + cardId);
    selectedCheck.style.opacity = '100';
}