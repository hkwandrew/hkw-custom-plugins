document.addEventListener('DOMContentLoaded', function () {
    var clipboard = new ClipboardJS('#copy-filenames', {
        text: function () {
            return document.getElementById('filenames-textarea').value
        }
    })

    clipboard.on('success', function (e) {
        alert('Filenames copied to clipboard!')
    })

    clipboard.on('error', function (e) {
        alert('Failed to copy filenames.')
    })
})
