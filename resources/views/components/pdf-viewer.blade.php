@props(['url'])

<div id="pdf-viewer" style="height: 80vh;"></div>

<script type="module">
    import EmbedPDF from 'https://cdn.jsdelivr.net/npm/@embedpdf/snippet@2/dist/embedpdf.js';

    EmbedPDF.init({
        type: 'container',
        target: document.getElementById('pdf-viewer'),
        src: @js($url),
        theme: {
            preference: 'system'
        }
    });
</script>
